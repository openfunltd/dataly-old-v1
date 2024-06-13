<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\LyAPI;
use App\Utils\TextDiff;

class LawDiffContoller extends Controller
{
    public function lawDiff($bill_id)
    {
        $bill = LyAPI::apiQuery("/bill/{$bill_id}", "查詢提案 {$bill_id}");
        if (! property_exists($bill, '對照表')) {
            // when there is no 對照表
        }
        $bill_id = $bill->billNo;

        $bills = LyAPI::apiQuery("/bill/{$bill_id}/related_bills", "查詢關聯提案 {$bill_id}");
        // merge with related_bills
        if (! property_exists($bills, 'bills')) {
            $bills = [];
            $bills[] = $bill;
        } else {
            $bills = $bills->bills;
            $bills = array_merge([$bill], $bills);
        }
        
        $diff = [];
        $related_bills = [];
        $bill_n_law_idx_mapping = [];
        foreach ($bills as $bill_idx => $bill) {
            if (! property_exists($bill, '對照表')) {
                continue;
            }

            //hot fix for 對照表會有沒有 rows 的狀況 bill_SN: 20委11005501
            if (! property_exists($bill->對照表[0], 'rows')) {
                $commits = $bill->對照表[1]->rows;
            } else {
                $commits = $bill->對照表[0]->rows;
            }

            $bill_n_law_indexes = [];
            $bill_n_law_indexes['bill_idx'] = $bill_idx;
            $bill_n_law_indexes['law_indexes'] = [];

            foreach ($commits as $commit) {
                $law_idx = self::getLawIndex($commit);
                $isNewLawIndex = (property_exists($commit, '現行') && $commit->現行 != '');
                if (! array_key_exists($law_idx, $diff)) {
                   $diff[$law_idx] = []; 
                   $diff[$law_idx]['current'] = ($isNewLawIndex) ? $commit->現行 : null;
                   $diff[$law_idx]['commits'] = new \stdClass();
                }
                $diff[$law_idx]['commits']->{$bill_idx} = (property_exists($commit, '修正')) ? $commit->修正 : $commit->增訂;
                $bill_n_law_indexes['law_indexes'][] = $law_idx;
            }
            $bill_n_law_idx_mapping[] = $bill_n_law_indexes;

            // render column values into related bills 
            $related_bill = [];
            $related_bill['bill_idx'] = $bill_idx;
            $related_bill['bill_name'] = self::parseBillName($bill);
            $related_bill['version_name'] = $bill->{'提案單位/提案委員'};
            $related_bill['non_first_proposers'] = self::parseNonFirstProposers($bill);
            $related_bill['bill_no'] = (property_exists($bill, '提案編號')) ? $bill->提案編號 : $bill->billNo;
            $related_bill['initial_date'] = self::getInitialDate($bill);
            $related_bills[$bill_idx] = $related_bill;
        }
        $diff_result = TextDiff::prettyHtmls($diff);

        return view('law-diff.single', [
            'nav' => 'law-diff',
            'related_bills' => $related_bills,
            'diff_result' => $diff_result,
            'bill_n_law_idx_mapping' => $bill_n_law_idx_mapping,
        ]);
    }

    private function getLawIndex($commit)
    {
        if (property_exists($commit, '現行') && $commit->現行 != '') {
            $text = $commit->現行;
        } else if (property_exists($commit, '修正')) {
            $text = $commit->修正;
        } else {
            $text = $commit->增訂;
        }
        $text = str_replace('　', ' ', $text);
        return explode(' ', $text)[0];
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

    private function parseNonFirstProposers($bill)
    {
        if (! property_exists($bill, '提案人') || count($bill->提案人) == 1) {
            return '';
        }
        $proposers = $bill->提案人;
        return implode('、', array_slice($proposers, 2));
    }

    private function getInitialDate($bill)
    {
        $initial_date = 'No Data';
        if (is_null($bill->議案流程) || count($bill->議案流程) === 0) {
            return $initial_date;
        }
        $date_array = $bill->議案流程[0]->日期;
        if (is_null($date_array) || count($date_array) === 0) {
            return $initial_date;
        }
        return $date_array[0];
    }
}
