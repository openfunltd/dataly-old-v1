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

    public static function batchRequest($base_url, $ids, $param_prefix)
    {
        //一次查詢多筆時使用
        $rough_estimate = (2048 - strlen($base_url)) / strlen('&' . $param_prefix . urlencode($ids[0]));
        $batch_cnt = $rough_estimate - 10;
        $params = array_map(function ($id) use ($param_prefix) {
            return $param_prefix . urlencode($id);
        }, $ids);
        $data = [];
        for ($i=0; $i < count($params); $i += $batch_cnt) {
            $batch = array_slice($params, $i, $batch_cnt);
            $url = sprintf($base_url, implode('&', $batch));
            $res = Http::get($url);
            if ($res->successful()) {
                $batch_data = $res->json()['aggs'];
                $batch_data = $batch_data[array_keys($batch_data)[0]];
                $data = array_merge($data, $batch_data);
            }
        }
        return $data;
    }
}
