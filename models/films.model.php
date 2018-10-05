<?php

class FilmsModel extends Model
{
    public function validationAddFilm(array $data): bool
    {
        var_dump($data);
        if (isset($data['title']) && isset($data['release']) && isset($data['format']) && isset($data['name'])) {
            if (strlen($data['title']) < 50 && strlen($data['release']) == 4 && strlen($data['format']) < 12) {
                $data['title'] = $this->db->getConnection()->real_escape_string($this->clean($data['title']));
                $data['release'] = $this->db->getConnection()->real_escape_string($this->clean($data['release']));
                $data['format'] = $this->db->getConnection()->real_escape_string($this->clean($data['format']));
                foreach ($data['name'] as $key => $value) {
                    if (!strlen($value) > 50) return false;
                    $data['name'][$key] = $this->db->getConnection()->real_escape_string($this->clean($data['name'][$key]));
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getCountFilms(): int
    {
        $count = $this->db->query("
        SELECT COUNT(`id`) as count
  FROM films
        ");
        return (int)$count[0]['count'];
    }

    public function getFilmsLimit($limit, $order)
    {
        if ($order) {
            $sql = "
SELECT `id`, `title`, `release` 
FROM films 
ORDER BY title
LIMIT {$limit},21  
";
        } else {
            $sql = "
SELECT `id`, `title`, `release` 
FROM films 
LIMIT {$limit},21  
";
        }
        return $this->db->query($sql);
    }

    public function orderByDesc(): array
    {
        $sql = "SELECT `title` FROM films ORDER BY title DESC";
        return $this->db->query($sql);
    }

    public function addFilm(array $data)
    {

        $this->db->getConnection()->begin_transaction();
        $actorsId = [];
        $queryAddFilms = $this->db->query("
        INSERT INTO films
        SET 
        `title` = '{$data['title']}', 
        `release` = {$data['release']},
        `format` = '{$data['format']}'
        ");
        $filmId = $this->db->getConnection()->insert_id;
        if ($queryAddFilms == false) {
            $this->db->getConnection()->rollback();
            return false;
        }
        $i = 0;
        foreach ($data['name'] as $name) {
            $name = $this->db->getConnection()->real_escape_string($name);
            $queryAddActors = $this->db->query("
        INSERT INTO actors 
        SET
        `name` = '{$name}'
        ");
            array_push($actorsId, $this->db->getConnection()->insert_id);
            $i++;
            if ($queryAddActors == false) {
                $this->db->getConnection()->rollback();
                return false;
            }
        }
        foreach ($actorsId as $actorId) {
            $queryAddFilms = $this->db->query("
        INSERT INTO films_actors 
        SET
        `id_film` = '{$filmId}',
        `id_actor` = '{$actorId}'
        ");
            if ($queryAddFilms == false) {
                $this->db->getConnection()->rollback();
                return false;
            }
        }
        if ($this->db->getConnection()->commit() == true) {
            return $filmId;
        } else {
            return false;
        }
    }

    public function getFilmInfoById(int $id)
    {
        $id = $this->clean($id);
        $result = $this->db->query("
SELECT films.`id`, `title`, films.`release`, films.`format`,`name`, films_actors.`id_film`, films_actors.`id_actor`
FROM films
LEFT JOIN films_actors ON films_actors.`id_film` = films.`id`
LEFT JOIN actors ON films_actors.`id_actor` = actors.`id`
WHERE films.id = {$id}
");
        return ($result) ? $result : false;
    }

    public function removeFilmById(int $id): bool
    {
        $id = $this->clean($id);
        $result = $this->db->query("
DELETE
FROM films
WHERE films.id = {$id}
");
        return ($this->db->getConnection()->affected_rows) ? true : false;
    }

    public function searchFilmsByTitle(string $string, int $limit = 0, string $order = null)
    {
        $string = $this->db->getConnection()->real_escape_string($this->clean($string));
        $result = null;
        if ($order === null) {
            $result = $this->db->query("
SELECT `id`, `title`, `release` 
FROM films 
WHERE `title` LIKE '%{$string}%'
LIMIT 0,21
");
        }
        return ($result) ? $result : false;
    }

    public function searchFilmsByActor(string $string, int $limit = 0, string $order = null)
    {
        $string = $this->db->getConnection()->real_escape_string($this->clean($string));
        $result = null;
        if ($order == null) {
            $result = $this->db->query("
SELECT f.id, fa.`id_actor`, fa.`id_film`, f.`title`, f.`release`, a.`name`
FROM actors AS a
INNER JOIN films_actors AS fa ON fa.id_actor = a.id
INNER JOIN films AS f ON f.id = fa.id_film
WHERE `name` LIKE '%{$string}%' 
LIMIT 0,21
");
        }
        return ($result) ? $result : false;
    }
}