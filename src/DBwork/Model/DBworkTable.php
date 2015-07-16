<?php
// module/Album/src/Album/Model/AlbumTable.php:
namespace DBwork\Model;

use Zend\Db\TableGateway\TableGateway;

class DBworkTable
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

    public function getDBwork($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveDBwork(DBwork $dbwork)
    {
        $data = array(
            'names' => $dbwork->names,
            'images'  => $dbwork->images,
        );

        $id = (int)$dbwork->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getDBwork($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteDBwork($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}