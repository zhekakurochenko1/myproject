<?php
// module/Album/test/AlbumTest/Model/AlbumTableTest.php:
namespace DBwork\Model;

use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class AlbumTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllAlbums()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $dbworkTable = new DBworkTable($mockTableGateway);

        $this->assertSame($resultSet, $dbworkTable->fetchAll());
    }
    public function testCanRetrieveAnDBworkByItsId()
    {
        $dbwork = new DBwork();
        $dbwork->exchangeArray(array('id'     => 123,
                                    'names' => 'civic',
                                    'images'  => 'img=civic'));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new DBwork());
        $resultSet->initialize(array($dbwork));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $dbworkTable = new DBworkTable($mockTableGateway);

        $this->assertSame($dbwork, $dbworkTable->getDBwork(123));
    }

    public function testCanDeleteAnDBworkByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('delete')
                         ->with(array('id' => 123));

        $dbworkTable = new DBworkTable($mockTableGateway);
        $dbworkTable->deleteDBwork(123);
    }

    public function testSaveDBworkWillInsertNewDBworkIfTheyDontAlreadyHaveAnId()
    {
        $dbworkData = array('names' => 'civic', 'imeges' => 'img=civic');
        $dbwork     = new DBwork();
        $dbwork->exchangeArray($dbworkData);

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('insert')
                         ->with($dbworkData);

        $dbworkTable = new DBworkTable($mockTableGateway);
        $dbworkTable->saveDBwork($dbwork);
    }

    public function testSaveDBworkWillUpdateExistingDBworkIfTheyAlreadyHaveAnId()
    {
        $dbworkData = array('id' => 123, 'names' => 'civic', 'images' => 'img=civic');
        $dbwork     = new DBwork();
        $dbwork->exchangeArray($dbworkData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new DBwork());
        $resultSet->initialize(array($dbwork));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));
        $mockTableGateway->expects($this->once())
                         ->method('update')
                         ->with(array('names' => 'civic', 'images' => 'img=civic'),
                                array('id' => 123));

        $dbworkTable = new DBworkTable($mockTableGateway);
        $dbworkTable->saveDBwork($dbwork);
    }

    public function testExceptionIsThrownWhenGettingNonexistentDBwork()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new DBwork());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $dbworkTable = new DBworkTable($mockTableGateway);

        try
        {
            $dbworkTable->getDBwork(123);
        }
        catch (\Exception $e)
        {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }
}