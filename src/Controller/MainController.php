<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CategoriesRepository $categoriesRepository, ProductsRepository $productRepository): Response
    {
        // Récupération des catégories et des produits
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);
        $products = $productRepository->findBy([], ['id' => 'desc'], 12); // Limite à 12 produits récents

        return $this->render('main/main.html.twig', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}