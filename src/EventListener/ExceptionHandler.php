<?php

namespace App\EventListener;

use App\Entity\HyperMidiResponse;
use App\Helper\EntityFactoryException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityFactoryException', 1],
                ['handle404Exception', 0],
            ],
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if ($event->getException() instanceof NotFoundHttpException) {
            $response = HyperMidiResponse::fromError($event->getException())->getResponse();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        }
    }

    public function handleEntityFactoryException(ExceptionEvent $event)
    {
        if ($event->getException() instanceof EntityFactoryException) {
            $response = HyperMidiResponse::fromError($event->getException())->getResponse();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }
}