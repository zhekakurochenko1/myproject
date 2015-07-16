<?php 
namespace DBwork\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DBwork\Model\DBwork;
use DBwork\Form\DBworkForm;

class DBworkController extends AbstractActionController
{
	protected $dbworkTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'dbwork' => $this->getDBworkTable()->fetchAll(),
        ));
    }
	public function addAction()
    {
        $form = new DBworkForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $db_work = new DBwork();
            $form->setInputFilter($db_work->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $db_work->exchangeArray($form->getData());
                $this->getDBworkTable()->saveDBwork($dbwork);

                // Redirect to list of albums
                return $this->redirect()->toRoute('dbwork');
            }
        }
		return array('form' => $form);
	
    }
	
	public function editAction() 
	
    {
        id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('dbwork', array(
                'action' => 'add'
            ));
        }
        $dbwork = $this->getAlbumTable()->getAlbum($id);

        $form  = new DBworkForm();
        $form->bind($dbwork);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($dbwork->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAlbumTable()->saveAlbum($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('dbwork');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('dbwork');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getDBworkTable()->deleteDBwork($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('dbwork');
        }

        return array(
            'id'    => $id,
            'dbwork' => $this->getDBworkTable()->getDBwork($id)
        );
    }

    public function getDBworkTable()
    {
        if (!$this->dbworkTable) {
            $sm = $this->getServiceLocator();
            $this->dbworkTable = $sm->get('DBwork\Model\DBworkTable');
        }
        return $this->dbworkTable;
    }
	
		
}

?>