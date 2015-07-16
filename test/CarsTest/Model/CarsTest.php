<?php
// module/Album/test/AlbumTest/Model/AlbumTest.php:
namespace CarsTest\Model;

use Cars\Model\Cars;

use PHPUnit_Framework_TestCase;

class CarsTest extends PHPUnit_Framework_TestCase
{
    public function testCarsInitialState()
    {
        $cars = new Cars();

        $this->assertNull($cars->artist, '"artist" should initially be null');
        $this->assertNull($cars->id, '"id" should initially be null');
        $this->assertNull($cars->title, '"title" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $cars = new Cars();
        $data  = array('artist' => 'some artist',
                       'id'     => 123,
                       'title'  => 'some title');

        $cars->exchangeArray($data);

        $this->assertSame($data['artist'], $cars->artist, '"artist" was not set correctly');
        $this->assertSame($data['id'], $cars->id, '"title" was not set correctly');
        $this->assertSame($data['title'], $cars->title, '"title" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $cars = new Cars();

        $cars->exchangeArray(array('artist' => 'some artist',
                                    'id'     => 123,
                                    'title'  => 'some title'));
        $cars->exchangeArray(array());

        $this->assertNull($cars->artist, '"artist" should have defaulted to null');
        $this->assertNull($cars->id, '"title" should have defaulted to null');
        $this->assertNull($cars->title, '"title" should have defaulted to null');
    }
}