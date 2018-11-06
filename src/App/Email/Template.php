<?php

namespace App\Email;


class Template
{
    /**
     * @return string
     */
    public function getHeader(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getFooter(): string
    {
        return '';
    }
}