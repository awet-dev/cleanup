<?php

namespace App\EventListener;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Service\HelperService;
use Doctrine\ORM\Mapping\PostPersist;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\PreUpdate;

final readonly class UserListener
{
    public function __construct(private EmailVerifier $emailVerifier, private HelperService $helper)
    {
    }

    #[PostPersist]
    public function postPersistHandler(User $user, PostPersistEventArgs $event): void
    {
        $this->emailVerifier->sendEmailConfirmation($user, 'app_verify_email');
        $this->helper->addFlash('info', 'You have created new account, we have send you email verification');
    }

    #[PreUpdate]
    public function preUpdateHandler(User $user, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('email')) {
            $this->emailVerifier->sendEmailConfirmation($user, 'profile_verify_email');
            $this->helper->addFlash('warning', 'Due to email update, we have send you email verification');
        }
    }
}
