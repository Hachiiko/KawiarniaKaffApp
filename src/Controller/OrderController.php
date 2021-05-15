<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OrderController extends AbstractController
{
    private CartManager $cartManager;

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    /**
     * @Route("/moje-konto/historia-zamowien", name="site_order_history")
     *
     * @return Response
     */
    public function history(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('Zaloguj się, wyświetlić historię swoich zamówień!');
        }

        return $this->render('site/account/orders.html.twig', [
            'orders' => $user->getOrders(),
        ]);
    }
}
