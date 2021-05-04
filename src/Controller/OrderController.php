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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class OrderController extends AbstractController
{
    private CartManager $cartManager;

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    public function create(Request $request): Response
    {
        $form = $this->createForm(OrderDetailsType::class, new OrderDetailsData);
        $form->handleRequest($request);

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedHttpException();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /** @var OrderDetailsData $data */

            $cart = $this->cartManager->get($user);

            try {
                $order = $this->cartManager->resolve(
                    cart: $cart,
                    firstName: $data->firstName,
                    lastName: $data->lastName,
                    phone: $data->phone,
                );
            } catch (EmptyCartException) {
                $this->addFlash('success', 'Nie można złożyć zamówienia z pustego koszyka.');

                return $this->render('', [
                    'form' => $form->createView(),
                ]);
            } catch (InsufficientStockException $exception) {
                $this->addFlash('success', 'Brak dostępności części produktów zawartych w koszyku.');

                return $this->render('', [
                    'form' => $form->createView(),
                    'insufficient_stock_exception' => $exception,
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Pomyślnie złożono zamówienie.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('', [
            'form' => $form->createView(),
        ]);
    }
}
