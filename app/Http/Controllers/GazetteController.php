<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\LyAPI;

class GazetteController extends Controller
{
    public function gazettes($year = null)
    {
        $api_stat = LyAPI::apiQuery('/stat', '查詢最新公報日期');
        $gazette_stat = $api_stat->gazette;
        if (is_null($year)) {
            $year = $gazette_stat->comYears[0]->year + 1911;
        }
        $comYear = $year - 1911;

        $gazettes = LyAPI::apiQuery("/gazette?comYear={$comYear}&limit=1000", "查詢 comYears={$comYear} 的公報")->gazettes;
        return view('gazette.list', [
            'nav' => 'gazettes',
            'year' => $year,
            'gazettes' => $gazettes,
            'gazette_stat' => $gazette_stat,
        ]);
    }

}
