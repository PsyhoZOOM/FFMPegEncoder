<?php

namespace FFMpeg\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select as Select;
use FFMpeg\Model\StreamsOut;
use Zend\Mvc\Plugin\Identity\Exception\RuntimeException;

class StreamOutTable
{
    protected $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getStreamsOutOfMainStream($id)
    {
        $id = (int) $id;
        $select = new Select();
        $select->from('streamsOut');
        $select->where(['streamID' => $id]);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function countMainStream($id)
    {
        $numberOfStreams  = $this->tableGateway->select(['streamID' => $id])->count();
        return $numberOfStreams;
    }

    public function getStreamOut($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf('Could not find row with identifier %d', $id));
        }
        return $row;
    }

    public function saveStreamOut(StreamsOut $streamOut)
    {
        $data = [
            'dst'       => $streamOut->dst,
            'vcodec'    => $streamOut->vcodec,
            'acodec'    => $streamOut->acodec,
            'scodec'    => $streamOut->scodec,
            'vbitrate'  => $streamOut->vbitrate,
            'abitrate'  => $streamOut->abitrate,
            'muxrate'   => $streamOut->muxrate,
            'minrate'   => $streamOut->minrate,
            'maxrate'   => $streamOut->maxrate,
            'format'    => $streamOut->format,
            'streamID'  => $streamOut->streamID,
            'scale'     => $streamOut->scale

        ];

        $id = (int) $streamOut->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getStreamOut($id);
        } catch (RuntimeException $d) {
            throw new RuntimeException(sprintf('Cannot Update Stream with identifier %d', $id));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteStreamOut($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}