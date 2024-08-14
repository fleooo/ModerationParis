<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $panier = $session->get('panier', []);

        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            $product = $productsRepository->find($id);

            if ($product) {
                $data[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('cart/index.html.twig', compact('data', 'total'));
    }

    #[Route('/add', name: 'add_ajax', methods: ['POST'])]
    public function addAjax(Request $request, ProductsRepository $productsRepository, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        // Validation des données reçues
        if (!$id || $quantity < 1) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides.'], 400);
        }

        $product = $productsRepository->find($id);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé.'], 404);
        }

        $panier = $session->get('panier', []);
        if (isset($panier[$id])) {
            $panier[$id] += $quantity;
        } else {
            $panier[$id] = $quantity;
        }
        $session->set('panier', $panier);

        return new JsonResponse(['success' => true, 'message' => 'Produit ajouté au panier !']);
    }

    #[Route('/remove', name: 'remove_ajax', methods: ['POST'])]
    public function removeAjax(Request $request, ProductsRepository $productsRepository, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;

        // Validation des données reçues
        if (!$id) {
            return new JsonResponse(['success' => false, 'message' => 'Données invalides.'], 400);
        }

        $product = $productsRepository->find($id);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé.'], 404);
        }

        $panier = $session->get('panier', []);
        if (isset($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
            $session->set('panier', $panier);

            return new JsonResponse(['success' => true, 'message' => 'Quantité de produit diminuée.']);
        }

        return new JsonResponse(['success' => false, 'message' => 'Produit non présent dans le panier.'], 400);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Products $product, SessionInterface $session): RedirectResponse
    {
        $id = $product->getId();
        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            unset($panier[$id]);
            $session->set('panier', $panier);
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session): RedirectResponse
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/update-address', name: 'update_address', methods: ['POST'])]
    public function updateAddress(Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();

        // Récupération des nouvelles valeurs du formulaire
        $newAddress = $request->request->get('address');
        $newZipcode = $request->request->get('zipcode');
        $newCity = $request->request->get('city');

        // Validation des données si nécessaire
        if ($newAddress && $newZipcode && $newCity) {
            $user->setAddress($newAddress);
            $user->setZipcode($newZipcode);
            $user->setCity($newCity);

            // Enregistrer les modifications en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('cart_index');
        }

        return new JsonResponse(['success' => false, 'message' => 'Données invalides.'], 400);
    }
}