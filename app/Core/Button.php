<?php

namespace App\Cinema\Core;

/**
 * Class Button
 * @package App\Cinema\Core
 */
class Button
{
    public int $page;
    public string|int $text;
    public bool $isActive;

    /**
     * Button constructor.
     * @param int $page
     * @param bool $isActive
     * @param string|null $text
     */
    public function __construct(int $page, $isActive = true, string $text = null)
    {
        $this->page = $page;
        $this->text = is_null($text) ? $page : $text;
        $this->isActive = $isActive;
    }

    public function activate()
    {
        $this->isActive = true;
    }

    public function deactivate()
    {
        $this->isActive = false;
    }
}
