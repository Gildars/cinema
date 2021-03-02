<?php

namespace App\Cinema\Models;

use App\Cinema\Core\Model;
use App\Cinema\Core\Session;
use PDO;


/**
 * Class ActorsModel
 * @package App\Cinema\Models
 */
class ActorsModel extends Model
{
    public function getActorByName(string $fullName): array|false
    {
        $actor = $this->db->prepare('
            SELECT *
            FROM actors
            WHERE `name` = :actorName
        ');
        $actor->execute(['actorName' => $fullName]);
        return $actor->fetch();
    }

    /**
     * @param string $fullName
     * @return bool
     */
    public function addActor(string $fullName): bool
    {
        $queryAddActor = $this->db->prepare('
            INSERT INTO actors 
            SET
            `name` = :actorName
        ');
        return $queryAddActor->execute(['actorName' => $fullName]);
    }
}

