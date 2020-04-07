<?php

namespace App\Services;

class ShortifyService
{
    const PERMITTED_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCSEFGHIKLMNOPRQRSTUVWXYZ';

    public function getShuffledString(): string
    {
        return substr(str_shuffle(self::PERMITTED_CHARS), 0, 7);
    }
}