<?php

namespace App\Repository;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;


class ReviewRepository extends Repository
{
    private $collection;

    public function __construct()
    {
        parent::__construct(); // Calls our upgraded constructor

        $this->collection = $this->mongo
            ->selectDatabase('moviz-mvc')
            ->selectCollection('reviews');
    }

    public function insertReview(array $review): void
    {
        $this->collection->insertOne($review);
    }

    public function updateReview(string $id, array $review): void
    {
        $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $review]
        );
    }

    public function deleteReview(string $id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }


    public function findById(string $id): ?array
   {
        if (!preg_match('/^[a-f\d]{24}$/i', $id)) {
            return null; // Invalid ObjectId format
        }

        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $doc->getArrayCopy() : null;  // if($doc){ converts the BSONDocument into a standard PHP associative array }else {null}
    }

    
    public function findByMovieId(int $movieId): array
    {
        return $this->collection->find(['movie_id' => $movieId , 'approved'=>true])->toArray();
    }

    public function findByUserId(int $userId): array
    {
        return $this->collection->find(['user_id' => $userId])->toArray();
    }


}
