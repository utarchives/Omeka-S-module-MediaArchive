<?php
namespace MediaArchive;

return [
    'api_adapters' => [
        'invokables' => [
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            OMEKA_PATH.'/modules/MediaArchive/view',
        ],
    ],
//     'entity_manager' => [
//         'mapping_classes_paths' => [
//             dirname(__DIR__) . '/src/Entity',
//         ],
//         'proxy_paths' => [
//             dirname(__DIR__) . '/data/doctrine-proxies',
//         ],
//     ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => OMEKA_PATH . '/modules/SpecialCharacterSearch/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'MediaArchive\Controller\Site\Index' => Controller\Site\IndexController::class,
        ],
        'factories' => [
//             'MediaArchive\Controller\Site\Index' => Service\Controller\Admin\IndexControllerFactory::class,
        ],
    ],
    'block_layouts' => [
        'invokables' => [
        ],
        'factories' => [

        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'navigation_links' => [
        'invokables' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'mediaArchive' => View\Helper\MediaArchive::class,
        ],
        'factories' => [
        ]
    ],
    'navigation' => [
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'dowonload-media-zip' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/dowonload-media-zip/:id',
                            'defaults' => [
                                '__NAMESPACE__' => 'MediaArchive\Controller\Site',
                                'controller' => 'Index',
                                'action' => 'download',
                            ],
                            'constraints' => [
                                'id' => '\d+',
                            ],
                        ],
                    ],
                ],
            ],
            'admin' => [
            ],
        ],
    ],
];
