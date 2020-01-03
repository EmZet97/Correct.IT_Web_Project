<?php

require_once 'controllers/DefaultController.php';
require_once 'controllers/DocsController.php';

class Routing
{
    public $routes = [];

    public function __construct()
    {
        $this->routes = [
            'index' => [
                'controller' => 'DefaultController',
                'action' => 'index'
            ],
            'login' => [
                'controller' => 'DefaultController',
                'action' => 'login'
            ],
            'logout' => [
                'controller' => 'DefaultController',
                'action' => 'logout'
            ],
            'register' => [
                'controller' => 'DefaultController',
                'action' => 'register'
            ],
            'fileNotFound' => [
                'controller' => 'DefaultController',
                'action' => 'fileNotFound'
            ],
            'browseDocs' => [
                'controller' => 'DocsController',
                'action' => 'browseDocs'
            ],
            'correctDoc' => [
                'controller' => 'DocsController',
                'action' => 'correctDoc'
            ],
            'correctDoc_Execute' => [
                'controller' => 'DocsController',
                'action' => 'correctDoc_Execute'
            ],
            'createDoc' => [
                'controller' => 'DocsController',
                'action' => 'createDoc'
            ],
            'createDoc_Execute' => [
                'controller' => 'DocsController',
                'action' => 'createDoc_Execute'
            ],
            'deleteDoc' => [
                'controller' => 'DocsController',
                'action' => 'deleteDoc'
            ],
            'editDoc' => [
                'controller' => 'DocsController',
                'action' => 'editDoc'
            ],
            'editDoc_Execute' => [
                'controller' => 'DocsController',
                'action' => 'editDoc_Execute'
            ],
            'myDocs' => [
                'controller' => 'DocsController',
                'action' => 'myDocs'
            ]
        ];
    }

    public function run()
    {        
        $page = isset($_GET['page']) && isset($this->routes[$_GET['page']]) ? $_GET['page'] : 'login';        

        if ($this->routes[$page]) {
            //echo "Hello there! :) Jesteś w:" . $this->routes[$page]['controller'] . "/" . $this->routes[$page]['action'] ;
            $class = $this->routes[$page]['controller'];
            $action = $this->routes[$page]['action'];

            $object = new $class;
            $object->$action();
        }
    }

}