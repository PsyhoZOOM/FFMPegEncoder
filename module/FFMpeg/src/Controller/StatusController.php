<?php

namespace FFMpeg\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use FFMpeg\Lib\FFMpeg\Client;

class StatusController extends AbstractActionController
{
    public function sysinfoAction()
    {
        $client = new Client();
        $client->getSystemInfo();
        $info = $client->getResponse();

        $this->response->setContent($info);

        return $this->response;
    }


    /**
     * Get stream info.
     *
     * @param int $id  ID of Stream.
     * @return void //get status if needed.
     */
    public function getStreamInfo($id)
    {
        $client = new Client();
        $client->getStreamStatus($id);

        $jsonResponse = $client->getResponse();
    }

    /**
     * Get active streams
     *
     * @return void
     */
    public function getActiveStreamsAction()
    {
        $client = new Client();
        $client->getActiveStreams();
        $response = $client->getResponse();
        $this->response->setContent($response);

        return $this->response;
    }
}