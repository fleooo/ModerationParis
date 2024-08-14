<?php

namespace App\Controller\Users;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile', methods: ['GET'])]
    public function profile(Security $security): Response
    {
        $user = $security->getUser();
        
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/orders', name: 'user_orders', methods: ['GET'])]
    public function orders(OrdersRepository $ordersRepository, Security $security): Response
    {
        $user = $security->getUser();
        
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $orders = $ordersRepository->findBy(['users' => $user]);

        return $this->render('user/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/orders/{id}', name: 'user_order_detail', methods: ['GET'])]
    public function orderDetail(Orders $order, Security $security): Response
    {
        $user = $security->getUser();
        
        if (!$user || $order->getUsers() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette commande.');
        }

        return $this->render('user/order_detail.html.twig', [
            'order' => $order,
        ]);
    }
}
