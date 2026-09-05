<?php

$urls = file('urls.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$total = 0;
$ok = 0;
$r429 = 0;
$r5xx = 0;
$other = 0;
$times = [];

foreach ($urls as $url) {
    $total++;

    $start = microtime(true);

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_NOBODY => true,
    ]);

    curl_exec($ch);

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $time = microtime(true) - $start;

    curl_close($ch);

    $times[] = $time;

    if ($code === 200) {
        $ok++;
    } elseif ($code === 429) {
        $r429++;
    } elseif ($code >= 500 && $code < 600) {
        $r5xx++;
    } else {
        $other++;
    }

    printf(
        "%4d  HTTP %3d  %.3fs  %s\n",
        $total,
        $code,
        $time,
        $url
    );
}

sort($times);

$avg = array_sum($times) / count($times);
$p95 = $times[(int) floor(count($times) * 0.95)];

echo "\n========== RESULT ==========\n";
echo "Total : $total\n";
echo "200   : $ok\n";
echo "429   : $r429\n";
echo "5xx   : $r5xx\n";
echo "Other : $other\n";
echo "Avg   : " . round($avg * 1000) . " ms\n";
echo "P95   : " . round($p95 * 1000) . " ms\n";
