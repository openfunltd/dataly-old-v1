<?php

namespace App\Utils;

class Helper
{
    // https://zh.wikipedia.org/zh-tw/Template:中華民國政黨色彩
    public static $party_colors = [
        '民主進步黨' => '#1B9431',
        '國民黨' => '#000095',
        '台灣民眾黨' => '#28C8C8',
        '無黨籍' => '#999999',
    ];

    public static function getTerm($date)
    {
        if ($date < '1993-02-01') {
            throw new \Exception("getTerm() not support date before 1993-02-01(第二任期以前)");
        }
        $day_range = ['-02-01', '-01-31'];
        //第 2 ~ 6 屆任期為 3 年
        if ('1993-02-01' <= $date and $date <= '2008-01-31') {
            $year = 1993;
            $term = 2;
            while ($term < 7) {
                $start_date = ($year + 3 * ($term - 2)) . $day_range[0];
                $end_date = ($year + 3 * ($term - 1)) . $day_range[1];
                if ($start_date <= $date and $date <= $end_date) {
                    break;
                }
                $term++;
            }
            return $term;
        }
        //第 7 屆開始，任期為 4 年
        $year = 2008;
        $term = 7;
        //暫且支援到第 100 屆
        while ($term < 101) {
            $start_date = ($year + 4 * ($term - 7)) . $day_range[0];
            $end_date = ($year + 4 * ($term - 6)) . $day_range[1];
            if ($start_date <= $date and $date <= $end_date) {
                break;
            }
            $term++;
        }
        if ($term > 100) {
            throw new \Exception("getTerm() not support date after 100-th term (input: $date)");
        }
        return $term;
    }
}
