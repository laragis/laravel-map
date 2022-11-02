<?php

namespace TungTT\LaravelMap\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PageExpiredException extends HttpException
{
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($code ?: 419,$message ?? 'Page expired.',  $previous);
    }
}