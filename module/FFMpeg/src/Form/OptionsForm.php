<?php

namespace FFMpeg\Form;

use Zend\Form\Form;

class OptionsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('ffmpeg');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'ffmpegPath',
            'type' => 'text',
            'required' => 'true',
            'options' => [
                'label' => 'Path to the ffmpeg, ffprobe',
            ],
        ]);

        $this->add([
            'name' => 'remote',
            'options' => [
                'label' => 'Run on a remote JAVA Server',
                'checked_value' => 'true',
                'unchecked_value' => 'false',
            ],
            'attributes' => [
                'value' => 'true',
            ],
            'type' => 'checkbox',
        ]);

        $this->add([
            'name' => 'remoteAddress',
            'type' => 'text',
            'options' => [
                'label' => 'Remote address',
            ],
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
                'label' => 'Password for remote host',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            [
                'attributes' => [
                    'value' => "Save",
                    'id' => 'submitbutton',
                ],
            ],
        ]);
    }
}