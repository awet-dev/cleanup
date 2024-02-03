<?php

namespace App\Service;

use LogicException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;

readonly class HelperService
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function addFlash(string $type, mixed $message): void
    {
        try {
            $session = $this->container->get('request_stack')->getSession();
        } catch (SessionNotFoundException $e) {
            throw new LogicException('You cannot use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".', 0, $e);
        }

        if (!$session instanceof FlashBagAwareSessionInterface) {
            throw new LogicException(sprintf('You cannot use the addFlash method because class "%s" doesn\'t implement "%s".', get_debug_type($session), FlashBagAwareSessionInterface::class));
        }

        $session->getFlashBag()->add($type, $message);
    }
}