<?php

namespace FFMpeg\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class OptionsTable
{
    protected $tableGateway;


    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function saveOptions(Options $options){
        $data = [
            'ffmpegPath'        => $options->ffmpegPath,
            'remote'            => $options->remote,
            'remoteAddress'     => $options->remoteAddress,
            'remotePort'        => $options->remotePort,
            'remoteUset'        => $options->remoteUser,
            'remotePass'        => $options->remotePass,
        ];

        $id = (int) $options->id;

        if($id === 0){
            $this->tableGateway->insert($data);
        }

        try{
            $this->getOptions($id);
        }catch (RuntimeException $e){
            throw new RuntimeException(sprintf('Cannaot Update Options with ID: %d; does not exist', $id));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }
}