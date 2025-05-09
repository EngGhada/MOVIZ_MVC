<?php

namespace App\Entity;

use App\Tools\StringTools;


class Entity
{

    public static function createAndHydrate(array $data): static
    {
        // Ici static fait référence à la classe de l'enfant, alors que self fait référence à la classe courante
        $entity = new static();
        $entity->hydrate($data);
        return $entity;
    }

    public function hydrate(array $data) // On hydrate l'objet avec les données du tableau
                                         // On utilise le tableau associatif pour hydrater l'objet
    {
        if (count($data) > 0) {
            // On parcourt le tableau de données
            foreach ($data as $key => $value) {
                // Pour chaque donnée, on appel le setter
                $methodName = 'set' . StringTools::toPascalCase($key);
                if (method_exists($this, $methodName)) {
                    if ($key == 'created_at') {
                        $value = new \DateTime($value); // Convertir la date en objet DateTime 
                    } else if ($key == 'release_date') {
                        $value = new \DateTime($value); // Convertir la date en objet DateTime
                    } else if ($key == 'duration') {
                        $value = new \DateTime($value); // Convertir la date en objet DateTime
                    }
                    $this->{$methodName}($value);
                }
            }
        }
    }
}
