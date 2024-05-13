<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MeetController extends Controller
{
    protected static $comt_map = [
        '15' => '內政委員會',
        '20' => '財政委員會',
        '22' => '教育及文化委員會',
        '23' => '交通委員會',
        '28' => '紀律委員會',
        '29' => '修憲委員會',
        '30' => '經費稽核委員會',
        '35' => '外交及國防委員會',
        '26' => '社會福利及衛生環境委員會委員會',
        '27' => '程序委員會',
        '19' => '經濟委員會',
        '36' => '司法及法制委員會',
    ];

    public function meets() 
    {
        $termStat = self::requestTermStat();
        $terms = self::getTermOptions($termStat);
        $term = (request()->query('term')) ?? $terms[0];
        $sessionPeriods = self::getSessionPeriods($termStat, $term);
        $sessionPeriod = (request()->query('sessionPeriod')) ?? 'all';
        $meets = self::requestMeets($term, $sessionPeriod);
        $gazettes = self::requestAllGzaettes();
        $rows = [];
        foreach ($meets as $meet) {
            $row = [];
            $row['dates'] = $meet['dates'];
            $row['meet_id'] = $meet['meet_id'];
            if (in_array($meet['meet_type'], ['院會', '黨團協商', '全院委員會'])) {
                $row['type_or_committee'] = [$meet['meet_type']];
            } else {
                $comt_names = [];
                foreach ($meet['committees'] as $comt_id) {
                    $comt_names[] = self::getComtName($comt_id);
                }
                $row['type_or_committee'] = $comt_names;
            }
            $row['name'] = $meet['name'];
            $row['meet_data'] = $meet['dates'];
            $row['proceedings'] = array_key_exists('議事錄', $meet) ? '議事錄' : '';
            $row['speeches'] = self::getSpeeches($meet);
            if (array_key_exists('公報發言紀錄', $meet)) {
                $row['gazette_publish_date'] = self::getGazettePublishDate($meet, $gazettes);
                $row['gazette_cnt'] = count($meet['公報發言紀錄']);
                $row['gazette_records'] = [];
                foreach ($meet['公報發言紀錄'] as $gazette_record) {
                    $record = [];
                    $record['gazette_id'] = $gazette_record['gazette_id'];
                    $record['page_start'] = $gazette_record['page_start'];
                    $record['speaker_cnt'] = count($gazette_record['speakers']);
                    $row['gazette_records'][] = $record;
                }
            }
            //$row['ivodCount'] = self::requestIvodCount($meet);
            //$row['written_i12n_count'] = count(self::requestWrittenI12n($meet));
            $rows[] = $row;
        }
        array_unshift($sessionPeriods, 'all');
        return view('meet.list', [
            'terms' => $terms,
            'sessionPeriods' => $sessionPeriods,
            'rows' => $rows,
            'parameters' => [
                'term' => $term,
                'sessionPeriod' => $sessionPeriod,
            ],
        ]);
    }

    private function requestTermStat()
    {
        $url = 'https://ly.govapi.tw/stat';
        $res = Http::get($url);
        $termStat = [];
        if ($res->successful()) {
            $termStat = $res->json()['meet']['terms'];
        }
        return $termStat;
    }

    private function getTermOptions($termStat)
    {
        $terms = array_map(function($termData) {
            return $termData['term'];
        }, $termStat);
        return $terms;
    }

    private function getSessionPeriods($termStat, $term)
    {
        $termData = array_filter($termStat, function($termData) use ($term) {
            return $termData['term'] == $term;
        });
        $termData = reset($termData);
        $sessionPeriods =  array_map(function($sessionPeriodData) {
            return $sessionPeriodData['sessionPeriod'];
        },$termData['sessionPeriod_count']);
        return $sessionPeriods;
    }


    private function requestMeets($term, $session_period) 
    {
        if ($session_period == 'all') {
            $session_period = '';
        }
        $url_base = "https://ly.govapi.tw/meet/$term/$session_period";
        $res = Http::get($url_base);
        if (! $res->successful()) {
            return [];
        }
        $res_json = $res->json();
        $meets = $res_json['meets'];
        $pages = $res_json['total_page'];
        if ($pages < 2) {
            return $meets;
        }
        for ($i = 2; $i <= $pages; $i++) {
            $res = Http::get($url_base . "?page=$i");
            if ($res->successful()) {
                $meets = array_merge($meets, $res->json()['meets']);
            }
        }
        return $meets;
    }

    private function requestAllGzaettes()
    {
        $url = 'https://ly.govapi.tw/gazette';
        $res = Http::get($url);
        if (! $res->successful()) {
            return [];
        }
        $res_json = $res->json();
        $gazettes = $res_json['gazettes'];
        $pages = $res_json['total_page'];
        if ($pages < 2) {
            return $gazettes;
        }
        for ($i = 2; $i <= $pages; $i++) {
            $res = Http::get($url . "?page=$i");
            if ($res->successful()) {
                $gazettes = array_merge($gazettes, $res->json()['gazettes']);
            }
        }
        return $gazettes;
    }

    private function getSpeeches($meet)
    {
        if (! array_key_exists('發言紀錄', $meet) || count($meet['發言紀錄']) == 0) {
            return [];
        }
        $speeches = [];
        foreach ($meet['發言紀錄'] as $speech) {
            $smeetingDate = $speech['smeetingDate'];
            $legislator_count = count($speech['legislatorNameList']);
            $speeches[$smeetingDate] = $legislator_count;
        }
        return $speeches;
    }

    private function getGazettePublishDate($meet, $gazettes)
    {
        $gazette_id = $meet['公報發言紀錄'][0]['gazette_id'];
        $gazette = array_filter($gazettes, function($gazettes) use ($gazette_id) {
            return $gazettes['gazette_id'] == $gazette_id;
        });
        $gazette = reset($gazette);
        return $gazette['published_at'];
    }

    private function requestIvodCount($meet)
    {
        $meet_id = $meet['meet_id'];
        $dates = $meet['dates'];
        $url = "http://ly.govapi.tw/meet/$meet_id/ivod";
        $res = Http::get($url);
        if (! $res->successful()) {
            return [];
        }
        $res_json = $res->json();
        $ivods = $res_json['ivods'];
        $ivodCount = [];
        foreach ($ivods as $ivod) {
            if (array_key_exists($ivod['date'], $ivodCount)) {
                $ivodCount[$ivod['date']]++;
            } else {
                $ivodCount[$ivod['date']] = 1;
            }
        }
        return $ivodCount;
    }

    private function requestWrittenI12n($meet)
    {
        $meet_id = $meet['meet_id'];
        $url = "http://ly.govapi.tw/meet/$meet_id/interpellation";
        $res = Http::get($url);
        if (! $res->successful()) {
            return [];
        }
        $res_json = $res->json();
        $i12ns = $res_json['interpellations'];
        return $i12ns;
    }

    private function getComtName($comt_id)
    {
        if (array_key_exists($comt_id, self::$comt_map)) {
            return self::$comt_map[$comt_id];
        }
        return null;
    }
}
