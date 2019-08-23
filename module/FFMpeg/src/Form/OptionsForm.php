<?php

namespace FFMpeg\Form;

use Zend\Form\Form;

class OptionsForm extends Form{
    public function __construct($name = null)
    {
     parent::__construnct('ffmpeg');

     $this->add([
         'name' => 'id',
         'type' => 'hidden',
     ]);

     $this->add([
         'name' => 'ffmpegPath',
         'type' => 'text',
         'options' => [
             'label' => 'Path to the ffmpeg, ffprobe'
         ]
     ]);

     $this->add([
         'name' => 'remote',
         'type' => 'checkbox',
         'options' => [
             'label' => 'Run locally or on remote java server',
         ],
     ]);

     $this->add([
         'name' => 'remoteAddress',
         'type' => 'text',
         'options' => [
             'label' => 'Remote address',
         ]
     ]);

     $this->add([
         'name' => 'remotePort',
         'type' => 'text',
         'options' => [
             'label' => 'Remote port number',
         ],
     ]);

     $this->add([
         'name' => 'remoteUser',
         'type' => 'text',
         'options' => [
             'label' => 'Username for remote host',
         ],
     ]);

     $this->add([
         'name' => 'remotePass',
         'type' => 'text',
         'options' => [
             'label' => 'Password for remote host'
         ]
     ]);
    }
}