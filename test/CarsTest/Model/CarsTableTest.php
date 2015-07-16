<?php
// module/Album/test/AlbumTest/Model/AlbumTableTest.php:
namespace Cars\Model;

use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class CarsTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllCars()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $carsTable = new CarsTable($mockTableGateway);

        $this->assertSame($resultSet, $carsTable->fetchAll());
    }
    public function testCanRetrieveAnCarsByItsId()
    {
        $cars = new Cars();
        $cars->exchangeArray(array('id'     => 123,
                                    'artist' => 'civic',
                                    'title'  => 'img=civic'));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Cars());
        $resultSet->initialize(array($cars));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $carsTable = new CarsTable($mockTableGateway);

        $this->assertSame($cars, $carsTable->getCars(123));
    }

    public function testCanDeleteAnCarsByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('delete')
                         ->with(array('id' => 123));

        $carsTable = new CarsTable($mockTableGateway);
        $carsTable->deleteCars(123);
    }

    public function testSaveCarsWillInsertNewCarsIfTheyDontAlreadyHaveAnId()
    {
        $carsData = array('artist' => 'civic', 'title' => 'img=civic');
        $cars     = new Cars();
        $cars->exchangeArray($carsData);

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('insert')
                         ->with($carsData);

        $carsTable = new CarsTable($mockTableGateway);
        $carsTable->saveCars($cars);
    }

    public function testSaveCarsWillUpdateExistingCarsIfTheyAlreadyHaveAnId()
    {
        $carsData = array('id' => 123, 'artist' => 'civic', 'title' => 'img=civic');
        $cars     = new Cars();
        $cars->exchangeArray($carsData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new cars());
        $resultSet->initialize(array($cars));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));
        $mockTableGateway->expects($this->once())
                         ->method('update')
                         ->with(array('artist' => 'civic', 'title' => 'img=civic'),
                                array('id' => 123));

        $carsTable = new CarsTable($mockTableGateway);
        $carsTable->saveCars($cars);
    }

    public function testExceptionIsThrownWhenGettingNonexistentCars()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Cars());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $carsTable = new CrsTable($mockTableGateway);

        try
        {
            $carsTable->getCars(123);
        }
        catch (\Exception $e)
        {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }
}