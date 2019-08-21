<?php

namespace FFMpeg\Lib\FFMpeg;

use FFMpeg\Lib\FFMpeg\StreamStatus;

/**
 * Video, audio, subtitle codecs, encoding controls
 * etc..
 */
class FFMpegFunctions
{
    /**
     * Get system supported codecs
     *
     * @return void
     */
    private function getSupportedCodecs()
    {
        $venc = shell_exec('ffmpeg -codecs');
        return preg_split("#[\r\n]+#", $venc);
    }
    /**
     * Start encoding of stream
     *
     * @param [type] $id id of stream
     * @return void
     */
    public function startEncoding($id)
    { }

    /**
     * Stop encoding of stream
     *
     * @param [type] $id id of stream
     * @return void
     */
    public function stopEncoding($id)
    { }

    /**
     * Stop all encodings
     *
     * @return void
     */
    public function stopAllEncodings()
    { }

    /**
     * Get status of all streaming encodings.id, name, status(true, false), kb. If status is false kb is zero(0);
     *
     * @return array  of FFMPeg\Lib\FFMPeg\StreamStatus::class object
     */
    public function getAllEncodingStatus()
    {
        $stream = new StreamStatus();
        $statusALL = array($stream);
        return $statusALL;
    }

    /**
     * Get status of streamID. Returning object FFMPeg\Lib\FFMPeg\StreamStatus::class
     *
     * @param [type] $id
     * @return StreamStatus();
     */
    public function getEncodingStatusOfStreamID($id)
    {
        $stream = new StreamStatus();
        return $stream;
    }

    /**
     * Get supported Video encoders
     *
     * @return array of video encoders
     */
    public function getVideoEncoders($default = "copy")
    {
        //Function have same code for video, audio and subtitle codecs
        //FFMPeg encoder looks like this:
        //H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10 (decoders: h264 h264_v4l2m2m h264_cuvid ) (encoders: libx264 libx264rgb h264_nvenc h264_omx h264_v4l2m2m h264_vaapi nvenc nvenc_h264 )
        //We need to extract only name of encoder and their corresponding encoders (sub encoders)
        //set default to copy (no encoding needed)
        $vencArray = array($default => $default);
        $vencArray['copy'] = 'copy';
        $venc = $this->getSupportedCodecs();

        foreach ($venc as $line) :
            $line = trim($line);
            //EV stands for Encoding Video. Change EA for Encoding Audio
            if (strpos($line, 'EV')) { //if line have Encoding Video
                $splitted = explode(" ", $line); //remove empty space from array
                $i = 0; //$i = index of $splitted line for removing from array $splitted
                foreach ($splitted as $emptyItem) : //array 0=codec params (encoder, decoder..) , 1=name, ... = description and sub-encoders
                    if (empty($emptyItem)) {
                        unset($splitted[$i]);
                    }
                    $i++;
                endforeach;

                //reindex array
                $splitted_reindexed = array_values($splitted);

                //add encoder to vencArray
                //               $apush[$splitted_reindexed[1]] = $splitted_reindexed[1];
                $vencArray[$splitted_reindexed[1]] = $splitted_reindexed[1];
                //             array_push($vencArray, $apush);

                //get sub encoders ex: h264 (h264_nvenc, h264_cuvid.. )
                $sr = 0; // index of $splitted_reindexed
                foreach ($splitted_reindexed as $encItem) {
                    if ($encItem === "(encoders:") {
                        //remove unnecessary words from array
                        $spliced = array_splice($splitted_reindexed, $sr);
                        $i = 1; //$i = index of splitted encoder description. Skip index 0 because it is not encoder name ;
                        while ($spliced[$i] != ')') {
                            //add to encoder array
                            $vencArray[$spliced[$i]] = $spliced[$i];
                            $i++;
                        }
                    }
                    $sr++;
                }
            }
        endforeach;

        //remove double and return
        return array_unique($vencArray);
    }

    /**
     * Get supported Audio encoders
     *
     * @return array of supported Audio encoders
     */
    public function getAudioEncoders($default = "copy")
    {
        //Function have same code for video, audio and subtitle codecs
        //FFMPeg encoder looks like this:
        //H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10 (decoders: h264 h264_v4l2m2m h264_cuvid ) (encoders: libx264 libx264rgb h264_nvenc h264_omx h264_v4l2m2m h264_vaapi nvenc nvenc_h264 )
        //We need to extract only name of encoder and their corresponding encoders (sub encoders)
        //set default to copy (no encoding needed)
        $aencArray = array($default => $default);
        $aencArray['copy'] = 'copy';
        $venc = $this->getSupportedCodecs();

        foreach ($venc as $line) :
            $line = trim($line);
            //EV stands for Encoding Video. Change EA for Encoding Audio
            if (strpos($line, 'EA')) { //if line have Encoding Audio
                $splitted = explode(" ", $line); //remove empty space from array
                $i = 0; //$i = index of $splitted line for removing from array $splitted
                foreach ($splitted as $emptyItem) : //array 0=codec params (encoder, decoder..) , 1=name, ... = description and sub-encoders
                    if (empty($emptyItem)) {
                        unset($splitted[$i]);
                    }
                    $i++;
                endforeach;

                //reindex array
                $splitted_reindexed = array_values($splitted);

                //add encoder to aencArray
                $aencArray[$splitted_reindexed[1]] = $splitted_reindexed[1];

                //get sub encoders ex: h264 (h264_nvenc, h264_cuvid.. )
                $sr = 0; // index of $splitted_reindexed
                foreach ($splitted_reindexed as $encItem) {
                    if ($encItem === "(encoders:") {
                        //remove unnecessary words from array
                        $spliced = array_splice($splitted_reindexed, $sr);
                        $i = 1; //$i = index of splitted encoder description. Skip index 0 because it is not encoder name ;
                        while ($spliced[$i] != ')') {
                            //add to encoder array
                            $aencArray[$spliced[$i]] = $spliced[$i];
                            $i++;
                        }
                    }
                    $sr++;
                }
            }
        endforeach;

        //remove double items and return
        return array_unique($aencArray);
    }

    /**
     * Get supported Subtitle encoders
     *
     * @return array of supported Subtitle encoders
     */
    public function getSubtitleEncoders($default = "")
    {
        //Function have same code for video, audio and subtitle codecs
        //FFMPeg encoder looks like this:
        //H.264 / AVC / MPEG-4 AVC / MPEG-4 part 10 (decoders: h264 h264_v4l2m2m h264_cuvid ) (encoders: libx264 libx264rgb h264_nvenc h264_omx h264_v4l2m2m h264_vaapi nvenc nvenc_h264 )
        //We need to extract only name of encoder and their corresponding encoders (sub encoders)
        //set default to copy (no encoding needed)
        $sencArray = array($default => $default);
        $sencArray['copy'] = 'copy';
        $venc = $this->getSupportedCodecs();

        foreach ($venc as $line) :
            $line = trim($line);
            //EV stands for Encoding Video. Change EA for Encoding Audio
            if (strpos($line, 'ES')) { //if line have Encoding Subtitle
                $splitted = explode(" ", $line); //remove empty space from array
                $i = 0; //$i = index of $splitted line for removing from array $splitted
                foreach ($splitted as $emptyItem) : //array 0=codec params (encoder, decoder..) , 1=name, ... = description and sub-encoders
                    if (empty($emptyItem)) {
                        unset($splitted[$i]);
                    }
                    $i++;
                endforeach;

                //reindex array
                $splitted_reindexed = array_values($splitted);

                //add encoder to sencArray
                $sencArray[$splitted_reindexed[1]] = $splitted_reindexed[1];

                //get sub encoders ex: h264 (h264_nvenc, h264_cuvid.. )
                $sr = 0; // index of $splitted_reindexed
                foreach ($splitted_reindexed as $encItem) {
                    if ($encItem === "(encoders:") {
                        //remove unnecessary words from array
                        $spliced = array_splice($splitted_reindexed, $sr);
                        $i = 1; //$i = index of splitted encoder description. Skip index 0 because it is not encoder name ;
                        while ($spliced[$i] != ')') {
                            //add to encoder array
                            $sencArray[$spliced[$i]] = $spliced[$i];
                            $i++;
                        }
                    }
                    $sr++;
                }
            }
        endforeach;

        //remove double and return
        return array_unique($sencArray);
    }

    /**
     * Get supported Output formats
     *
     * @return array of Output formats
     */
    public function getOutputFormats()
    {
        $outFormats = array('mpegts');
        return $outFormats;
    }
}
