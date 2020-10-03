<?php
require_once __DIR__ . '/..//vendor/autoload.php';

use TemperatureMap\TemperatureMap;

$filePath= __DIR__ . '/images/sample_image.png';


$temperatureMap = new TemperatureMap($filePath);


$temperatureMap->setTemperatureArea(580, 0, 500);

$temperatureMap->setTemperatureRange(-20, 100);

$imgW = 550;
$imgH = 500;
$x = mt_rand(0, $imgW - 1);
$y = mt_rand(0, $imgH - 1);
$w = mt_rand(1, $imgW - $x);
$h = mt_rand(1, $imgH - $y);

$colorIndex = $temperatureMap->getAreaColorIndex($x, $y, $w, $h);

echo "Temperature in the range of {$w}x{$h} from the coordinates ({$x}, {$y}) is" . PHP_EOL;
echo $temperatureMap->getTemperatureFromIndex($colorIndex) . '℃';
echo PHP_EOL;

exit(0);

