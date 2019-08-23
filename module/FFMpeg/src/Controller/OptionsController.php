<?php

namespace FFMpeg\Controller;

use FFMpeg\Model\OptionsTable;
use Zend\Mvc\Controller\AbstractActionController;
use FFMpeg\Form\OptionsForm;

class OptionsController extends AbstractActionController
{
    private $table;

    public function __construct(OptionsTable $table)
    {
        $this->table = $table;
    }


 public function showOptionsAction(){
     $options = $this->table->fetchAll();

     $form = new OptionsForm(null);
     $form->bind($options);

     $request = $this->getRequest();

     $if(!$request->isPost()){

     }

 }   
}