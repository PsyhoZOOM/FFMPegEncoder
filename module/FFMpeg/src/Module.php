<?php

namespace FFMpeg;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;

class Module implements ConfigProviderInterface
{
    const VERSION = '3.0.3-dev';
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
                    return new Model\StreamOutTable($tableOutGateway);
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

                Model\OptionsTable::class => function ($container) {
                    $optionsGateway = $container->get(Model\OptionsTableGateway::class);
                    return new Model\OptionsTable($optionsGateway);
                },

                Model\OptionsTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Options());
                    return new TableGateway('options', $dbAdapter, null, $resultSetPrototype);
                }

            ],
        ];
    }
}