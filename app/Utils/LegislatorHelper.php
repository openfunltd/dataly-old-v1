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

    public static function requestLegislators($term)
    {
        $res = LyAPI::apiQuery('/legislator/11?limit=300', '查詢立法委員所屬政黨');
        return $res->legislators;
    }

    //TODO retire the function
    public static function requestLegislatorPartyMap($term)
    {
        $res = LyAPI::apiQuery("/legislator/$term?limit=300", '查詢立法委員所屬政黨');
        return $res->legislators;
        $legislator_party_map = [];
        foreach ($legislators as $legislator) {
            $name = str_replace(" ", "", $legislator->name);
            $name = str_replace("‧", "", $name);
            $legislator_party_map[$name] = $legislator->party;
        }
        return $legislator_party_map;
    }

    public static function getLegislatorsBasicInfo($legislators)
    {
        $legislators_basic_info = new \stdClass();
        foreach ($legislators as $legislator) {
            $name = str_replace(" ", "", $legislator->name);
            $name = str_replace("‧", "", $name);
            $party = $legislator->party;
            $bio_id = $legislator->bioId;
            $legislators_basic_info->{$name} = (object) [
                'party' => $party,
                'bio_id' => $bio_id,
            ];
        }
        return $legislators_basic_info;
    }
}
