<?php

namespace FFMpeg\Form;

use Zend\Form\Form;


class AddStreamOutForm extends Form
{
    private $vcodecs;
    private $acodecs;
    private $scodecs;

    public function __construct($name = null)
    {
        parent::__construct('ffmpeg');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'streamID',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'dst',
            'type' => 'text',
            'options' => [
                'label' => 'Destination URL',
              
            ],
        ]);

        $this->add([
            'name' => 'vcodec',
            'id'    => 'vcodec',
            'type' => 'select',
            'options' => [
                'label' => 'Video Codec',
            ],
        ]);

        $this->add([
            'name' => 'acodec',
            'type' => 'select',
            'options' => [
                'label' => 'Audio Codec',
            ],
        ]);


        $this->add([
            'name' => 'scodec',
            'type' => 'select',
            'options' => [
                'label' => 'Subtitle Codec',
            ],
        ]);

        $this->add([
            'name' => 'vbitrate',
            'type' => 'text',
            'options' => [
                'label' => 'Video bitrate/k',
            ],
        ]);

        $this->add([
            'name' => 'abitrate',
            'type' => 'text',
            'options' => [
                'label' => 'Audio bitrate/k',
            ],
        ]);

        $this->add([
            'name' => 'minrate',
            'type' => 'text',
            'options' => [
                'label' => 'Minimal bitrate/k',
            ],
        ]);

        $this->add([
            'name' => 'maxrate',
            'type' => 'text',
            'options' => [
                'label' => 'Maximum birtate/k',
            ],
        ]);


        $this->add([
            'name' => 'muxrate',
            'type' => 'text',
            'options' => [
                'label' => 'Muxrate',
            ],
        ]);

        $this->add([
            'name' => 'format',
            'type' => 'text',
            'options' => [
                'label' => 'Output format (mpegts)',
            ],
        ]);

        $this->add([
            'name' => 'scale',
            'type' => 'text',
            'options' => [
                'label' => 'Rescale',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            [
                'attributes'    => [
                    'value'     => "Save",
                    'id'        => 'submitbutton',
                ],
            ],
        ]);
    }

    /**
     * Get the value of vcodecs
     */ 
    public function getVcodecs()
    {
        return $this->vcodecs;
    }

    /**
     * Set the value of vcodecs
     *
     * @return  self
     */ 
    public function setVcodecs($vcodecs)
    {
        $this->vcodecs = $vcodecs;
        $this->get('vcodec')->setValueOptions($this->vcodecs);

    }

    /**
     * Get the value of acodecs
     */ 
    public function getAcodecs()
    {
        return $this->acodecs;
    }

    /**
     * Set the value of acodecs
     *
     * @return  self
     */ 
    public function setAcodecs($acodecs)
    {
        $this->acodecs = $acodecs;
        $this->get('acodec')->setValueOptions($this->acodecs);

        return $this;
    }

    /**
     * Get the value of scodecs
     */ 
    public function getScodecs()
    {
        return $this->scodecs;
    }

    /**
     * Set the value of scodecs
     *
     * @return  self
     */ 
    public function setScodecs($scodecs)
    {
        $this->scodecs = $scodecs;
        $this->get('scodec')->setValueOptions($this->scodecs);

        return $this;
    }
}


