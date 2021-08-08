<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AdminAuthMiddleware extends CI_Controller  {
    protected $controller;
    protected $ci;
    public $roles = array();
    public function __construct($controller, $ci)
    {
        $this->controller = $controller;
        $this->ci = $ci;
    }

    public function run($headers){
        $this->is_auth = $this->isAuth($headers);
    }

    public function isAuth($headers){
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                $bearerToken = $matches[1];
            }
            $decodedToken = AUTHORIZATION::validateToken($bearerToken);
            if ($decodedToken != false) {
                return $decodedToken;
            }
        }
        return null;
    }
}