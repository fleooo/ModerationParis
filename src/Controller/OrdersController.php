<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if (empty($panier)) {
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('app_main');
        }

        // Le panier n'est pas vide, on crée la commande
        $order = new Orders();
        $order->setUsers($this->getUser());
        $order->setReference(uniqid());

        $total = 0;

        // On parcourt le panier pour créer les détails de commande
        foreach ($panier as $item => $quantity) {
            // On va chercher le produit
            $product = $productsRepository->find($item);

            if (!$product) {
                $this->addFlash('error', 'Produit introuvable: ' . $item);
                return $this->redirectToRoute('app_main');
            }

            if ($quantity <= 0) {
                $this->addFlash('error', 'Quantité invalide pour le produit: ' . $product->getName());
                return $this->redirectToRoute('app_main');
            }

            $orderDetails = new OrdersDetails();
            $price = $product->getPrice();

            // On crée le détail de commande
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);
        }

        

        // On persiste et on flush
        try {
            $em->persist($order);
            $em->flush();
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
            return $this->redirectToRoute('app_main');
        }

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('app_main');
    }
}