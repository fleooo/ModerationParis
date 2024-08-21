<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category, ProductsRepository $productsRepository, Request $request): Response
    {
        $products = $category->getProducts();
        $images = $category->getCategoryImages(); // Assurez-vous que cela récupère les images correctement

        return $this->render('categories/list.html.twig', [
            'category' => $category,
            'products' => $products,
            'images' => $images, // Passez les images au template
        ]);
    }
}