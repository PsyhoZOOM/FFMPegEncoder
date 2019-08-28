<?php

namespace FFMpeg\Lib\FFMpeg;



/**
 * Manipulate with streams. Starting, Stopping, get Stream status
 *  - Starting from local server.
 *  - Starting from remote server by passing full command line or id of main stream.
 */

class StreamFfmpeg
{

    private $status = false;



    /**
     * Stare remote stream by full command line
     *
     * @param String $cmd full ffmpeg command line to send
     * @return void
     */
    public function startRemoteCMD($cmd)
    {


        $arrCMD = [
            'action' => 'startStreamCMD',
            'cmd'   => $cmd
        ];

        $cmdJSON = json_encode($arrCMD, JSON_UNESCAPED_SLASHES);

        $this->send($cmdJSON);
    }

    /**
     * Start remote stream by id
     *
     * @param Integer $id of main stream
     * @return void
     */
    public function startRemoteStream($id)
    {
        $arrCMD = [
            'action' => 'startStream',
            'uniqueID' => $id
        ];

        $cmdJSON = json_encode($arrCMD, JSON_UNESCAPED_SLASHES);

        $this->send($cmdJSON);
    }

    /**
     * Start Local Stream  by command line and Main Stream ID.
     *
     * @param string $cmd <p>
     * Command line to execute.
     * </p>
     * 
     * @param int $id <p>
     * ID of Main Stream.
     * </p>
     * 
     * @return void
     */
    private function startLocalStream($cmd, $id)
    {
        //TODO Start stream local stream

    }

    public function stopStream($id)
    {
        $arrCMD = [
            'action' => 'stopStream',
            'uniqueID' => $id,
        ];

        $JSON_cmd = json_encode($arrCMD, JSON_UNESCAPED_SLASHES);
        $this->send($JSON_cmd);
    }

    /**
     * Send JSON object to socket
     *
     * @param string $JSON_cmd <p>
     * JSON formated String 
     * </p>
     * @return void
     */
    private function send($JSON_cmd)
    {
        //TODO Get parameters from database or arguments
        $address = "127.0.0.1"; //host of remote Server
        $port = 10020; // port of remote Server
        $socket = fsockopen($address, $port, $errno, $errstr, 5);
        fwrite($socket, $JSON_cmd);
        fwrite($socket, "\n");
        $this->status = fgets($socket, 1024);
        fclose($socket);
    }

    public function  getStatus()
    {
        return $this->status;
    }
}