<?php

namespace App\Repository;
use App\Entity\Director;

class DirectorRepository extends Repository
{

  

    public function findAllByMovieId(int $movie_id): array
    {
        $query = $this->pdo->prepare("SELECT * FROM director d
                                      LEFT JOIN movie_director md ON md.director_id = d.id
                                      WHERE md.movie_id = :movie_id");
        $query->bindParam(':movie_id', $movie_id, $this->pdo::PARAM_STR);
        $query->execute();
        $directors = $query->fetchAll($this->pdo::FETCH_ASSOC);

        $directorsArray = [];

        if ($directors) {
            foreach ($directors as $director) {
                $directorsArray[] = Director::createAndHydrate($director);
            }
        }

        return $directorsArray;
    }

    public function linkDirectorToMovie(int $movieId, int $directorId): void
    {
        $query = $this->pdo->prepare("INSERT INTO movie_director (movie_id, director_id) VALUES (:movie_id, :director_id)");
        $query->bindParam(':movie_id', $movieId, $this->pdo::PARAM_INT);
        $query->bindParam(':director_id', $directorId, $this->pdo::PARAM_INT);
        $query->execute();
    }

    public function findAll(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM director");
        $query->execute();
        $directors = $query->fetchAll($this->pdo::FETCH_ASSOC);

        $directorsArray = [];

        if ($directors) {
            foreach ($directors as $director) {
                $directorsArray[] = Director::createAndHydrate($director);
            }
        }

        return $directorsArray;
    }
   public function unlinkAllDirectorsFromMovie(int $movieId): void
    {
        $query = $this->pdo->prepare("DELETE FROM movie_director WHERE movie_id = :movie_id");
        $query->bindParam(':movie_id', $movieId, $this->pdo::PARAM_INT);
        $query->execute();
    } 
    
    public function insert($first_name , $last_name): int
    {
        $query = $this->pdo->prepare("INSERT INTO director (first_name , last_name) VALUES (:first_name, :last_name)");
        $query->bindParam(':first_name',$first_name , $this->pdo::PARAM_STR);
        $query->bindParam(':last_name',$last_name , $this->pdo::PARAM_STR);
        $query->execute();
        return (int)$this->pdo->lastInsertId();
    }
    
    
}