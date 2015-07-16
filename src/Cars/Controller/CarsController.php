<?php
namespace Cars\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Cars\Model\Cars;
use Cars\Form\CarsForm;

class CarsController extends AbstractActionController
{
    protected $carsTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'carss' => $this->getCarsTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new CarsForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $cars = new Cars();
            $form->setInputFilter($cars->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $cars->exchangeArray($form->getData());
                $this->getCarsTable()->saveCars($cars);

                // Redirect to list of albums
                return $this->redirect()->toRoute('cars');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cars', array(
                'action' => 'add'
            ));
        }
        $cars = $this->getCarsTable()->getCars($id);

        $form  = new CarsForm();
        $form->bind($cars);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($cars->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCarsTable()->saveCars($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('cars');
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
            return $this->redirect()->toRoute('cars');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCarsTable()->deleteCars($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('cars');
        }

        return array(
            'id'    => $id,
            'cars' => $this->getCarsTable()->getCars($id)
        );
    }

    public function getCarsTable()
    {
        if (!$this->carsTable) {
            $sm = $this->getServiceLocator();
            $this->carsTable = $sm->get('Cars\Model\CarsTable');
        }
        return $this->carsTable;
    }
}