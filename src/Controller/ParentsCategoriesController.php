<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class ParentsCategoriesController extends AbstractController
{
    #[Route('page/{slug}', name: 'app_parents_categories')]
    public function index(string $slug, CategoriesRepository $categoriesRepository, LoggerInterface $logger): Response
    {
        $parentCategory = $categoriesRepository->findOneBy(['slug' => $slug]);

        if (!$parentCategory) {
            $logger->error('Parent category not found with slug: ' . $slug);
            return new Response('Parent category not found with slug: ' . $slug);
        }

        $logger->info('Parent category found: ' . $parentCategory->getName());

        $categories = $categoriesRepository->findChildrenByParent($parentCategory);

        if (empty($categories)) {
            $logger->error('No child categories found for the parent category: ' . $parentCategory->getName());
            return new Response('No child categories found for the parent category: ' . $parentCategory->getName());
        }

        foreach ($categories as $category) {
            $logger->info('Child category found: ' . $category->getName());
        }

        return $this->render('parents_categories/index.html.twig', [
            'parentCategory' => $parentCategory,
            'categories' => $categories,
        ]);
    }
}
