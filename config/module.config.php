<?php 
return array(
			'invokables' => array(
				'DBwork\Controller\DBwork => 'DBwork\Controller\DBworkController',
				),
			),
'router' => array(
        'routes' => array(
            'dbwork' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/dbwork[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'DBwork\Controller\DBwork',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


?>