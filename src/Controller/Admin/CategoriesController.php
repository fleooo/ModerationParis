<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\CategoryImage;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);

        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Categories();

        $form = $this->createForm(CategoriesFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle images
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $folder = 'categories';
                $filename = $pictureService->add($image, $folder, 300, 300);

                $categoryImage = new CategoryImage();
                $categoryImage->setName($filename);
                $category->addCategoryImage($categoryImage);
            }

            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category added successfully');
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Categories $category, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CategoriesFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle images
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $folder = 'categories';
                $filename = $pictureService->add($image, $folder, 300, 300);

                $categoryImage = new CategoryImage();
                $categoryImage->setName($filename);
                $category->addCategoryImage($categoryImage);
            }

            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category updated successfully');
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Categories $category, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Remove associated images
        foreach ($category->getCategoryImages() as $image) {
            $em->remove($image);
        }

        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Category deleted successfully');
        return $this->redirectToRoute('admin_categories_index');
    }

    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(CategoryImage $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            $filename = $image->getName();
            if ($pictureService->delete($filename, 'categories', 300, 300)) {
                $em->remove($image);
                $em->flush();
                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Error deleting image'], 400);
        }

        return new JsonResponse(['error' => 'Invalid CSRF token'], 400);
    }
}