<?php

$path = 'C:/Users/raulm/.gemini/antigravity-ide/brain/d88c4d35-82b3-4e76-a9f3-9679b58143dc/.user_uploaded/media_1792937579304.png';
$img = imagecreatefrompng($path);
$w = imagesx($img);
$h = imagesy($img);
echo "Dimensions: {$w}x{$h}\n";

// Let's sample pixels between 300 and 600 horizontally across row 280 (which is right under headers)
$colors = [];
for ($x = 200; $x < 800; $x++) {
    $rgb = imagecolorat($img, $x, 280);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    // Look for border gray colors (neither white 255,255,255 nor dark text)
    if ($r > 200 && $r < 245 && abs($r-$g) < 5 && abs($g-$b) < 5) {
        $hex = sprintf("#%02X%02X%02X", $r, $g, $b);
        $colors[$hex] = ($colors[$hex] ?? 0) + 1;
    }
}
print_r($colors);
