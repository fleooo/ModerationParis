<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvecModerationController extends AbstractController
{
    #[Route('/AvecModeration', name: 'app_avec_moderation')]
    public function index(): Response
    {
        return $this->render('avec_moderation/AvecModeration.html.twig', [
            'controller_name' => 'AvecModerationController',
        ]);
    }
}
