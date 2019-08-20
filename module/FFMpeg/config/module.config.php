<?php

namespace FFMpeg;

use Zend\Router\Http\Segment;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Router\Http\Literal;
use Zend\Router\Http\TreeRouteStack;

return [
    'controllers' => [
        'factories' => [
            Controller\StreamOutController::class => function ($container) {
                return new Controller\StreamOutController(
                    $container->get(Model\StreamOutTable::class)
                );
            },
            Controller\IndexController::class => function ($container) {
                return new Controller\IndexController(
                    $container->get(Model\StreamTable::class), $container->get(Model\StreamOutTable::class)
                );
            },
            Controller\EncoderController::class => function ($container) {
                return new Controller\EncoderController(
                    $container->get(Model\StreamTable::class), $container->get(Model\StreamOutTable::class)
                );
            },
        ]
    ],
    'router' => [
        'router_class'  => TreeRouteStack::class,
        'routes' => [
            'ffmpeg' => [
                'type' => Segment::class,
                'options' => [
                    'route'         => '/ffmpeg[/:action[/:id]]',
                    'constrains'    => [
                        'action'        => '[a-zA-Z][A-Z0-9_-]*',
                        'id'       => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'    => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'encoder' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'         => '[/:id[/:action]]/encoder',
                            'constrains'    => [
                                'action'        => '[a-zA-Z][A-Z0-9_-]*',
                                'id'            => '[0-9]*',
                            ],
                            'defaults'      => [
                                'controller'        => Controller\EncoderController::class,
                            ],
                        ],
                    ],
                    'streamout' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'         => '/',
                            'constrains'    =>  [
                                'action'        => '[a-zA-Z][A-Z0-9_-]*',
                                'id'       => '[0-9]*',
                                'streamID'       => '[0-9]*',
                            ],
                            'defaults'      => [
                                'controller'    => Controller\StreamOutController::class,
                                'action'        => 'showstreams'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'editstreamout' => [
                                'type'      => Segment::class,
                                'options' => [
                                    'route'     => '/editstreamout[/:id]',
                                    'constrains' => [
                                        'action'    => '[a-z]',
                                        'id'        => '[0-9]',
                                    ],
                                    'defaults'  => [
                                        'controller' => Controller\StreamOutController::class,
                                        'action'    => 'editstreamout',
                                    ],
                                ],
                            ],
                            'addstreamout' => [
                                'type'      => Segment::class,
                                'options' => [
                                    'route'     => '/addstreamout[/:id]',
                                    'constrains' => [
                                        'action'    => '[a-z]',
                                        'id'        => '[0-9]',
                                    ],
                                    'defaults'  => [
                                        'controller' => Controller\StreamOutController::class,
                                        'action'    => 'addstreamout',
                                    ],
                                ],
                            ],
                             'deletestreamout' => [
                                'type'      => Segment::class,
                                'options' => [
                                    'route'     => '/deletestreamout[/:id]',
                                    'constrains' => [
                                        'action'    => '[a-z]',
                                        'id'        => '[0-9]',
                                    ],
                                    'defaults'  => [
                                        'controller' => Controller\StreamOutController::class,
                                        'action'    => 'deletestreamout',
                                    ],
                                ],
                            ],
                            
                        ],
                    ],
                ],

            ],
        ],
    ],
    'view_manager'  => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];
