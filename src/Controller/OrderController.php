<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\EmptyCartException;
use App\Exception\InsufficientStockException;
use App\Form\Data\OrderDetailsData;
use App\Form\Type\OrderDetailsType;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('site/orders.html.twig', [
            'orders' => $user->getOrders(),
        ]);
    }

    /**
     * @Route("/podsumowanie-koszyka", name="site_order_create", methods={"GET", "POST"})
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(OrderDetailsType::class, new OrderDetailsData);
        $form->handleRequest($request);

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('Zaloguj się, aby złożyć zamówienie!');
        }

        $cart = $this->cartManager->get($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /** @var OrderDetailsData $data */

            try {
                $order = $this->cartManager->resolve(
                    cart: $cart,
                    firstName: $data->firstName,
                    lastName: $data->lastName,
                    phone: $data->phone,
                );
            } catch (EmptyCartException) {
                $this->addFlash('warning', 'Nie można złożyć zamówienia z pustego koszyka.');

                return $this->render('', [
                    'form' => $form->createView(),
                ]);
            } catch (InsufficientStockException $exception) {
                $this->addFlash('warning', 'Brak dostępności części produktów zawartych w koszyku.');

                return $this->render('', [
                    'form' => $form->createView(),
                    'insufficient_stock_exception' => $exception,
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Pomyślnie złożono zamówienie.');

            return $this->redirectToRoute('site_homepage');
        }

        return $this->render('site/cart.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart,
        ]);
    }
}
