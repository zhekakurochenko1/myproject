<?php
// module/Album/src/Album/Model/AlbumTable.php:
namespace Cars\Model;

use Zend\Db\TableGateway\TableGateway;

class CarsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getCars($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCars(Cars $cars)
    {
        $data = array(
            'artist' => $cars->artist,
            'title'  => $cars->title,
        );

        $id = (int)$cars->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCars($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCars($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}