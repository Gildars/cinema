<?php

class FilmsController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new FilmsModel();
    }

    public function index()
    {
        $page = !empty($this->params[0]) ? $this->params[0] : 1;
        $order = (isset($_GET['order'])) ? true : false;
        $this->data['currentPage'] = $page;
        $filmsCount = $this->model->getCountFilms();
        if (empty($this->params[0])) { // Если не передан параметр
            $films = $this->model->getFilmsLimit(0, $order);
            $this->data['button'] = new Pagination(array(
                'itemsCount' => $filmsCount,
                'itemsPerPage' => 21,
                'currentPage' => $filmsCount
            ));
            $this->data['films'] = $films;
        } elseif (is_numeric($this->params[0])) { // Если переданный параметр является нормером страницы
            if ($this->params[0] == 1) {
                $limit = 0;
                $this->data['films'] = $this->model->getFilmsLimit($limit, $order);
            } else {
                $limit = ($this->params[0] * 21) - 21;
                $this->data['films'] = $this->model->getFilmsLimit($limit, $order);
            }
        }
        $this->data['button'] = new Pagination(array(
            'itemsCount' => $filmsCount,
            'itemsPerPage' => 21,
            'currentPage' => $page
        ));
    }

    public function search()
    {
        $films = null;
        if (!isset($_GET['s']) || empty($_GET['s']) || !isset($_GET['t']) || empty($_GET['t'])) {
            Session::setFlash('Некоректный поисковый запрос');
        } else {
            $page = isset($params[0]) ? (int)($this->params[0]) : 1;
            $searchString = $_GET['s'];
            $type = (isset($_GET['t']) && $_GET['t'] == 'actor' || $_GET['t'] === 'film') ? $_GET['t'] : false;

            $this->data['currentPage'] = $page;
            if (empty($this->params[0])) { // Если не передан параметр
                if ($type == 'actor') {
                    $films = $this->model->searchFilmsByActor($searchString, 0);
                } elseif ($type == 'film') {
                    $films = $this->model->searchFilmsByTitle($searchString, 0);
                }
            } elseif (!empty($this->params[0]) && is_numeric($this->params[0])) { // Если переданный параметр является цифрой
                if ($this->params[0] == 1) {
                    $limit = 0;
                    $films = $this->model->searchFilmsByString($searchString, $limit);
                } else {
                    $limit = ($this->params[0] * 21) - 21;
                    $this->data['films'] = $this->model->searchFilmsByString($searchString, $limit);
                }
            }
        }

        $this->data['films'] = ($films !== false) ? $films : null;
        $this->data['button'] = new Pagination(array(
            'itemsCount' => count($films),
            'itemsPerPage' => 21,
            'currentPage' => isset($page) ? $page : 0
        ));
    }

    public function add()
    {
        if (!empty($_POST)) {
            $response = [];
            $data = $this->model->validationAddFilm($_POST);
            if ($data !== false) {
                $film = $this->model->addFilm($data);
                if ($film == true) {
                    Router::redirect("/films/view/$film/");
                }
            } else {
                Session::setFlash('Заполните все поля для ввода данных.');
            }
        }
    }

    public function view()
    {
        $params = App::getRouter()->getParams();
        if ($params[0]) {
            $film = $this->model->getFilmInfoById($params[0]);
            if ($film) {
                $this->data['film'] = $film;
            } else {
                Session::setFlash('Фильм не найден.');
            }
        } else {
            Router::redirect('/');
        }

    }

    public function import()
    {

        if (isset($_FILES['file'])) {
            $allowedExts = array("txt");
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            $films = null;
            if ((($_FILES["file"]["type"] == "text/plain")
                && ($_FILES["file"]["size"] < 170000)
                && in_array($extension, $allowedExts))) {
                if ($_FILES["file"]["error"] > 0) {
                    Session::setFlash("Ошибка загрузки файла. Code: " . $_FILES["file"]["error"]);
                } else {

                    $file = file_get_contents($_FILES['file']['tmp_name']);
                    $data = [];
                    $film = [
                        'title' => '',
                        'release' => '',
                        'format' => '',
                        'name' => []
                    ];
                    $matchesLines = [];
                    if (preg_match_all("/^(Title|Release\sYear|Format|Stars):\h*(.*)/m", $file, $matchesLines)) {
                        $fieldsNames = $matchesLines[1];
                        $fieldsValues = $matchesLines[2];
                        $lastIndex = 0;
                        foreach ($fieldsNames as $index1 => $value1) {
                            foreach ($fieldsValues as $index2 => $value2) {
                                if ($index2 == $lastIndex) {
                                    continue;
                                }
                                if (!empty($film['title']) && !empty($film['release']) &&
                                    !empty($film['format']) && !empty($film['name'])) {
                                    array_push($data, $film);
                                    $this->model->addFilm($film);
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
                                        preg_match_all("/[A-zА-яё\]+\s[a-zа-яё]+(?=[,]*)/",
                                            $value2, $firstNameAndSurname);
                                        foreach ($firstNameAndSurname[0] as $name) {
                                            $name = trim($name);
                                            array_push($film['name'], $name);
                                        }
                                    } else {
                                        $film[$value1] = $value2;
                                    }
                                    $lastIndex = $index2;
                                    break;
                                }
                            }
                        }
                        Session::setFlash('Фильмы успешно импортированы');
                    }
                }
            } else {
                Session::setFlash('Ошибка загрузки файла. Файл должен быть формата .txt');
            }
        }
    }

    public function remove()
    {
        $idFilm = $this->params[0];
        if ($idFilm) {
            if ($this->model->removeFilmById($idFilm)) {
                Session::setFlash('Фильм удален');
            } else {
                Session::setFlash('Фильм не найден');
            }
        }
    }
}
