<?php

namespace App\Kernel\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof HttpExceptionInterface || null !== $event->getResponse()) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'result' => false,
            'errors' => [
                $exception->getMessage(),
            ]
        ], $exception->getStatusCode()));
    }
}