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

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        $rowset = $resultSet->toArray();
        $row = $rowset[0];
        return $row;
    }

    public function saveOptions(Options $options)
    {
        $data = [
            'ffmpegPath'        => $options->ffmpegPath,
            'remote'            => $options->remote,
            'remoteAddress'     => $options->remoteAddress,
            'remotePort'        => $options->remotePort,
            'remoteUser'        => $options->remoteUser,
            'remotePass'        => $options->remotePass,
        ];


        //Delete all options from table options
        $this->tableGateway->getAdapter()->query('DELETE FROM ' . $this->tableGateway->getTable())->execute();

        //Insert options in table options
        $this->tableGateway->insert($data);
    }
}