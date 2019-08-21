<?php

namespace FFMpeg\Controller;

use FFMpeg\Lib\FFMpeg\FFMpegFunctions;
use FFMpeg\Lib\FFMpeg\streamFFMpeg;
use FFMpeg\Model\StreamOutTable;
use FFMpeg\Model\StreamTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EncoderController extends AbstractActionController
{
    private $streamIn;
    private $streamOut;
    public function __construct(StreamTable $table, StreamOutTable $tableOut)
    {
        $this->streamIn = $table;
        $this->streamOut = $tableOut;
    }

    public function indexAction()
    { }

    public function startEncodingAction()
    {


        if(!$this->getRequest()->isPost()){
            return $this->redirect()->toRoute('ffmpeg');
        }


        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('ffmpeg');
        }
        $this->createOut($id);

        $streamFF = new streamFFMpeg();

        $streamFF->setId(1);
        $streamFF->setName("name1");
        $streamFF->setDesc("desc");

        $ffmpeg = new ffmpegFunctions();
        $vcodecs = $ffmpeg->getVideoEncoders();

        $streams = [$streamFF, $streamFF];

        $view = new ViewModel([
            'stream' => $streams,
            'vcodecs' => $vcodecs,
        ]);

        return $this->redirect()->toRoute('ffmpeg');
    }

    private function createOut($id)
    {
        if ($id == null) {
            $id = 7;
        }

        $dir = __DIR__ . "/../../../../data/stream/$id";
        $cwd = getcwd();
        mkdir($dir, 0777, true, null);

        $cmdLine = "ffmpeg -i ";
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
    }
}