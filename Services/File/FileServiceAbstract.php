<?php

namespace App\Cinema\Services\File;

use App\Cinema\Exceptions\IncorrectFileTypeException;
use App\Cinema\Exceptions\InvalidArgumentException;
use App\Cinema\Exceptions\LargeFileSizeException;
use App\Cinema\Exceptions\UploadFileException;
use App\Cinema\Lib\Session;

/**
 * Class FileServiceAbstract
 * @package App\Cinema\Services\File
 */
abstract class FileServiceAbstract implements FileServiceInterface
{
    protected array $file;
    protected int $maxFileSize;
    protected array $allowedExt = ['txt'];

    public function __construct(array $file, int $maxFileSize)
    {
        $this->file = $file;
        $this->maxFileSize = $maxFileSize;
    }

    public function isValidFile(): bool
    {
        try {
            if (!$this->isFile()) {
                throw new InvalidArgumentException('На входе ожидался файл.');
            }
            if (!$this->isAllowedExt()) {
                throw new IncorrectFileTypeException('Ошибка загрузки файла. Файл должен быть формата .txt');
            }
            if (!$this->isAllowSize()) {
                throw new LargeFileSizeException('Слишком большой размер файла.');
            }
            if ($this->hasError()) {
                throw new UploadFileException($this->file['error']);
            }
        } catch (
            InvalidArgumentException | IncorrectFileTypeException | LargeFileSizeException | UploadFileException
            $exception
        ) {
            Session::setFlash($exception->getMessage());
            return false;
        }
        return true;
    }

    public function isFile(): bool
    {
        return file_exists($this->file['tmp_name']);
    }

    public function isAllowedExt(): bool
    {
        $filename = $this->file['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        return in_array($extension, $this->allowedExt);
    }

    public function isAllowSize(): bool
    {
        return $this->file['size'] < $this->maxFileSize;
    }

    public function isTxt(): bool
    {
        return $this->file['type'] === 'text/plain';
    }

    public function hasError(): bool
    {
        return $this->file['error'] !== UPLOAD_ERR_OK;
    }
}
