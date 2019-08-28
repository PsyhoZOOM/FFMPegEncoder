<?php

namespace FFMpeg\Controller;

use FFMpeg\Form\OptionsForm;
use FFMpeg\Model\Options;
use FFMpeg\Model\OptionsTable;
use Zend\Mvc\Controller\AbstractActionController;

class OptionsController extends AbstractActionController
{
    private $table;

    public function __construct(OptionsTable $table)
    {
        $this->table = $table;
    }

    public function showOptionsAction()
    {
        $request = $this->getRequest();
        $option = $this->table->fetchAll();
        $excArr = [
            'id' => $option['id'],
            'ffmpegPath' => $option['ffmpegPath'],
            'remote'    => $option['remote'],
            'remoteAddress' => $option['remoteAddress'],
            'remotePort'   => $option['remotePort'],
            'remoteUser'        => $option['remoteUser'],
            'remotePass'        => $option['remotePass'],
        ];

        $optionBind = new Options();


        $options = new Options();

        $form = new OptionsForm(null);
        $form->get('submit')->setValue("Save");


        $viewData = ['form' => $form];

        if (!$request->isPost()) {
            $optionBind->exchangeArray($excArr);
            $form->bind($optionBind);
            return $viewData;
        }

        $form->setInputFilter($options->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        //TO-DO SAVE DATA
        $options->exchangeArray($form->getData());
        $this->table->saveOptions($options);

        return $this->redirect()->toRoute('ffmpeg');
    }
}
