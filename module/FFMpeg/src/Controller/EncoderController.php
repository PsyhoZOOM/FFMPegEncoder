<?php

namespace FFMpeg\Controller;

use FFMpeg\Lib\FFMpeg\StreamFfmpeg;
use FFMpeg\Model\StreamOutTable;
use FFMpeg\Model\StreamTable;
use FFMpeg\Model\OptionsTable;
use Zend\Mvc\Controller\AbstractActionController;

class EncoderController extends AbstractActionController
{
    private $streamIn;
    private $streamOut;
    private $optionsTable;
    public function __construct(StreamTable $table, StreamOutTable $tableOut, OptionsTable $optionsTable)
    {
        $this->streamIn = $table;
        $this->streamOut = $tableOut;
        $this->optionsTable = $optionsTable;
    }

    public function indexAction()
    { }

    public function startEncodingAction()
    {




        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('ffmpeg');
        }

        //TODO Implement another method for starting stream: 
        /* 1. Local 
        *  2. Remote by $id of main Stream
        *  3. Remote by full command line
        *
        *  This must be defined in Options!!!
        */

        $cmd = $this->createOut($id);
        $streamControl =  new StreamFfmpeg($this->optionsTable);

        //remote by $id
        $streamControl->startRemoteStream($id);

        //remote full command line
        //       $streamControl->startRemoteCMD($cmd);

        //Local
        //       $streamControl->startLocalStream($cmd);



        $this->flashMessenger()->addMessage($streamControl->getStatus());



        return $this->redirect()->toRoute('ffmpeg');
    }

    public function stopEncodingAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('ffmpeg');
        }

        $streamControl = new StreamFfmpeg();
        $streamControl->stopStream($id);

        return $this->redirect()->toRoute('ffmpeg');
    }


    private function createOut($id)
    {
        if ($id == null) {
            $id = 7;
        }

        $dir = __DIR__ . "/../../../../data/stream/$id";
        $cwd = getcwd();
        // mkdir($dir, 0777, true, null);

        $cmdLine = "ffmpeg -loglevel quiet -progress - -i ";
        $cmd = "";

        //prepare input
        $input = $this->streamIn->getStream($id);
        $output = $this->streamOut->getStreamsOutOfMainStream($id);

        foreach ($output as $key => $value) {
            $cmd = $cmd . ' -c:v ' . $value->vcodec;
            if (!empty($value->vbitrate)) {
                $cmd = $cmd . ' -b:v ' . $value->vbitrate;
            }

            $cmd = $cmd . ' -c:a ' . $value->acodec;
            if (!empty($value->abitrate)) {
                $cmd = $cmd . ' -b:a ' . $value->abitrate;
            }

            if (!empty($value->scodec)) {
                $cmd = $cmd . ' -c:s ' . $value->scodec;
            }

            if (!empty($value->scale)) {
                $cmd = $cmd . ' -vf scale=' . $value->scale;
            }

            if (!empty($value->muxrate)) {
                $cmd = $cmd . ' -muxrate ' . $value->muxrate;
            }

            if (!empty($value->minrate)) {
                $cmd = $cmd . ' -minrate ' . $value->minrate;
            }

            if (!empty($value->maxrate)) {
                $cmd = $cmd . ' -maxrate ' . $value->maxrate;
            }

            $cmd = $cmd . ' -f ' . $value->format;
            $cmd = $cmd . ' ' . $value->dst;
        }

        echo $cmd;

        $cmdLine = $cmdLine . $input->src . ' ' . $cmd;
        return $cmdLine;
    }
}