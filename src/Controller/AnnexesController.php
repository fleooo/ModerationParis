<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnexesController extends AbstractController
{
    #[Route('/annexes', name: 'app_annexes')]
    public function index(): Response
    {
        return $this->render('annexes/index.html.twig', [
            'controller_name' => 'AnnexesController',
        ]);
    }

    #[Route('/A-Propos', name: 'app_a_propos')]
    public function aPropos(): Response
    {
        return $this->render('annexes/a_propos.html.twig', [
            'controller_name' => 'AnnexesController',
        ]);
    }

    #[Route('/MentionsLegales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('annexes/mentions_legales.html.twig', [
            'controller_name' => 'AnnexesController',
        ]);
    }
}