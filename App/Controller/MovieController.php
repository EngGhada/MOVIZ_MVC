<?php

namespace App\Controller;

use App\Repository\DirectorRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use App\Security\Security;

class MovieController extends Controller
{
    public function route(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                  // Handle all POST logic first
                  $action = $_POST['action'] ?? null;
                  switch ($action) {
                    case 'create':
                        $this->create(); // Handle form submission
                        break;
                    case 'edit':
                        $this->edit();   // Handle update
                        break;
                   //case 'delete':
                       // $this->delete(); // If you want to support deletion
                       // break;
                    default:
                        throw new \Exception("Action POST non reconnue : $action");
                   }
                }
             if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // GET request handling (e.g., display forms, list, show)
                if (isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'show':
                            $this->show();
                            break;
                        case 'list':
                            $this->list();
                            break;
                        case 'create':
                            $this->create(); // GET form view
                            break;
                        case 'edit':
                            $this->edit();  // Show edit form
                            break;
                        case 'delete':
                            $this->delete(); // Handle deletion
                            break;
                        default:
                            throw new \Exception("Action GET non reconnue : " . $_GET['action']);
                    }
                } else {
                    throw new \Exception("Aucune action GET détectée");
                }
    
            } else {
                throw new \Exception("Méthode HTTP non supportée");
            }
    
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    protected function show()
    {
        try {
            if (isset($_GET['id'])) {
                // Récupérer le film avec le Repository
                $movieRepository = new MovieRepository();
                $id = (int)$_GET['id'];
                $movie = $movieRepository->findOneById($id);

                if ($movie) {
                    $genreRepository = new GenreRepository();
                    $genres = $genreRepository->findAllByMovieId($movie->getId());

                    $directorRepository = new DirectorRepository();
                    $directors = $directorRepository->findAllByMovieId($movie->getId());
                    
                    $reviewRepository = new ReviewRepository();
                    $reviews = $reviewRepository->findByMovieId($movie->getId());

                    $this->render('movie/show', [
                        'movie' => $movie,
                        'genres' => $genres,
                        'directors' => $directors,
                        'reviews' => $reviews
                    ]);
                } else {
                    throw new \Exception("Ce film n'existe pas");
                }

            } else {
                throw new \Exception("L'id est manquant en paramètre d'url");
                
            }

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        } 
    }

    protected function list()
    {
        try {
            // Récupérer tous les films
            $movieRepository = new MovieRepository();
            $movies = $movieRepository->findAll();

            $this->render('movie/list', [
                'movies' => $movies,
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        } 
    }

    protected function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Display the form
            $genreRepo = new GenreRepository();
            $directorRepo = new DirectorRepository();
            $genres = $genreRepo->findAll();
            $directors = $directorRepo->findAll();
    
            $this->render('movie/create', [
                'genres' => $genres,
                'directors' => $directors
            ]);
            return;
        }
    
        // Handle POST
        if (
            !isset($_POST['title'], $_POST['synopsis'], $_POST['release_date'], $_POST['duration']) ||
            empty($_FILES['image']['tmp_name'])
        ) {
            throw new \Exception("Form data is incomplete or missing.");
        }
    
        $uploadResult = \App\Tools\FileTools::uploadImage('/uploads/movies/', $_FILES['image']);
        if (!empty($uploadResult['errors'])) {
            throw new \Exception(implode(', ', $uploadResult['errors']));
        }
    
        $imageName = $uploadResult['fileName'];
        $title = $_POST['title'];
        $synopsis = $_POST['synopsis'];
        $releaseDate = $_POST['release_date'];
        $duration = $_POST['duration'];
        $genreIds = $_POST['genres'] ?? [];
        $directorIds = $_POST['directors'] ?? [];
    
        $movieRepo = new MovieRepository();
        $movieId = $movieRepo->create($title, $synopsis, $releaseDate, $duration, $imageName);
    
        $genreRepo = new GenreRepository();
        foreach ($genreIds as $genreId) {
            $genreRepo->linkGenreToMovie($movieId, (int)$genreId);
        }
    
        $directorRepo = new DirectorRepository();
        foreach ($directorIds as $directorId) {
            $directorRepo->linkDirectorToMovie($movieId, (int)$directorId);
        }
    
        header("Location: index.php?controller=movie&action=show&id=" . $movieId);
        exit;
    }

        protected function edit(): void
       {
        try {
            $movieId = $_GET['id'] ?? null;

            if (!$movieId) {
                throw new \Exception("ID du film manquant.");
            }

            $movieRepo = new MovieRepository();
            $genreRepo = new GenreRepository();
            $directorRepo = new DirectorRepository();

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $movie = $movieRepo->findOneById($movieId);
                if (!$movie) {
                    throw new \Exception("Film introuvable.");
                }

                $genres = $genreRepo->findAll();
                $directors = $directorRepo->findAll();

                $this->render('movie/edit', compact('movie', 'genres', 'directors'));
                return;
            }
            // Handle POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (
                    !isset($_POST['title'], $_POST['synopsis'], $_POST['release_date'], $_POST['duration']) ||
                    empty($_POST['genres']) || empty($_POST['directors'])
                ) {
                    throw new \Exception("Données du formulaire incomplètes.");
                }

                $title = $_POST['title'];
                $synopsis = $_POST['synopsis'];
                $releaseDate = $_POST['release_date'];
                $duration = $_POST['duration'];
                $genreIds = $_POST['genres'];
                $directorIds = $_POST['directors'];

                $movie = $movieRepo->findOneById($movieId);
                if (!$movie) {
                    throw new \Exception("Film introuvable.");
                }

                $imageName = $movie->getImageName(); // default to existing
                if (!empty($_FILES['image']['name'])) {
                      $uploadResult = \App\Tools\FileTools::uploadImage('/uploads/movies/', $_FILES['image'],$imageName);
                    if (!empty($uploadResult['errors'])) {
                        throw new \Exception(implode(', ', $uploadResult['errors']));
                    }
                    $imageName = $uploadResult['fileName'];
                }

                $movieRepo->update($movieId, $title, $synopsis, $releaseDate, $duration, $imageName);

                // Re-link genres
                $genreRepo->unlinkAllGenresFromMovie($movieId);
                foreach ($genreIds as $genreId) {
                    $genreRepo->linkGenreToMovie($movieId, (int)$genreId);
                }

                // Re-link directors
                $directorRepo->unlinkAllDirectorsFromMovie($movieId);
                foreach ($directorIds as $directorId) {
                    $directorRepo->linkDirectorToMovie($movieId, (int)$directorId);
                }
                header("Location: index.php?controller=movie&action=show&id=" . $movieId);
                exit;
            }

            throw new \Exception("Méthode HTTP non supportée.");

        } catch (\Exception $e) {
            $this->render('errors/default', ['error' => $e->getMessage()]);
        }
    }
    
    protected function delete(): void
     {
        try {
            $movieId = $_GET['id'] ?? null;
            if (!$movieId) {
                throw new \Exception("ID du film manquant.");
            }

            $movieRepo = new MovieRepository();
            $genreRepo = new GenreRepository();
            $directorRepo = new DirectorRepository();

            // Fetch current movie to get image name
            $movie = $movieRepo->findOneById($movieId);
            if (!$movie) {
                throw new \Exception("Film introuvable.");
            }

            // Delete image file
            $imageName = $movie->getImageName();
            if (!empty($imageName)) {
                $imagePath = _ROOTPATH_ . '/uploads/movies/' . $imageName;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Unlink genres & directors
            $genreRepo->unlinkAllGenresFromMovie($movieId);
            $directorRepo->unlinkAllDirectorsFromMovie($movieId);

            // Delete movie
            $movieRepo->delete($movieId);

            // Redirects
            header("Location: index.php?controller=movie&action=list");
            exit;

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

}