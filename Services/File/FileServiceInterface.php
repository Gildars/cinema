<?php

namespace App\Cinema\Services\File;


/**
 * Interface FileServiceInterface
 * @package App\Cinema\Services\File
 */
interface FileServiceInterface
{
    public function isFile(): bool;

    public function isAllowedExt(): bool;

    public function isAllowSize(): bool;

    public function isTxt(): bool;

    public function isValidFile(): bool;

    public function hasError(): bool;

    public function __construct(array $file, int $maxFileSize);
}
