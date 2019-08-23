<?php

namespace FFMpeg\Controller;

use FFMpeg\Model\OptionsTable;
use Zend\Mvc\Controller\AbstractActionController;

class OptionsController extends AbstractActionController
{
    private $table;

    public function __construct(OptionsTable $table)
    {
        $this->table = $table;
    }


 public function showOptionsAction(){

 }   
}