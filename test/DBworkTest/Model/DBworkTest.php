<?php
// module/Album/test/AlbumTest/Model/AlbumTest.php:
namespace DBworkTest\Model;

use DBwork\Model\DBwork;

use PHPUnit_Framework_TestCase;

class DBworkTest extends PHPUnit_Framework_TestCase
{
    public function testDBworkInitialState()
    {
        $dbwork = new DBwork();

        $this->assertNull($dbwork->names, '"names" should initially be null');
        $this->assertNull($dbwork->id, '"id" should initially be null');
        $this->assertNull($dbwork->images, '"images" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $dbwork = new DBwork();
        $data  = array('names' => 'some names',
                       'id'     => 123,
                       'images'  => 'some images');

        $dbwork->exchangeArray($data);

        $this->assertSame($data['names'], $dbwork->names, '"names" was not set correctly');
        $this->assertSame($data['id'], $dbwork->id, '"images" was not set correctly');
        $this->assertSame($data['images'], $dbwork->images, '"images" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $dbwork = new DBwork();

        $dbwork->exchangeArray(array('names' => 'some names',
                                    'id'     => 123,
                                    'images'  => 'some images'));
        $dbwork->exchangeArray(array());

        $this->assertNull($dbwork->names, '"names" should have defaulted to null');
        $this->assertNull($dbwork->id, '"images" should have defaulted to null');
        $this->assertNull($dbwork->images, '"images" should have defaulted to null');
    }
}