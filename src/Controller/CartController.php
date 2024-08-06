<?php
namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $panier = $session->get('panier', []);

        // On initialise des variables
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

        if ($id) {
            $product = $productsRepository->find($id);
            if ($product) {
                $panier = $session->get('panier', []);
                if (empty($panier[$id])) {
                    $panier[$id] = 1;
                } else {
                    $panier[$id]++;
                }
                $session->set('panier', $panier);

                return new JsonResponse(['success' => true, 'message' => 'Produit ajouté au panier !']);
            }
        }

        return new JsonResponse(['success' => false, 'message' => 'Erreur lors de l\'ajout au panier.'], 400);
    }

    #[Route('/remove', name: 'remove_ajax', methods: ['POST'])]
    public function removeAjax(Request $request, ProductsRepository $productsRepository, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;

        if ($id) {
            $product = $productsRepository->find($id);
            if ($product) {
                $panier = $session->get('panier', []);
                if (!empty($panier[$id])) {
                    if ($panier[$id] > 1) {
                        $panier[$id]--;
                    } else {
                        unset($panier[$id]);
                    }
                    $session->set('panier', $panier);

                    return new JsonResponse(['success' => true, 'message' => 'Quantité de produit diminuée.']);
                }
            }
        }

        return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la suppression du produit.'], 400);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Products $product, SessionInterface $session)
    {
        // On récupère l'id du produit
        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        
        // On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }
}