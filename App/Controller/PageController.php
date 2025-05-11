<?php

namespace App\Controller;
use App\Repository\MovieRepository;


class PageController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'home':
                        //charger controleur home
                        $this->home();
                        break;
                    default:
                        throw new \Exception("Cette action n'existe pas : ".$_GET['action']);
                        break;
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch(\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }

    }

    /*
    Exemple d'appel depuis l'url
        ?controller=page&action=home
    */
    protected function home() 
    {   
        // On charge le repository
        $movieRepository = new MovieRepository();
        // On charge les films
        $movies = $movieRepository->findAll();
        // On charge l'utilisateur
        
         $user = $_SESSION['user'] ?? null;
        
        $this->render('page/home', [
            'movies' => $movies,
             'user' => $user
        ]);

    }

}