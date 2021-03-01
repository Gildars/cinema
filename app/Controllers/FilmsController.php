<?php

namespace App\Cinema\Controllers;

use App\Cinema\Core\Controller;
use App\Cinema\Core\Pagination;
use App\Cinema\Core\Router;
use App\Cinema\Core\Session;
use App\Cinema\Models\FilmsModel;
use App\Cinema\Requests\FilmRequest;
use App\Cinema\Services\File\FileTxtService;

/**
 * Class FilmsController
 * @package App\Cinema\Controller
 */
class FilmsController extends Controller
{
    /**
     * @var \App\Cinema\Models\FilmsModel
     */
    private FilmsModel $filmsModel;

    /**
     * Controller constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->filmsModel = new FilmsModel();
    }

    public function index()
    {
        $this->pageTitle = 'Главная';
        $pageNumber = !empty($_GET['page']) ? (int) $_GET['page'] : 1;
        $order = !empty($_GET['order']);
        $filmsCount = $this->filmsModel->getCountFilms();
        $recordsPerPage = 21;
        $limitOfStart = ($pageNumber * $recordsPerPage) - $recordsPerPage;

        $this->data['currentPage'] = $pageNumber;
        $this->data['films'] = $this->filmsModel->getFilms($limitOfStart, $recordsPerPage, $order) ?? [];
        $this->data['button'] = new Pagination([
            'itemsCount' => $filmsCount,
            'itemsPerPage' => 21,
            'currentPage' => $pageNumber
        ]);
    }

    public function search()
    {
        $this->pageTitle = 'Поиск';
        $pageNumber = !empty($_GET['page']) ? $_GET['page'] : 1;
        $recordsPerPage = 21;
        $filmRequest = new FilmRequest($_GET);
        if ($filmRequest->search()) {
            $order = !empty($_GET['order']);
            $searchString = $_GET['what'];
            $searchBy = $_GET['by'];

            $limitOfStart = ($pageNumber * $recordsPerPage) - $recordsPerPage;

            $films = match ($searchBy) {
                'actor' => $this->filmsModel->searchFilmsByActor($searchString, $limitOfStart, $recordsPerPage, $order),
                'film' => $this->filmsModel->searchFilmsByTitle($searchString, $limitOfStart, $recordsPerPage, $order)
            };
        }

        $this->data['currentPage'] = $pageNumber;
        $this->data['films'] = $films ?? [];
        $this->data['button'] = new Pagination([
            'itemsCount' => $films[0]['count'] ?? 0,
            'itemsPerPage' => $recordsPerPage,
            'currentPage' => $pageNumber
        ]);
    }

    public function add()
    {
        $this->pageTitle = 'Добавить фильм';

        if (!empty($_POST)) {
            $filmRequest = new FilmRequest($_POST);
            if ($filmRequest->addFilmWithActors() && $this->filmsModel->addFilmWithActors($_POST)) {
                Session::setFlash('Фильм успешно добавлен.');
            }
        }
    }

    public function view()
    {
        if (empty($_GET['id'])) {
            Router::redirect('/');
        }

        if ($film = $this->filmsModel->getFilmInfoById((int)$_GET['id'])) {
            $this->data['film'] = $film;
            $this->pageTitle = $film[0]['title'];
        } else {
            $this->pageTitle = 'Фильм не найден.';
            Session::setFlash('Фильм не найден.');
        }
    }

    public function import()
    {
        $this->pageTitle = 'Импорт фильмов';

        if (isset($_FILES['file'])) {
            $fileTxtService = new FileTxtService($_FILES['file'], 170000);
            if ($fileTxtService->isValidFile()) {
                $fileTxtService->parseFilms();
            }
        }
    }

    public function remove()
    {
        $this->pageTitle = 'Удалить фильм';
        $idFilm = $_GET['id'] ?? false;
        if ($idFilm) {
            if ($this->filmsModel->removeFilmById($idFilm)) {
                Session::setFlash('Фильм удален.');
            } else {
                Session::setFlash('Фильм не найден');
            }
        }
    }
}
