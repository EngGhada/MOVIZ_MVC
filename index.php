<?php
    require_once __DIR__.'/config.php';
    require_once __DIR__ . '/vendor/autoload.php';

     use App\Controller\Controller; 

    // Sécurise le cookie de session avec httponly
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => $_SERVER['SERVER_NAME'],
        'httponly' => true
    ]);

    session_start();
  
    $controller = new Controller();
    $controller->route();
    
?>