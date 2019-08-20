<?php

namespace FFMpeg;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\StreamOutTable::class => function ($container) {
                    $tableOutGateway = $container->get(Model\StreamOutTableGateway::class);
                    return new Model\StreamOutTable(($tableOutGateway));
                },
                Model\StreamOutTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\StreamsOut());
                    return new TableGateway('streamsOut', $dbAdapter, null, $resultSetPrototype);
                },
                Model\StreamTable::class => function ($container) {
                    $tableGateway = $container->get(Model\StreamTableGateway::class);
                    return new Model\StreamTable($tableGateway);
                },
                Model\StreamTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Streams());
                    return new TableGateway('streams', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
}
