<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestHelper
{
    const URL = 'url';

    /**
     * @param Request $request
     * @return string
     */
    public function getUrlAsArray(Request $request): string
    {
        $data = json_decode($request->getContent(), true);
        if (array_key_exists(self::URL, $data) && filter_var(
                $data[self::URL],
                FILTER_VALIDATE_URL,
                FILTER_FLAG_HOST_REQUIRED
            )) {
            return $data[self::URL];
        }

        throw new BadRequestHttpException();
    }
}