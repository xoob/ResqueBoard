<?php

    include(dirname(dirname(ROOT)) . DS . 'vendor' . DS . 'autoload.php');
    
    define('APPLICATION_NAME', 'RescueBoard');
    define('TITLE_SEP', ' | ');
    
    $settings = array(
            /*'mongo' => array(
             'host' => 'localhost',
                    'port' => 27017,
                    'database' => 'cube_development'
            ),*/
            /* 'redis' => array(
             'host' => '127.0.0.1',
                    'port' => 6379
            ),*/
            /*'resquePrefix' => 'resque'*/
    );
    
    $config = array(
                'debug' => false,
                'view' => 'Fresque\View\MyView',
                'templates.path' => ROOT . DS .'View'
            );