<?php
namespace FFMpeg\Controller;

use FFMpeg\Model\StreamTable;
use FFMpeg\Lib\FFMpeg\streamFFMpeg;
use FFMpeg\Lib\FFMpeg\FFMpegFunctions;
use FFMpeg\Model\StreamOutTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EncoderController extends AbstractActionController{
    private $table;
    private $tableOut;
    public function __construct(StreamTable $table, StreamOutTable $tableOut)
    {
        $this->table = $table;
        $this->tableOut = $tableOut;
    }

    public function  indexAction(){
        echo "INDEX";
    }

    public function startEncodingAction(){
        echo "ENCODERS";

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
}