<?php
namespace TemperatureMap;

use RuntimeException;

class TemperatureMap
{
  protected $image;

  protected int $temperatureAreaX;
  protected int $temperatureAreaY;
  protected int $temperatureAreaL;

  protected int $temperatureMin;
  protected int $temperatureMax;

  public function __construct(string $path)
  {
    $this->setImage($path);
  }

  protected function setImage(string $path): bool
  {
    $image = @imagecreatefrompng($path);
    if (!$image || !is_resource($image)) {
      throw new RuntimeException('Map image can not be  acquired.');
    }

    //need check image
    $this->image = $image;

    return true;
  }

  public function getColorIndex(int $x, int $y) :int
  {
    $colorIndex = imagecolorat($this->image, $x, $y);

    return $colorIndex;
  }

  public function getColor(int $x, int $y) :array
  {
    $colorIndex = imagecolorat($this->image, $x, $y);

    $colors = imagecolorsforindex($this->image, $colorIndex);

    return $colors;
  }

  public function getAreaColorIndex(int $x, int $y, int $w, int $h) :int
  {
    $colorList =[];

    for ($i = 0;$i < $w;$i++):
      for ($j = 0;$j < $h;$j++):
        $colorList[] = imagecolorat($this->image, $x + $i, $y + $j);
      endfor;
    endfor;

    $countValues = array_count_values($colorList);
    $countValuesMax = max($countValues);

    $countValues = array_flip($countValues);

    return $countValues[$countValuesMax];
  }

  public function getAreaColor(int $x, int $y, int $w, int $h) :array
  {
    $colorIndex = $this->getAreaColorIndex($x, $y, $w, $h);

    return imagecolorsforindex($this->image, $colorIndex);
  }

  public function setTemperatureArea(int $x, int $y, int $length) :void
  {
    $this->temperatureAreaX = $x;
    $this->temperatureAreaY = $y;
    $this->temperatureAreaL = $length;
  }

  public function getTemperaturePoint(int $colorIndex) :float
  {
    for ($i = 0;$i < $this->temperatureAreaL;$i++):
      $testColor = imagecolorat($this->image, $this->temperatureAreaX, $this->temperatureAreaY + $this->temperatureAreaL - $i - 1);
      if ($testColor === $colorIndex) {
        $hundredHoldPoint = intdiv($i * 10000, $this->temperatureAreaL);
        return $hundredHoldPoint / 100;
      }
    endfor;

    return -1;
  }

  public function setTemperatureRange(int $min, int $max) :void
  {
    $this->temperatureMin = $min;
    $this->temperatureMax = $max;
  }

  public function getTemperatureFromIndex(int $colorIndex) :float
  {
    $percent = $this->getTemperaturePoint($colorIndex);
    $range = $this->temperatureMax - $this->temperatureMin;
    $point = $range * $percent;

    return  (($this->temperatureMin * 100) + $point) / 100;
  }
}
