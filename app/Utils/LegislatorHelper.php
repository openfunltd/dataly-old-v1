<?php

namespace App\Utils;

use App\Utils\LyAPI;

class LegislatorHelper
{
    public static $party_icon_url = [
        '民主進步黨' => 'img/party_icon/dpp.png',
        '中國國民黨' => 'img/party_icon/kmt.png',
        '台灣民眾黨' => 'img/party_icon/tpp.png',
        '無黨籍' => 'img/party_icon/none.png',
    ];

    public static function requestLegislatorPartyMap($term)
    {
        $res = LyAPI::apiQuery('/legislator/11?limit=300', '查詢立法委員所屬政黨');
        $legislators = $res->legislators;
        $legislator_party_map = [];
        foreach ($legislators as $legislator) {
            $name = str_replace(" ", "", $legislator->name);
            $legislator_party_map[$name] = $legislator->party;
        }
        return $legislator_party_map;
    }
}
