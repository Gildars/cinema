<?php


class Model
{

    protected $db;

    public function __construct()
    {
        $this->db = App::$db;
    }

    function clean($value = "")
    {
        $value = XSSCleaner::xss_clean($value);
        $value = trim($value);
        $value = stripslashes($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value);
        $value = $this->db->getConnection()->real_escape_string($value);
        return $value;
    }

    function check_length($value = '', $min, $max)
    {
        $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
        return !$result;
    }
}