<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\LyAPI;

class LegislatorController extends Controller
{
    public function legislators()
    {
        $termStat = self::requestTermStat();
        $terms = self::getTermOptions($termStat);
        $term = (request()->query('term')) ?? $terms[0];
        $sessionPeriods = self::getSessionPeriods($termStat, $term);
        $sessionPeriod = (request()->query('sessionPeriod')) ?? 'all';
        $legislators = self::requestLegislators($term);

        //從第4屆開始才有『所屬委員會』資料
        if ($term >= 4 && $sessionPeriod != 'all') {
            foreach ($legislators as &$legislator) {
                $target = '第' . $term . '屆第' . $sessionPeriod . '會期';
                $committees = array_filter($legislator['committee'], function($comt) use ($target) {
                    return mb_strpos($comt, $target) !== false;
                });
                $legislator['committee'] = $committees;
            }
        }

        array_unshift($sessionPeriods, 'all');
        return view('legislator.list', [
            'nav' => 'legislators',
            'terms' => $terms,
            'sessionPeriods' => $sessionPeriods,
            'legislators' => $legislators,
            'params' => [
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
            $termStat = $res->json()['bill']['terms'];
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

    private function requestLegislators($term)
    {
        $url = "https://ly.govapi.tw/legislator/$term";
        $data = LyAPI::paginationRequest($url, 'legislators');
        return $data;
    }
}
