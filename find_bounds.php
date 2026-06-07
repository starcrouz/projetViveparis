<?php
$im = @imagecreatefromgif('images/cadreMediaOk.gif');
if (!$im) {
    die("Failed to load image cadreMediaOk.gif\n");
}
$w = imagesx($im);
$h = imagesy($im);

$start_x = 288;
$start_y = 222;

$queue = [[$start_x, $start_y]];
$visited = ["$start_x,$start_y" => true];

$min_x = $w;
$max_x = 0;
$min_y = $h;
$max_y = 0;

$count = 0;
while (count($queue) > 0) {
    $curr = array_shift($queue);
    $cx = $curr[0];
    $cy = $curr[1];
    
    $color_index = imagecolorat($im, $cx, $cy);
    $color_tran = imagecolorsforindex($im, $color_index);
    
    // In GD, alpha ranges from 0 (opaque) to 127 (transparent)
    if ($color_tran['alpha'] > 80) {
        $count++;
        if ($cx < $min_x) $min_x = $cx;
        if ($cx > $max_x) $max_x = $cx;
        if ($cy < $min_y) $min_y = $cy;
        if ($cy > $max_y) $max_y = $cy;
        
        $neighbors = [
            [$cx + 1, $cy],
            [$cx - 1, $cy],
            [$cx, $cy + 1],
            [$cx, $cy - 1]
        ];
        
        foreach ($neighbors as $n) {
            $nx = $n[0];
            $ny = $n[1];
            if ($nx >= 0 && $nx < $w && $ny >= 0 && $ny < $h) {
                $key = "$nx,$ny";
                if (!isset($visited[$key])) {
                    $visited[$key] = true;
                    $queue[] = [$nx, $ny];
                }
            }
        }
    }
}

echo "BFS finished. Visited transparent pixels: $count\n";
echo "Inner transparent bounds: X: $min_x -> $max_x, Y: $min_y -> $max_y\n";
echo "Width: " . ($max_x - $min_x + 1) . ", Height: " . ($max_y - $min_y + 1) . "\n";
?>
