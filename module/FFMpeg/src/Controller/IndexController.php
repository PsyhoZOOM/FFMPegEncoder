<?php

namespace FFMpeg\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use FFMpeg\Model\StreamTable;
use FFMpeg\Form\AddStreamForm;
use FFMpeg\Model\StreamOutTable;
use FFMpeg\Model\Streams;

class IndexController extends AbstractActionController
{
    private $table;
    private $tableOut;

    public function __construct(StreamTable $table, StreamOutTable $tableOut)
    {
        $this->table = $table;
        $this->tableOut = $tableOut;
    }


    public function indexAction()
    {
        $tableData = $this->setNumberOfOutStream();
        $view = new ViewModel(
            [
                'tableData' => $tableData,
            ],
        );
        return $view;
    }

    public function addStreamAction()
    {
        $form = new AddStreamForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $stream = new Streams();
        $form->setInputFilter($stream->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $stream->exchangeArray($form->getData());
        $this->table->saveStream($stream);

        return $this->redirect()->toRoute('ffmpeg');
    }


    public function editStreamAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute("ffmpeg");
        }

        $request = $this->getRequest();

        try {
            $stream = $this->table->getStream($id);
        } catch (Exception $e) {
            return $this->redirect()->toRoute('ffmpeg', ['action' => 'index']);
        }

        $form = new AddStreamForm();
        $form->bind($stream);
        $form->get('submit')->setAttribute('value', 'Save changes');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($stream->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveStream($stream);

        return $this->redirect()->toRoute('ffmpeg', ['action' => 'index']);
    }


    public function deleteStreamAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('ffmpeg');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteStream($id);
            }
            return $this->redirect()->toRoute('ffmpeg');
        }

        return [
            'id' => $id,
            'stream' => $this->table->getStream($id),
        ];
    }


    /**
     * Set Number of OutStreams in database
     *
     * @return ResultSet of StreamTable
     */
    private function setNumberOfOutStream()
    {
        $tableData = $this->table->fetchAll(true);

        foreach ($tableData as $key => $data) {
            $numStreams = (int) $this->tableOut->countMainStream($data->id);
            if ($data->outstreams != $numStreams) {
                $this->table->updateOutStream($data->id, $numStreams);
            }
        }

        return $this->table->fetchAll();
    }
}