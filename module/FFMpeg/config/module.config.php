<?php

namespace FFMpeg;

use Zend\Router\Http\Segment;
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
            Controller\OptionsController::class => function ($container) {
                return new Controller\OptionsController(
                    $container->get(Model\OptionsTable::class)
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
                    'route'         => '/ffmpeg/[:action[/:id]]',
                    'constrains'    => [
                        'action'        => '[a-zA-Z][A-Z0-9_-]*',
                        'id'       => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'    => 'index',
                        'id'        => '0',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'encoder' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'         => '[/:id[/:action]]/encoder',
                            'verb'          => 'post',
                            'constrains'    => [
                                'action'        => '[a-zA-Z][A-Z0-9_-]*',
                                'id'            => '[0-9]*',
                            ],
                            'defaults'      => [
                                'controller'        => Controller\EncoderController::class,
                            ],
                        ],
                    ],
                    'options' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/[:action]',
                            'defaults'  => [
                                'controller' => Controller\OptionsController::class,
                                'action'    => 'showoptions',
                            ],
                        ],
                    ],
                    'streamout' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'         => '/streamout[/:id]',
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
    'view_manager'  =>  [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        
    ],
     'navigation' => [
        'default' => [
            [
                'label' => 'FFMpeg',
                'route' => 'ffmpeg',
                'pages' => [
                    [
                        'label'     => 'Add',
                        'route'     => 'ffmpeg',
                        'action'    => 'addstream',
                    ],
                    [
                        'label'     => 'Edit',
                        'route'     => 'ffmpeg',
                        'action'    => 'editstream',
                    ],
                    [
                        'label'     => "Delete",
                        'route'     => 'ffmpeg',
                        'action'    => 'deletestream',
                    ],
                    [
                        'label'     => 'Options',
                        'route'     => 'ffmpeg/options',
                        'action'    => 'showoptions'
                    ],
                    [
                        'label'     => 'List of Out streams',
                        'route'     => 'ffmpeg/streamout',
                        'action'    => 'showstreams',
                        'pages'     => [
                            [
                                'label'     => 'Edit Stream Out',
                                'route'     => 'ffmpeg/streamout/editstreamouts[/:id]',
                                'action'    => 'editstreamout',
                            ],
                        ],
                    ],

                ],
            ],
        ],
    ],

];