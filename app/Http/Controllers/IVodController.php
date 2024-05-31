<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\LyAPI;

class IVodController extends Controller
{
    public function ivod($ivod_id)
    {
        $ivod = LyAPI::apiQuery("/ivod/{$ivod_id}?with_transcript=1", "查詢影音 {$ivod_id}");
        return view('ivod.detail', [
            'nav' => 'ivods',
            'ivod' => $ivod,
        ]);
    }

    public function ivods($date = null, $meet_id = null) 
    {
        if (is_null($date)) {
            $api_stat = LyAPI::apiQuery('/stat', '查詢最新影音日期');
            $date = date('Y-m-d', $api_stat->ivod->max_meeting_date / 1000);
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            abort(404);
        }
        $ivods = LyAPI::apiQuery("/ivod?date={$date}&limit=1000", "查詢 {$date} 影音列表");
        usort($ivods->ivods, function($a, $b) {
            return strtotime($a->start_time) <=> strtotime($b->start_time);
        });

        $meets = [];
        foreach ($ivods->ivods as $ivod) {
            $meet_id = $ivod->meet->id ?? 'unknown';
            if (!array_key_exists($meet_id, $meets)) {
                $meets[$meet_id] = new \StdClass;
                if ('unknown' === $meet_id) {
                    $meets[$meet_id]->meet->id = 'unknown';
                    $meets[$meet_id]->meet->title= '未知會議';
                } else {
                    $meets[$meet_id]->meet = $ivod->meet;
                }
                $meets[$meet_id]->ivods = [];
            }
            $meets[$meet_id]->meet->{'會議名稱'} = $ivod->{'會議名稱'};
            $meets[$meet_id]->meet->{'會議時間'} = $ivod->{'會議時間'};

            $meets[$meet_id]->ivods[] = $ivod;
        }
        return view('ivod.list', [
            'nav' => 'ivods',
            'date' => $date,
            'meets' => $meets,
        ]);
    }

    public function datelist($term = null, $sessionPeriod = null)
    {
        $ivod_stat = LyAPI::apiQuery('/stat', '查詢最新影音日期')->ivod;
        if (is_null($term)) {
            $term = $ivod_stat->terms[0]->term;
            $sessionPeriod = $ivod_stat->terms[0]->sessionPeriod_count[0]->sessionPeriod;
        }

        $period_stat = null;
        foreach ($ivod_stat->terms as $term_stat) {
            if ($term_stat->term != $term) {
                continue;
            }
            foreach ($term_stat->sessionPeriod_count as $sessionPeriod_stat) {
                if ($sessionPeriod_stat->sessionPeriod != $sessionPeriod) {
                    continue;
                }
                $period_stat = $sessionPeriod_stat;
                break;
            }
        }
        if (is_null($period_stat)) {
            abort(404);
        }

        $ivods = LyAPI::apiQuery(sprintf("/ivod?date_start=%s&date_end=%s&limit=0&aggs=date",
            urlencode(date('c', $period_stat->min_meeting_date / 1000)),
            urlencode(date('c', $period_stat->max_meeting_date / 1000))),
            "查詢 {$term}-{$sessionPeriod} 影音每日統計");

        $dates = [];
        foreach ($ivods->aggs->date as $date_count) {
            $date = $date_count->value / 1000;
            $dates[] = [
                'date' => $date,
                'count' => $date_count->count,
            ];
        }
        usort($dates, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return view('ivod.datelist', [
            'nav' => 'ivods',
            'term' => $term,
            'sessionPeriod' => $sessionPeriod,
            'dates' => $dates,
        ]);
    }
}
