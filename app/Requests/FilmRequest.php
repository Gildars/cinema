<?php

namespace App\Cinema\Requests;

use App\Cinema\Core\Session;
use App\Cinema\Exceptions\ValidationException;

/**
 * Class FilmRequest
 * @package App\Cinema\Requests
 */
class FilmRequest extends Request
{
    public function __construct(array $getParams)
    {
        parent::__construct($getParams);
        foreach ($this->params as $key => $value) {
            $this->params[$key] = $this->antiXSS->xss_clean($value);
        }
    }

    /**
     * @return bool
     */
    public function search(): bool
    {
        try {
            if (empty($this->params['by']) || empty($this->params['what'])) {
                throw new ValidationException('Напишите что нужно найти.');
            }
            if ($this->params['by'] !== 'actor' && $this->params['by'] !== 'film') {
                throw new ValidationException('Напишите что нужно найти.');
            }
            if (!strlen($this->params['what']) > 50) {
                throw new ValidationException('Поисковый запрос не может быть больше чем 50 символов.');
            }
            return true;
        } catch (ValidationException $exception) {
            Session::setFlash($exception->getMessage());
            return false;
        }
    }

    /**
     * @return bool
     */
    public function addFilmWithActors(): bool
    {
        try {
            if (
                empty($this->params['title']) || empty($this->params['release']) ||
                empty($this->params['format']) || empty($this->params['name'])
            ) {
                throw new ValidationException('Заполните все поля.');
            }
            if (!strlen($this->params['title']) > 50) {
                throw new ValidationException('Название не может быть больше 50 символов.');
            }
            if (!preg_match('/^[1-9][0-9]{3}$/', $this->params['release'])) {
                throw new ValidationException('Некоректный год.');
            }
            if ($this->params['release'] < 1850) {
                throw new ValidationException('Год выпуска не может быть меньше 1850 года.');
            }
            if ($this->params['release'] > date('Y')) {
                throw new ValidationException('Год выпуска не может перевышать текущий.');
            }
            if (!preg_match('/VHS|DVD|Blu-Ray/', $this->params['format'])) {
                throw new ValidationException('Некоректный формат.');
            }
            foreach ($this->params['name'] as $value) {
                if (strlen($value) > 50) {
                    throw new ValidationException('Имя не может быть больше чем 50 символов.');
                }
            }
            return true;
        } catch (ValidationException $exception) {
            Session::setFlash($exception->getMessage());
            return false;
        }
    }
}
