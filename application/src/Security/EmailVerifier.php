<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

readonly class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private EntityManagerInterface     $entityManager,
        private MailerInterface            $mailer,
    )
    {
    }

    public function sendEmailConfirmation(UserInterface $user, string $verify_email_route): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('info@cleanup.be', 'Cleanup'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');


        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verify_email_route,
            $user->getId(),
            $user->getEmail()
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
