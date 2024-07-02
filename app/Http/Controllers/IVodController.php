<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\LyAPI;
use App\Utils\IVodHelper;
use App\Utils\LegislatorHelper;

class IVodController extends Controller
{
    public function ivod($ivod_id)
    {
        $ivod = LyAPI::apiQuery("/ivod/{$ivod_id}?with_transcript=1&with_gazette=1", "查詢影音 {$ivod_id}");
        $no_transcript = is_null($ivod->transcript);
        $no_gazette = is_null($ivod->gazette);
        if ($no_transcript && $no_gazette) {
            abort(404);
        }
        return view('ivod.detail', [
            'nav' => 'ivods',
            'ivod' => $ivod,
        ]);
    }

    public function ivods($date = null, $meet_id = null) 
    {
        $api_stat = LyAPI::apiQuery('/stat', '查詢最新影音日期');
        $ivod_stat = $api_stat->ivod;
        if (is_null($date)) {
            $date = date('Y-m-d', $ivod_stat->max_meeting_date / 1000);
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            abort(404);
        }
        $ivods = LyAPI::apiQuery("/ivod?date={$date}&limit=1000", "查詢 {$date} 影音列表");
        usort($ivods->ivods, function($a, $b) {
            return strtotime($a->start_time) <=> strtotime($b->start_time);
        });
        $term = $ivods->ivods[0]->meet->term;
        $legislators = LegislatorHelper::requestLegislators($term);
        $legislators_basic_info = LegislatorHelper::getLegislatorsBasicInfo($legislators);
        $legislator_party_map = LegislatorHelper::requestLegislatorPartyMap($term);

        $meets = [];
        foreach ($ivods->ivods as $ivod) {
            $meet_id = $ivod->meet->id ?? 'unknown-' . crc32($ivod->{'會議名稱'});
            if (!array_key_exists($meet_id, $meets)) {
                $meets[$meet_id] = new \StdClass;
                if (strpos($meet_id, 'unknown') === 0) {
                    $meets[$meet_id]->meet = new \StdClass;
                    $meets[$meet_id]->meet->id = $meet_id;
                    $meets[$meet_id]->meet->title= preg_replace('#（事由.*#', '', $ivod->{'會議名稱'});
                } else {
                    $meets[$meet_id]->meet = $ivod->meet;
                }
                $meets[$meet_id]->ivods = [];
            }
            $subjects = IVodHelper::getSubjects($ivod->{'會議名稱'});
            if (isset($subjects)) {
                $digested_subjects = IVodHelper::digestSubjects($subjects);
            }
            $related_laws = IVodHelper::getLaws((isset($subjects)) ? $subjects : [$ivod->{'會議名稱'}]);
            foreach ($related_laws as &$law) {
                $law_name = $law;
                $res = LyAPI::apiQuery("/law?q=$law_name", "查詢 law_id {$law_name}");
                $law_id = null;
                if (count($res->laws) > 0 && $res->laws[0]->name == $law_name) {
                    $law_id = $res->laws[0]->id;
                }
                if (is_null($law_id)) {
                    $law = sprintf('%s(新法)', $law_name);
                    continue;
                }
                $law = sprintf('%s(<a href="https://ly.govapi.tw/law/%s">%s</a>)', $law_name, $law_id, $law_id);
            }
            $meets[$meet_id]->meet->{'會議名稱'} = isset($subjects) ? implode("<br>", $digested_subjects) : $ivod->{'會議名稱'};
            $meets[$meet_id]->meet->{'會議時間'} = $ivod->{'會議時間'};
            $meets[$meet_id]->meet->{'關聯法律'} = implode("<br>", $related_laws);
            $ivod->{'party'} = self::getParty($ivod->委員名稱, $legislators_basic_info);
            $ivod->{'bio_id'} = self::getBioId($ivod->委員名稱, $legislators_basic_info);
            $meets[$meet_id]->ivods[] = $ivod;
        }
        return view('ivod.list', [
            'nav' => 'ivods',
            'date' => $date,
            'meets' => $meets,
            'term' => $ivod->meet->term ?? null,
            'sessionPeriod' => $ivod->meet->sessionPeriod ?? null,
            'ivod_stat' => $ivod_stat,
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
            'ivod_stat' => $ivod_stat,
        ]);
    }

    private function getParty($name, $legislators_basic_info)
    {
        $name = str_replace(" ", "", $name);
        $name = str_replace("‧", "", $name);
        if (property_exists($legislators_basic_info, $name)) {
            return $legislators_basic_info->{$name}->party;
        }
        return "";
    }

    private function getBioId($name, $legislators_basic_info) 
    {
        $name = str_replace(" ", "", $name);
        $name = str_replace("‧", "", $name);
        if (property_exists($legislators_basic_info, $name)) {
            $bio_id = $legislators_basic_info->{$name}->bio_id;
            return str_pad($bio_id, 4, '0', STR_PAD_LEFT);
        }
        return "";
    }
}
