<?php
   // require_once __DIR__.'/config.php';
    require_once './config.php';
    require_once __DIR__ . '/vendor/autoload.php';
    

    // Sécurise le cookie de session avec httponly
    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => $_SERVER['SERVER_NAME'],
        'httponly' => true
    ]);

    session_start();
  
    // no need to redefine _ROOTPATH_ here
    
   // define('_ROOTPATH_', __DIR__);
    define('_TEMPLATEPATH_', __DIR__.'/templates');
  //  spl_autoload_register();

    use App\Controller\Controller;
    // Nous avons besoin de cette classe pour verifier si l'utilisateur est connecté
    use App\Entity\User;
    use App\Entity\Movie;
    use App\Entity\Genre;
    use App\Entity\Director;
    use App\Security\Security;
    use MongoDB\BSON\UTCDateTime;


    $controller = new Controller();
    $controller->route();
    
?>