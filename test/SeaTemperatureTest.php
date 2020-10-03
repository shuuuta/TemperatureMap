<?php

namespace TemperatureMap\Test;

use PHPUnit\Framework\TestCase;
use TemperatureMap\TemperatureMap;

class TemperatureMapTest extends TestCase
{
  public function testSetImageGettingPathReturnTrue()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/sample_image.png');

    $refrectionSetImage = new \ReflectionMethod($temperatureMap, 'setImage');
    $refrectionSetImage->setAccessible(true);

    $result = $refrectionSetImage->invoke($temperatureMap, __DIR__ . '/sample/sample_image.png');
    $this->assertSame(true, $result);
  }

  public function testSetImageGettingFailurPathReturnFalse()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/sample_image.png');

    $refrectionSetImage = new \ReflectionMethod($temperatureMap, 'setImage');
    $refrectionSetImage->setAccessible(true);

    $result = $refrectionSetImage->invoke($temperatureMap, 'failurPath');

    $this->assertSame(false, $result);
  }

  public function testGetColorIndexReturnColorIndex()
  {
    $filePath = __DIR__ . '/sample/sample_image.png';
    $temperatureMap = new TemperatureMap($filePath);

    $x = 200;
    $y = 200;

    $color = $temperatureMap->getColorIndex($x, $y);

    $image = @imagecreatefrompng($filePath);
    $expected = imagecolorat($image, $x, $y);

    $this->assertSame($expected, $color);
  }

  public function testGetColorReturnColor()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/color_f33_f66_faa_3f3_6f6_afa_33f_66f_aaf.png');

    $color = $temperatureMap->getColor(150, 150);

    $expected = [
      'red' => hexdec(66),
      'green' => hexdec('ff'),
      'blue' => hexdec(66),
      'alpha' => hexdec(0),
    ];
    $this->assertSame($expected, $color);
  }

  public function testGetAreaColorIndex()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/color_f33_f66_faa_3f3_6f6_afa_33f_66f_aaf.png');

    $colorIndex = $temperatureMap->getAreaColorIndex(60, 20, 100, 100);

    $expected = [
      'red' => hexdec('ff'),
      'green' => hexdec(66),
      'blue' => hexdec(66),
      'alpha' => hexdec(0),
    ];

    $reflectionClass = new \ReflectionClass(get_class($temperatureMap));
    $property = $reflectionClass->getProperty('image');
    $property->setAccessible(true);

    $color = imagecolorsforindex($property->getValue($temperatureMap), $colorIndex);

    $this->assertSame($expected, $color);
  }

  public function testGetAreaColor()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/color_f33_f66_faa_3f3_6f6_afa_33f_66f_aaf.png');

    $color = $temperatureMap->getAreaColor(60, 20, 100, 100);

    $expected = [
      'red' => hexdec('ff'),
      'green' => hexdec(66),
      'blue' => hexdec(66),
      'alpha' => hexdec(0),
    ];
    $this->assertSame($expected, $color);
  }

  public function testSetTemperatureAreaSetProperties()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/sample_image.png');

    $values = [100, 200, 300];
    $temperatureMap->setTemperatureArea(...$values);

    $properties = [
      'temperatureAreaX',
      'temperatureAreaY',
      'temperatureAreaL',
    ];

    foreach ($values as $i => $value) :
      $reflectionClass = new \ReflectionClass(get_class($temperatureMap));
      $property = $reflectionClass->getProperty($properties[$i]);
      $property->setAccessible(true);

      $this->assertSame($value, $property->getValue($temperatureMap));
    endforeach;
  }

  public function testGetTemperaturePointReturnPercentageOfTemperatureRange()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/sample_image.png');

    $color = $temperatureMap->getAreaColorIndex(415, 225, 1, 1);

    $temperatureMap->setTemperatureArea(580, 0, 500);

    $point = $temperatureMap->getTemperaturePoint($color);

    $this->assertSame(35.0, $point);
  }

  public function testSetTemperatureRangeSetProperties()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/sample_image.png');

    $values = [-2, 31];
    $temperatureMap->setTemperatureRange(...$values);

    $properties = [
      'temperatureMin',
      'temperatureMax',
    ];

    foreach ($values as $i => $value) :
      $reflectionClass = new \ReflectionClass(get_class($temperatureMap));
      $property = $reflectionClass->getProperty($properties[$i]);
      $property->setAccessible(true);

      $this->assertSame($value, $property->getValue($temperatureMap));
    endforeach;
  }

  public function testGetTemperatureFromIndexReturnTemperature()
  {
    $temperatureMap = new TemperatureMap(__DIR__ . '/sample/sample_image.png');
    $temperatureMap->setTemperatureArea(580, 0, 500);
    $temperatureMap->setTemperatureRange(-10, 90);

    $color = $temperatureMap->getAreaColorIndex(280, 320, 1, 1);

    $this->assertSame(30.0, $temperatureMap->getTemperatureFromIndex($color));
  }
}
