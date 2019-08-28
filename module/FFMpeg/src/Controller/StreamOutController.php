<?php

namespace FFMpeg\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use FFMpeg\Model\StreamOutTable;
use FFMpeg\Form\AddStreamOutForm;
use FFMpeg\Model\StreamsOut;
use FFMpeg\Lib\FFMpeg\FFMpegFunctions;

class StreamOutController extends AbstractActionController
{
    private $table;

    public function __construct(StreamOutTable $table)
    {
        $this->table = $table;
    }

    public function ShowStreamsAction()
    {
        $id = (int)  $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('ffmpeg');
        }
        $view  = new ViewModel(
            [
                'tableData' => $this->table->getStreamsOutOfMainStream($id),
            ]
        );
        $view->setVariable('streamid', $id);

        return $view;
    }

    public function addStreamOutAction()
    {

        $id =  (int) $this->params()->fromRoute('id', 0);

        $ffmpeg = new FFMpegFunctions();



        $form = new AddStreamOutForm();
        //set encoder options
        $form->setVcodecs($ffmpeg->getVideoEncoders());
        $form->setAcodecs($ffmpeg->getAudioEncoders());
        $form->setScodecs($ffmpeg->getSubtitleEncoders());

        $form->get('submit')->setValue('Add');

        $form->get('id')->setValue($id);


        $request = $this->getRequest();




        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->get('streamID')->setValue($id);


        $streamOut = new StreamsOut();
        $form->setInputFilter($streamOut->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }


        $streamOut->exchangeArray($form->getData());

        //we need to null id if we wanna to insert new row
        $streamOut->id = NULL;

        //set source id to out stream
        $streamOut->streamID = $id;

        $this->table->saveStreamOut($streamOut);

        return $this->redirect()->toRoute('ffmpeg/streamout', ['action' => 'showstreams', 'id' => $id]);
    }

    public function editStreamOutAction()
    {
        $id =  (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('ffmpeg');
        }

        try {
            $streamOut = $this->table->getStreamOut($id);
        } catch (Exception $e) {
            $this->redirect()->toRoute('ffmpeg');
        }

        $form = new AddStreamOutForm(null);
        $form->bind($streamOut);
        $form->get('submit')->setAttribute('value', 'Save changes');

        //set codecs 
        $ffmpeg = new FFMpegFunctions();
        $form->setVcodecs($ffmpeg->getVideoEncoders($form->get('vcodec')->getValue()));
        $form->setAcodecs($ffmpeg->getAudioEncoders($form->get('acodec')->getValue()));
        $form->setScodecs($ffmpeg->getSubtitleEncoders($form->get('scodec')->getValue()));

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'streamID' => $streamOut->streamID, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($streamOut->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveStreamOut($streamOut);

        return $this->redirect()->toRoute('ffmpeg/streamout', ['action' => 'showstreams', 'id' => $streamOut->streamID]);
    }

    public function deleteStreamOutAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('ffmpeg');
        }

        $streamOut = $this->table->getStreamOut($id);
        $streamID = $streamOut->streamID;

        if (!$streamID) {
            return $this->redirect()->toRoute("ffmpeg");
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteStreamOut($id);
            }
            return $this->redirect()->toRoute('ffmpeg/streamout', [
                'action' => 'showstreams',
                'id'     =>  $streamID,
            ]);
        }
        return [
            'id' => $id,
            'streamOut' => $streamOut,
            'streamid'  => $streamID,
        ];
    }
}
