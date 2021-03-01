<?php

namespace App\Cinema\Models;

use App\Cinema\Core\Model;
use PDO;

/**
 * Class FilmsModel
 * @package App\Cinema\Model
 */
class FilmsModel extends Model
{

    /**
     * @return int|false
     */
    public function getCountFilms(): int|false
    {
        return $this->db->query('SELECT COUNT(`id`) as count FROM films')->fetch(PDO::FETCH_OBJ)->count;
    }


    /**
     * @param int $limitOfStart
     * @param int $amount
     * @param false $order
     * @return array|false
     */
    public function getFilms(int $limitOfStart, int $amount, $order = false): array|false
    {
        if ($order) {
            $films = $this->db->prepare('
                SELECT `id`, `title`, `release` 
                FROM films 
                ORDER BY `title` + 0 ASC, `title` ASC
                LIMIT :limitOfStart, :amount
            ');
        } else {
            $films = $this->db->prepare('
                SELECT `id`, `title`, `release` 
                FROM films 
                LIMIT :limitOfStart, :amount
            ');
        }
        $films->execute(['limitOfStart' => $limitOfStart, 'amount' => $amount]);
        return $films->fetchAll();
    }


    /**
     * @param array $film
     * @return bool
     */
    public function addFilm(array $film): bool
    {
        $queryAddFilms = $this->db->prepare('
            INSERT INTO films
            SET 
            `title` = :title, 
            `release` = :YearRelease,
            `format` = :format
        ');
        return $queryAddFilms->execute([
            'title' => $film['title'],
            'YearRelease' => $film['release'],
            'format' => $film['format']
        ]);
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
            `name` = :ActorName
        ');
        return $queryAddActor->execute(['ActorName' => $fullName]);
    }


    /**
     * @param array $data
     * @return bool
     */
    public function addFilmWithActors(array $data): bool
    {
        $this->db->beginTransaction();
        try {
            $this->addFilm($data);

            $idFilm = $this->db->lastInsertId();
            $idActors = [];

            foreach ($data['name'] as $fullName) {
                $this->addActor($fullName);
                $idActors[] = $this->db->lastInsertId();
            }

            foreach ($idActors as $id) {
                $queryAddFilms = $this->db->prepare('
                    INSERT INTO films_actors 
                    SET
                    `id_film` = :idFilm,
                    `id_actor` = :idActor
                ');
                $queryAddFilms->execute(['idFilm' => $idFilm, 'idActor' => $id]);
            }
            $this->db->commit();
            return true;
        } catch (\PDOException $exception) {
            $this->db->rollBack();
            return false;
        }
    }


    /**
     * @param int $idFilm
     * @return array|false
     */
    public function getFilmInfoById(int $idFilm): array|false
    {
        $result = $this->db->prepare('
            SELECT films.`id`, `title`, films.`release`, films.`format`,`name`, films_actors.`id_film`, films_actors.`id_actor`
            FROM films
            LEFT JOIN films_actors ON films_actors.`id_film` = films.`id`
            LEFT JOIN actors ON films_actors.`id_actor` = actors.`id`
            WHERE films.id = :id
        ');
        $result->execute(['id' => $idFilm]);
        return $result->fetchAll();
    }

    /**
     * @param int $idFilm
     * @return bool
     */
    public function removeFilmById(int $idFilm): bool
    {
        $query = $this->db->prepare('
            DELETE
            FROM films
            WHERE films.id = :idFilm
        ');
        return $query->execute(['idFilm' => $idFilm]);
    }


    /**
     * @param string $searchText
     * @param int $limitOfStart
     * @param int $amount
     * @param bool $order
     * @return false|array
     */
    public function searchFilmsByTitle(string $searchText, int $limitOfStart, int $amount, $order = false): false|array
    {
        if ($order) {
            $result = $this->db->prepare('
                SELECT (SELECT COUNT(`id`) as count
                        FROM films
                        WHERE `title` LIKE :searchStr
                       ) as count,
                       `id`,
                       `title`,
                       `release`
                FROM films
                WHERE `title` LIKE :searchText
                ORDER BY `title` + 0 ASC, `title` ASC
                LIMIT :limitOfStart, :amount
            ');
        } else {
            $result = $this->db->prepare('
                SELECT (SELECT COUNT(`id`) as count
                        FROM films
                        WHERE `title` LIKE :searchStr
                       ) as count,
                       `id`,
                       `title`,
                       `release`
                FROM films
                WHERE `title` LIKE :searchText
                LIMIT :limitOfStart, :amount
            ');
        }
        $result->execute([
            'searchText' => '%' . $searchText . '%',
            'searchStr' => '%' . $searchText . '%',
            'limitOfStart' => $limitOfStart,
            'amount' => $amount
        ]);
        return $result->fetchAll();
    }

    /**
     * @param string $searchText
     * @param int $limitOfStart
     * @param int $amount
     * @param bool $order
     * @return false|array
     */
    public function searchFilmsByActor(string $searchText, int $limitOfStart, int $amount, $order = false): false|array
    {
        if ($order) {
            $result = $this->db->prepare('
                SELECT (SELECT COUNT(`id`) as count
                        FROM actors
                        WHERE `name` LIKE :searchStr
                       ) as count,
                       f.id,
                       fa.`id_actor`,
                       fa.`id_film`,
                       f.`title`,
                       f.`release`,
                       a.`name`
                FROM actors AS a
                         INNER JOIN films_actors AS fa ON fa.id_actor = a.id
                         INNER JOIN films AS f ON f.id = fa.id_film
                WHERE `name` LIKE :searchText
                ORDER BY `title` + 0 ASC, `title` ASC
                LIMIT :limitOfStart, :amount
            ');
        } else {
            $result = $this->db->prepare('
                SELECT (SELECT COUNT(`id`) as count
                        FROM actors
                        WHERE `name` LIKE :searchStr
                       ) as count,
                       f.id,
                       fa.`id_actor`,
                       fa.`id_film`,
                       f.`title`,
                       f.`release`,
                       a.`name`
                FROM actors AS a
                         INNER JOIN films_actors AS fa ON fa.id_actor = a.id
                         INNER JOIN films AS f ON f.id = fa.id_film
                WHERE `name` LIKE :searchText
                LIMIT :limitOfStart, :amount
            ');
        }
        $result->execute([
            'searchText' => '%' . $searchText . '%',
            'searchStr' => '%' . $searchText . '%',
            'limitOfStart' => $limitOfStart,
            'amount' => $amount
        ]);
        return $result->fetchAll();
    }
}
