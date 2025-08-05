<?php


App::uses('Controller', 'Controller');

class AppController extends Controller
{


    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'users', 'action' => 'view'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authError' => 'You must be logged in to access this page.',
            'authorize' => array('Controller'), // Use controller-based authorization
        )
    );


    public function beforeFilter()
    {
        // Allow login page without authentication
        // $this->Auth->allow('login');
        $this->Auth->allow(); // allow access without login
    }

    public function isAuthorized($user)
    {
        //echo "koko";exit();
        // You can customize role-based access here
        return true;
    }
}
