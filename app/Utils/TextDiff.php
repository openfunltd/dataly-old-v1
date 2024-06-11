<?php

namespace App\Utils;

class TextDiff
{
    public static function prettyHtmls($contents)
    {
        $input = tempnam('/tmp/', 'law-diff-');
        file_put_contents($input, json_encode($contents));
        $output = tempnam('/tmp/', 'law-diff-');

        $cmd = sprintf("env NODE_PATH=%s node %s %s %s",
            escapeshellarg(base_path() . '/node_modules'),
            escapeshellarg(base_path() . '/scripts/text_diff_worker.js'),
            escapeshellarg($input),
            escapeshellarg($output)
        );

        system($cmd, $ret);

        $result = file_get_contents($output);
        $result = json_decode($result);

        unlink($input);
        unlink($output);
        return $result;
    } 
}
