<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search_results')]
    public function search(Request $request, ProductsRepository $productsRepository): Response
    {
        $query = $request->query->get('query', '');
        $results = $productsRepository->searchProducts($query);

        dump($results);

        return $this->render('search/results.html.twig', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}
