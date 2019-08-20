<?php 
namespace FFMpeg\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Authentication\Adapter\DbTable\Exception\RuntimeException;
use Zend\Db\Sql\Sql;

class StreamTable{
    protected $tableGateway;
 
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;   
    }

    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }


    public function getStream($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if(!$row){
            throw new RuntimeException(sprintf('Could not find row with identifier %d', $id));
        }
        return $row;
    }

    public function saveStream(Streams $stream){
        $data = [
            'name'   => $stream->name,
            'src'    => $stream->src,
            'desc'   => $stream->desc,
        ];

        $id = (int) $stream->id;

        if($id === 0){
            $this->tableGateway->insert($data);
            return;
        }

        try{
            $this->getStream($id);
        }catch (RuntimeException $e){
            throw new RuntimeException(sprintf('Cannot Update Stream with identifier %d; does not exist',$id));
        }

        $this->tableGateway->update($data, ['id' => $id]);

    }

    public function deleteStream($id){
        $this->tableGateway->delete(['id' => (int) $id]);
    }

    public function updateOutStream($id, $numStreams){
        $update = $this->tableGateway->getSql()->update();
        $data = ['outstreams' => $numStreams];

        try{
            $this->tableGateway->update($data, ['id'=> $id]);
        }catch (RuntimeException $e){
            throw new RuntimeException(sprintf('Cannot Update Stream with identifier %d; does not exist',$id));

        }


    }



}