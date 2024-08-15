<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SansModerationController extends AbstractController
{
    #[Route('/SansModeration', name: 'app_sans_moderation')]
    public function index(): Response
    {
        return $this->render('sans_moderation/SansModeration.html.twig', [
            'controller_name' => 'SansModerationController',
        ]);
    }
}
