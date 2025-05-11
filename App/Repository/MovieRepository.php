<?php

namespace App\Repository;
use App\Entity\Movie;

class MovieRepository extends Repository
{
    public function findOneById(int $id): Movie|bool
    {
        $query = $this->pdo->prepare("SELECT * FROM movie WHERE id = :id");
        $query->bindParam(':id', $id, $this->pdo::PARAM_STR);
        $query->execute();
        $movieData = $query->fetch($this->pdo::FETCH_ASSOC);
        if ($movieData) {
           $movie= Movie::createAndHydrate($movieData);
               // Inject genres
        $genreRepo = new GenreRepository();
        $genres = $genreRepo->findAllByMovieId($id);
        $movie->setGenres($genres);

        // Inject directors (optional but logical)
        $directorRepo = new DirectorRepository();
        $directors = $directorRepo->findAllByMovieId($id);
        $movie->setDirectors($directors);

        return $movie;
        } else {
            return false;
        }
    }

    public function findAll(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM movie");
        $query->execute();
        $movies = $query->fetchAll($this->pdo::FETCH_ASSOC);

        $moviesArray = [];

        if ($movies) {
            foreach ($movies as $movie) {
                $moviesArray[] = Movie::createAndHydrate($movie);
            }
        }
        return $moviesArray;
    }

    public function linkDirector(int $movieId, int $directorId): void
    {
        $query = $this->pdo->prepare("
            INSERT INTO movie_director (movie_id, director_id)
            VALUES (:movie_id, :director_id)
        ");
    
        $query->bindValue(':movie_id', $movieId, \PDO::PARAM_INT);
        $query->bindValue(':director_id', $directorId, \PDO::PARAM_INT);
    
        $query->execute();
    }
    
    public function getImageNameById(int $id): ?string
    {
        $query = $this->pdo->prepare("SELECT image_name FROM movie WHERE id = :id");
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch($this->pdo::FETCH_ASSOC);
    
        return $result ? $result['image_name'] : null;
    
    }


        public function create(string $title, string $synopsis, string $releaseDate, string $duration, string $imageName): int
    {
        $query = $this->pdo->prepare("INSERT INTO movie (title, synopsis, release_date, duration, image_name)
                                             VALUES (:title, :synopsis, :release_date, :duration, :image_name)");

        $query->bindValue(':title', $title, \PDO::PARAM_STR);
        $query->bindValue(':synopsis', $synopsis, \PDO::PARAM_STR);
        $query->bindValue(':release_date', $releaseDate, \PDO::PARAM_STR);
        $query->bindValue(':duration', $duration, \PDO::PARAM_STR);
        $query->bindValue(':image_name', $imageName, \PDO::PARAM_STR);
       
        $query->execute();

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $title, string $synopsis, string $releaseDate, string $duration, string $imageName): bool
    {
        $query = $this->pdo->prepare("UPDATE movie SET title = :title,
                                                       synopsis = :synopsis,
                                                       release_date = :release_date,
                                                       duration = :duration,
                                                       image_name = :image_name 
                                                        WHERE id = :id");

        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->bindValue(':title', $title, \PDO::PARAM_STR);
        $query->bindValue(':synopsis', $synopsis, \PDO::PARAM_STR);
        $query->bindValue(':release_date', $releaseDate, \PDO::PARAM_STR);
        $query->bindValue(':duration', $duration, \PDO::PARAM_STR);
        $query->bindValue(':image_name', $imageName, \PDO::PARAM_STR);
       

        return $query->execute();
    }
    
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare("DELETE FROM movie WHERE id = :id");
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        return $query->execute();
    }

   
}