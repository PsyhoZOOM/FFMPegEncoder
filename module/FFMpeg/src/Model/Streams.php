<?php

namespace FFMpeg\Model;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilterAwareInterface;
use DomainException;

class Streams implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $src;
    public $desc;
    public $enc_status;
    public $outstreams;
    public $path;

    private $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->src = (!empty($data['src'])) ? $data['src'] : null;
        $this->desc = (!empty($data['desc'])) ? $data['desc'] : null;
        $this->enc_status =  (!empty($data['enc_status'])) ? $data['enc_status'] : false;
        $this->streamouts = (!empty($data['outstreams'])) ? $data['outstreams'] : 0;
        $this->path = (!empty($data['path'])) ? $data['path'] : '';
    }

    public function getArrayCopy()
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'src'        => $this->src,
            'desc'       => $this->desc,
            'enc_status' => $this->enc_status,
            'outstreams' => $this->outstreams,
            'path'      => $this->path

        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf("%s does not allow injection of an alternate input filter", __CLASS__));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name'      => 'id',
            'required'  => 'true',
            'filters'   => [
                ['name'     => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name'     => StripTags::class],
                ['name'     => StringTrim::class],
            ],
            'validators' => [
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'encoding'  => 'UTF-8',
                        'min'       => 1,
                        'max'       => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'      => 'src',
            'required'  => true,
            'filters'   => [
                ['name'     => StripTags::class],
                ['name'     => StringTrim::class],
            ],
            'validators' => [
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'encoding'  => 'UTF-8',
                        'min'       =>  1,
                        'max'       => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name'      =>  'desc',
            'required'  => false,
            'filters'   => [
                ['name'      => StripTags::class],
                ['name'      => StringTrim::class],
            ],
            'validators'    => [
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'enconding' => 'UTF-8',
                        'min'       => 0,
                        'max'       => 1000,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
