<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'FadoeBase' => __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'fadoeNavigation' => function($sm) {
                /* @var $navigation Zend\View\Helper\Navigation */
                $navigation = $sm->get('navigation');
                $navigation->getPluginManager()->setInvokableClass(
                    'twitterBootstrapMenu',
                    'FadoeBase\View\Helper\Navigation\TwitterBootstrapMenu'
                );
                return $navigation;
            }
        )
    )

);
