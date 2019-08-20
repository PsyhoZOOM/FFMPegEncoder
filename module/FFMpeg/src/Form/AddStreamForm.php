<?php
namespace FFMpeg\Form;

use Zend\Form\Form;

class AddStreamForm extends Form{
    public function __construct($name = null)
    {
     parent::__construct('ffmpeg');

     $this->add([
         'name' => 'id',
         'type' => 'hidden',
     ]);

     $this->add([
         'name' => 'name',
         'type' => 'text',
         'options' => [
             'label' => 'Stream name',
         ],
     ]);

     $this->add([
         'name' => 'src',
         'type' => 'text',
         'options' => [
             'label' => 'Source',
         ],
     ]);

     $this->add([
         'name' => 'desc',
         'type' => 'text',
         'options' => [
             'label' => 'Description',
         ],
     ]);

     $this->add([
         'name' => 'submit',
         'type' => 'submit',
         [
             'attributes'   => [
                 'value'    => 'Save',
                 'id'       => 'submitbutton',
             ],
            ],
     ]);

    }
}