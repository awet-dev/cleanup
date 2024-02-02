<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'public_')]
class PublicController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function home(): Response
    {
        return $this->render('public/index.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // todo send message to admin via email.
        }

        return $this->render('public/contact.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
