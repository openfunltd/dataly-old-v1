<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class LyAPI
{
    public static function paginationRequest($url, $target)
    {
        $res = Http::get($url);
        if (! $res->successful()) {
            return [];
        }
        $res_json = $res->json();
        $data = $res_json[$target];
        $pages = $res_json['total_page'];
        if ($pages < 2) {
            return $data;
        }
        for ($i = 2; $i <= $pages; $i++) {
            $res = Http::get($url . "?page=$i");
            if ($res->successful()) {
                $data = array_merge($data, $res->json()[$target]);
            }
        }
        return $data;
    }
}
