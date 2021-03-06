<?php

namespace App\Cinema\Services\File;

use App\Cinema\Core\Session;
use App\Cinema\Models\FilmsModel;
use App\Cinema\Requests\FilmRequest;
use voku\helper\AntiXSS;

/**
 * Class FileTxtService
 * @package App\Cinema\Services\File
 */
class FileTxtService extends FileServiceAbstract
{
    /**
     * @var FilmsModel
     */
    private FilmsModel $filmsModel;

    public function __construct(array $file, int $maxFileSize)
    {
        parent::__construct($file, $maxFileSize);
        $this->filmsModel = new FilmsModel();
    }

    public function parseFilms()
    {
        $txtFile = file_get_contents($this->file['tmp_name']);
        $foundData = [];
        $film = [
            'title' => '',
            'release' => '',
            'format' => '',
            'name' => []
        ];
        $matchesLines = [];
        $antiXSS = new AntiXSS();

        if (preg_match_all('/^(Title|Release\sYear|Format|Stars):\h*(.*)/m', $txtFile, $matchesLines)) {
            $fieldsNames = $antiXSS->xss_clean($matchesLines[1]);
            $fieldsValues = $antiXSS->xss_clean($matchesLines[2]);
            $lastIndex = 0;

            foreach ($fieldsNames as $index1 => $value1) {
                foreach ($fieldsValues as $index2 => $value2) {
                    if ($index2 == $lastIndex) {
                        continue;
                    }
                    if (
                        !empty($film['title']) && !empty($film['release']) &&
                        !empty($film['format']) && !empty($film['name'])
                    ) {
                        array_push($foundData, $film);
                        $this->filmsModel->addFilmWithActors($film);
                        $film = [
                            'title' => '',
                            'release' => '',
                            'format' => '',
                            'name' => []
                        ];
                    }
                    if ($index1 === $index2) { // заполняем масив film
                        $value1 = strtolower($value1);
                        if ($value1 == 'release year') {
                            $film['release'] = $value2;
                        } elseif ($value1 == 'stars') {
                            $firstNameAndSurname = null;
                            preg_match_all(
                                '/[A-zА-яё\]+\s[a-zа-яё]+(?=[,]*)/',
                                $value2,
                                $firstNameAndSurname
                            );
                            foreach ($firstNameAndSurname[0] as $name) {
                                $name = trim($name);
                                array_push($film['name'], $name);
                            }
                        } else {
                            $film[$value1] = $value2;
                        }
                        $filmRequest = new FilmRequest($film);
                        $filmRequest->addFilmWithActors();
                        $lastIndex = $index2;
                        break;
                    }
                }
            }
            Session::setFlash('Фильмы успешно импортированы');
        }
    }
}
