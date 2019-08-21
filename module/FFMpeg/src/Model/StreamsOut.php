<?php
namespace FFMpeg\Model;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class StreamsOut implements InputFilterAwareInterface
{
    public $id;
    public $dst;
    public $vcodec;
    public $acodec;
    public $scodec;
    public $vbitrate;
    public $abitrate;
    public $muxrate;
    public $minrate;
    public $maxrate;
    public $format;
    public $streamID;
    public $scale;
    public $encstatus;
    public $outstreams;

    private $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->dst = (!empty($data['dst'])) ? $data['dst'] : null;
        $this->vcodec = (!empty($data['vcodec'])) ? $data['vcodec'] : 'copy';
        $this->acodec = (!empty($data['acodec'])) ? $data['acodec'] : 'copy';
        $this->scodec = (!empty($data['scodec'])) ? $data['scodec'] : 'copy';
        $this->vbitrate = (!empty($data['vbitrate'])) ? $data['vbitrate'] : null;
        $this->abitrate = (!empty($data['abitrate'])) ? $data['abitrate'] : null;
        $this->muxrate = (!empty($data['muxrate'])) ? $data['muxrate'] : null;
        $this->minrate = (!empty($data['minrate'])) ? $data['minrate'] : null;
        $this->maxrate = (!empty($data['maxrate'])) ? $data['maxrate'] : null;
        $this->format = (!empty($data['format'])) ? $data['format'] : 'mpegts';
        $this->streamID = (!empty($data['streamID'])) ? $data['streamID'] : null;
        $this->scale = (!empty($data['scale'])) ? $data['scale'] : null;

    }

    public function getArrayCopy()
    {

        return [
            'id' => $this->id,
            'dst' => $this->dst,
            'vcodec' => $this->vcodec,
            'acodec' => $this->acodec,
            'scodec' => $this->scodec,
            'vbitrate' => $this->vbitrate,
            'abitrate' => $this->abitrate,
            'muxrate' => $this->muxrate,
            'minrate' => $this->minrate,
            'maxrate' => $this->maxrate,
            'format' => $this->format,
            'streamID' => $this->streamID,
            'scale' => $this->scale,
        ];

    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf('%s does not allow injection of and alternate input filter', __CLASS__));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => 'false',
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'streamID',
            'required' => 'false',
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'dst',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'vcodec',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'acodec',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'scodec',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'vbitrate',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'abitrate',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'muxrate',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'minrate',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'maxrate',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'format',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $inputFilter->add([
            'name' => 'scale',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],

        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}