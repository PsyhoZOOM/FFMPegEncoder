<?php

namespace FFMpeg\Model;

use Zend\EventManager\Exception\DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Filter\StringTrim;

class Options implements InputFilterAwareInterface
{
 public $id;
 public $ffmpegPath;
 public $remote;
 public $remoteAddress;
 public $remotePort;
 public $remoteUser;
 public $remotePass;

 private $inputFilter;

 public function exchangeArray($data){
     $this->id = (!empty($data['id'])) ? $data['id'] : null;
     $this->ffmpegPath = (!empty($data['ffmpegPath'])) ? $data['ffmpegPath'] : null;
     $this->$remote = (!empty($data['remote'])) ? $data['remote'] : false;
     $this->remoteAddress = (!empty($data['remoteAddress'])) ? $data['remoteAddress'] : null;
     $this->remotePort = (!empty($data['remotePort'])) ? $data['remotePort'] : null;
     $this->remoteUser = (!empty($data['remoteUser'])) ? $data['remoteUser'] : null;
     $this->remotePass = (!empty($data['remotePass'])) ? $data['remotePass'] : null;
 }


 public function getArrayCopy(){
     return [
         'id'           => $this->id,
         'ffmpegPath'   => $this->ffmpegPath,
         'remote'       => $this->remote,
         'remoteAddress'=> $this->remoteAddress,
         'remotePort'   => $this->remotePort,
         'remoteUser'   => $this->remoteUser,
         'remotePass'   => $this->remotePass
     ];
 }

 public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
 {
     throw new DomainException(sprintf("$s does not allow injection of and alternate input filter", __CLASS__));
 }

 public function getInputFilter()
 {
     if($this->inputFilter){
         return $this->inputFilter;
     }

     $inputFilter = new InputFilter();

     $inputFilter->add([
         'name' => 'ffmpegPath',
         'required' => 'true',
         'filters' => [
             ['name' => StripTags::class],
             ['name' => StringTrim::class],
         ],
     ]);

     $inputFilter->add([
         'name' => 'remote',
         'required' => 'true',
         'validators' => [
             [
                 'name' => InArray::class,
                 'options' => [
                     'haystack' => [true, false],
                 ],
                ],
            
            ],
     ]);

     $this->inputFilter = $inputFilter;
     return $this->inputFilter;

 }

}