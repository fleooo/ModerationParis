<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AProposController extends AbstractController
{
    #[Route('/A-Propos', name: 'app_a_propos')]
    public function index(): Response
    {
        return $this->render('a_propos/AProposController.html.twig', [
            'controller_name' => 'AProposController',
        ]);
    }
}
