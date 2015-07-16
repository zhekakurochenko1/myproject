<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Cars\Controller\Cars' => 'Cars\Controller\CarsController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'cars' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/cars[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cars\Controller\Cars',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'cars' => __DIR__ . '/../view',
        ),
    ),
);