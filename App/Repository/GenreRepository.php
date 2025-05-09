<?php

namespace App\Repository;
use App\Entity\Genre;

class GenreRepository extends Repository
{
    public function findAllByMovieId(int $movie_id): array
    {
        $query = $this->pdo->prepare("SELECT * FROM genre g
                                      LEFT JOIN movie_genre mg ON mg.genre_id = g.id
                                      WHERE mg.movie_id = :movie_id");
        $query->bindParam(':movie_id', $movie_id, $this->pdo::PARAM_STR);
        $query->execute();
        $genres = $query->fetchAll($this->pdo::FETCH_ASSOC);

        $genresArray = [];

        if ($genres) {
            foreach ($genres as $genre) {
                $genresArray[] = Genre::createAndHydrate($genre);
            }
        }
        return $genresArray;
    }
    public function linkGenreToMovie(int $movieId, int $genreId): void
    {
        $query = $this->pdo->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (:movie_id, :genre_id)");
        $query->bindParam(':movie_id', $movieId, $this->pdo::PARAM_INT);
        $query->bindParam(':genre_id', $genreId, $this->pdo::PARAM_INT);
        $query->execute();
    }

    public function unlinkAllGenresFromMovie(int $movieId): void
    {
        $query = $this->pdo->prepare("DELETE FROM movie_genre WHERE movie_id = :movie_id");
        $query->bindParam(':movie_id', $movieId, $this->pdo::PARAM_INT);
        $query->execute();
    }

    public function findAll(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM genre");
        $query->execute();
        $genres = $query->fetchAll($this->pdo::FETCH_ASSOC);

        $genresArray = [];

        if ($genres) {
            foreach ($genres as $genre) {
                $genresArray[] = Genre::createAndHydrate($genre);
            }
        }
        return $genresArray;
    }

}