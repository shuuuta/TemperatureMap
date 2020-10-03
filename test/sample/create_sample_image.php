<?php

$image = imagecreate(600,500);

imagecolorallocate($image, 255, 255,255);

$colors = [];
for ($i = 0;$i < 20;$i++):
  $step = $i * 10;
  $colors[] = imagecolorallocate($image, 255 - $step, 55, 55 + $step );
endfor;


$step = 50;
for ($i = 0;$i < 11;$i++):
  for ($j = 0;$j < 10;$j++):
    $x = $i * $step;
    $y = $j * $step;
    $color = $colors[$i + $j];
    imagefilledrectangle($image, $x, $y, $x + $step - 1, $y + $step - 1, $color);
  endfor;
endfor;


$step = 25;
for ($i = 0;$i < 20;$i++):
  $y = $i * $step;
  $color = $colors[$i];
  imagefilledrectangle($image, 575, $y, 600, $y + $step - 1, $color);
endfor;

$filePath = __DIR__ . '/sample_image.png';
imagepng($image, $filePath);

echo 'Create sample image at ' . $filePath . '.' . PHP_EOL;
exit(0);
