<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\LegislatorHelper;
use App\Utils\LyAPI;

class BillController extends Controller
{
    public function bills() 
    {
        $termStat = self::requestTermStat();
        $terms = self::getTermOptions($termStat);
        $term = (request()->query('term')) ?? $terms[0];
        $sessionPeriods = self::getSessionPeriods($termStat, $term);
        $sessionPeriod = (request()->query('sessionPeriod')) ?? $sessionPeriods[0];
        $legislator_party_map = LegislatorHelper::requestLegislatorPartyMap($term);
        $targetSessionPeriods = [$sessionPeriod];
        if ($sessionPeriod == 'all') {
            $targetSessionPeriods = $sessionPeriods;
        }
        $rows = [];
        foreach ($targetSessionPeriods as $targetSessionPeriod) {
            $bills = self::requestBills($term, $targetSessionPeriod);
            $law_name_map = self::requestLawNameMap($bills);
            foreach ($bills as $bill) {
                $row = [];
                $row['links'] = self::buildLinks($bill);
                $row['law_diff'] = property_exists($bill, '對照表');
                $row['initial_date'] = self::getInitialDate($bill);
                $row['bill_id'] = $bill->提案編號 ?? 'No Data';
                $row['sessionPeriod'] = $bill->會期 ?? '- No Data';
                $row['proposer'] = self::getProposer($bill);
                $row['proposer_party'] = self::getParty($row['proposer'], $legislator_party_map);
                $row['bill_name'] = self::parseBillName($bill);
                $row['law_names'] = self::getLawNames($bill, $law_name_map);
                $rows[] = $row;
            }
        }
        array_unshift($sessionPeriods, 'all');
        return view('bill.list', [
            'nav' => 'bills',
            'terms' => $terms,
            'sessionPeriods' => $sessionPeriods,
            'rows' => $rows,
            'params' => [
                'term' => $term,
                'sessionPeriod' => $sessionPeriod
            ],
        ]);
    }

    private function requestTermStat()
    {
        $res = LyAPI::apiQuery('/stat', '查詢提案統計數據');
        return $res->bill->terms;
    }

    private function getTermOptions($termStat)
    {
        $terms = array_map(function($termData) {
            return $termData->term;
        }, $termStat);
        return $terms;
    }

    private function getSessionPeriods($termStat, $term)
    {
        $termData = array_filter($termStat, function($termData) use ($term) {
            return $termData->term == $term;
        });
        $termData = reset($termData);
        $sessionPeriods =  array_map(function($sessionPeriodData) {
            return $sessionPeriodData->sessionPeriod;
        },$termData->sessionPeriod_count);
        return $sessionPeriods;
    }

    private function requestBills($term, $session_period, $usingPager = false)
    {
        $url = "/bill/?term=$term&sessionPeriod=$session_period" .
            "&bill_type=法律案&bill_type=修憲案&proposal_type=委員提案&proposal_type=政府提案" .
            "&limit=2000&field=提案人&field=對照表&field=laws&field=議案流程";
        $reason = sprintf("查詢第 %s 屆第 %s 會期提案", $term, $session_period);
        $res = LyAPI::apiQuery($url, $reason);
        return $res->bills;
    }

    private function requestLawNameMap($bills)
    {
        $law_ids = [];
        $url_base = 'https://ly.govapi.tw/v1/law?';
        foreach ($bills as $bill) {
            if (! property_exists($bill ,'laws')) {
                continue;
            }
            foreach ($bill->laws as $law_id) {
                if (! in_array($law_id, $law_ids)) {
                    $law_ids[] = $law_id;
                }
            }
        }
        $law_ids_chunk = [];
        $chunk_size = 200;
        for ($i = 0; $i < count($law_ids); $i += $chunk_size) {
            $law_ids_chunk[] = array_slice($law_ids, $i, $chunk_size);
        }
        $laws = [];
        foreach ($law_ids_chunk as $chunk_ids) {
           $query = ''; 
           foreach ($chunk_ids as $law_id) {
               $query .= "id=$law_id&";
           }
           $url = $url_base . $query . "limit=200"; 
           $laws = array_merge($laws, self::requestLaws($url)); 
        }
        $law_name_map = array_reduce($laws, function($acc, $curr) {
            $acc[$curr['id']] = $curr['name'];
            return $acc;
        }, []);
        return $law_name_map;
    }

    private function requestLaws($url) {
        $laws = [];
        $res = Http::get($url);
        if ($res->successful()) {
            $laws = $res->json()['laws'];
        }
        return $laws;
    }

    private function buildLinks($bill)
    {
        $billNo = $bill->billNo;
        $law_diff = $bill->對照表 ?? null;
        $bill_SN = $bill->提案編號 ?? $billNo;
        $links = [];
        $links[] = ['公報網', "https://ppg.ly.gov.tw/ppg/bills/$billNo/details"];
        $links[] = [
            '對照表',
            ($law_diff) ? "law-diff/$bill_SN" : "",
        ];
        $links[] = [
            '舊版對照表',
            ($law_diff) ? "https://openfunltd.github.io/law-diff/bills.html?billNo=$billNo" : "",
        ];
        $links[] = ['國會API', "https://ly.govapi.tw/v1/bill/$billNo"];
        return $links;
    }

    private function getInitialDate($bill)
    {
        $initialDate = '- No Data';
        if (is_null($bill->議案流程) || count($bill->議案流程) === 0) {
            return $initialDate;
        }
        $dateArray = $bill->議案流程[0]->日期;
        if (is_null($dateArray) || count($dateArray) === 0) {
            return $initialDate;
        }
        return $dateArray[0];
    }

    private function getProposer($bill)
    {
        $proposers = $bill->提案人 ?? null;
        $proposal_from = $bill->{'提案單位/提案委員'} ?? null;
        if (isset($proposers) && count($proposers) > 0) {
            return $proposers[0];
        }
        if (isset($proposal_from)) {
            return $proposal_from;
        }
        return 'No Data';
    }

    private function parseBillName($bill)
    {
        $bill_name = $bill->議案名稱;
        if (mb_substr($bill_name, 0, 2) === "廢止") {
            $bill_name = explode('，', $bill_name)[0];
            $bill_name = str_replace(['「','」'], '', $bill_name);
        } else if (mb_substr($bill_name, 0, 3) === "擬撤回") {
            return $bill_name;
        } else {
            $start_idx = mb_strpos($bill_name, "「");
            $end_idx = mb_strpos($bill_name, "」");
            $bill_name = mb_substr($bill_name, $start_idx + 1, $end_idx - 1);
        }
        return $bill_name;
    }

    private function getLawNames($bill, $law_name_map)
    {
        $laws = $bill->laws ?? null;
        if (is_null($laws)) {
            return [];
        }
        $law_names = [];
        foreach ($laws as $law_id) {
            $law_names[] = $law_name_map[$law_id];
        }
        return $law_names;
    }

    private function getParty($proposer, $legislator_party_map) 
    {
        $proposer = str_replace(" ", "", $proposer);
        $proposer = str_replace("‧", "", $proposer);
        $party_end_idx = mb_strrpos($proposer, '立法院黨團');
        if ($party_end_idx !== false) {
            $party = mb_substr($proposer, 0, $party_end_idx);
            return $party;
        }
        if (array_key_exists($proposer, $legislator_party_map)) {
            return $legislator_party_map[$proposer];
        }
        return "";
    }
}
