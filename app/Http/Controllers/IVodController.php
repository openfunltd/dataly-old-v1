<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\LyAPI;

class IVodController extends Controller
{
    public function ivods($date = null, $meet_id = null) 
    {
        if (is_null($date)) {
            $latest_ivod = LyAPI::apiQuery('/ivod?limit=1', '查詢最新影音日期');
            $date = $latest_ivod->ivods[0]->date;
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
}
