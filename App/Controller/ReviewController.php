<?php

namespace App\Controller;

use App\Db\Mongo;
use App\Security\Security;
use MongoDB\BSON\UTCDateTime;
use App\Controller\Controller;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use App\Repository\DirectorRepository;

class ReviewController extends Controller
{
    public function route(): void
    {
        $action = $_GET['action'] ?? 'list';

        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'update':
                $this->update();
                break;
            default:
                echo "Action inconnue pour ReviewController";
        }
    }

    private function create(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitReview'])) {
            $rate = (int)$_POST['rate'];
            $review = trim($_POST['review']);
            $userId = Security::getCurrentUserId();
            $userName = Security::getCurrentUserName();
            $movieId = (int)$_GET['movie_id'];

            if (!$rate || !$review || !$userId || !$movieId || !$userName) {
                $errors[] = 'Champs invalides ou manquants';
            }

            if (empty($errors)) {
                
                $repository = new ReviewRepository();
                $repository->insertReview([
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'movie_id' => $movieId,
                    'rate' => $rate,
                    'review' => $review,
                    'approved' => true,
                    'created_at' => new UTCDateTime()
                ]);
                
                header("Location: index.php?controller=movie&action=show&id={$movieId}");
                exit;
            }
        }

        $this->render('review/form', [
            'errors' => $errors
        ]);
    }


    // Edit review
    // This method is called when the user clicks on the edit button for a review
    // It fetches the review from the database and renders the edit form
    // The form is pre-filled with the review data, allowing the user to modify it
    // After submission, the update method is called to save the changes
    // and redirect the user back to the movie pages

    private function edit(): void    
    {
        $id = $_GET['id'] ?? '';
        $movieId = $_GET['movie_id'] ?? '';

        $movieRepository = new MovieRepository();
        $movie = $movieRepository->findOneById($_GET['movie_id'] ?? 0);
        $genreRepository = new GenreRepository();
        $genres = $genreRepository->findAllByMovieId($movie->getId());

        $directorRepository = new DirectorRepository();
        $directors = $directorRepository->findAllByMovieId($movie->getId());
        
        $repository = new ReviewRepository();
        $review = $repository->findById($id);
    
        if (!$review) {
            throw new \Exception("Review introuvable.");
        }
    
        // Auth check: is the logged-in user the owner of the review?
        if ($_SESSION['user']['id'] !== $review['user_id']) {
            throw new \Exception("Action non autorisée.");
        }
    
        // Fetch all reviews again for this movie
        $reviews = $repository->findByMovieId((int)$movieId);
    
        // Render show page, passing the review being edited
        $this->render('movie/show', [
           'movie' => $movie,
           'genres' => $genres,
            'directors' => $directors,
            'reviews' => $reviews,
            'editReview' => $review,

        ]);
    }


    // Update review
    // This method is called when the user submits the edit form
    // It updates the review in the database and redirects to the movie page

    private function update(): void 
    
    {
        $id = $_POST['id'] ?? '';
        $movieId = $_POST['movie_id'] ?? '';
      
        $repository = new ReviewRepository();
        $review = $repository->findById($id);

        if (!$review || $_SESSION['user']['id'] !== $review['user_id']) {
            throw new \Exception("Action non autorisée.");
        }

        $newData = [
            'rate' => (int)$_POST['rate'],
            'review' => trim($_POST['review']),
            'created_at' => new UTCDateTime()
        ];

        $repository->updateReview($id, $newData);

        header("Location: index.php?controller=movie&action=show&id=$movieId");
        exit;
    }

    
    private function delete(): void
    {
       
    
        $id = $_GET['id'] ?? ''; //if it's set in the URL , use it, otherwise use an empty string
        $movieId = $_GET['movie_id'] ?? '';
    
        if (!$id) {
            throw new \Exception("Review ID manquant.");
        }
    
        // Mongo client and repository
     
        $repository = new ReviewRepository();
    
        // Fetch the review first
        $review = $repository->findById($id);
    
        //  Check if review exists and if the current user is the author
        if (!$review) {
            throw new \Exception("Critique non trouvée.");
        }
    
        if (!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] !== $review['user_id']) {
            throw new \Exception("Accès non autorisé.");
        }
    
        // Proceed with deletion
        $repository->deleteReview($id);
    
        //  Redirect back to movie page
        header("Location: index.php?controller=movie&action=show&id=" . $review['movie_id']);
        exit;
    }
    
}
