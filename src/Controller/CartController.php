<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\EmptyCartException;
use App\Exception\InsufficientStockException;
use App\Form\Data\OrderDetailsData;
use App\Form\Type\OrderDetailsType;
use App\Repository\CartProductVariantRepository;
use App\Repository\ProductVariantRepository;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CartController extends AbstractController
{
    private CartManager $cartManager;

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    /**
     * @Route("/koszyk", name="site_cart_resolve", methods={"GET", "POST"})
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function resolve(Request $request): Response
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

    /**
     * @Route("/koszyk/dodaj-produkt", name="site_cart_add_product", methods={"POST"})
     */
    public function addProduct(Request $request, ProductVariantRepository $productVariantRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json([
                'message' => 'Wystąpił błąd autoryzacji. Zaloguj się ponownie.',
            ], 401);
        }

        $requestedProductVariantId = (int) $request->get('productVariantId');
        $requestedQuantity = (int) $request->get('quantity');

        if ($requestedQuantity < 1) {
            return $this->json([
                'message' => 'Nie można dodać produktu do koszyka o ilości < 1.',
            ], 400);
        }

        $productVariant = $productVariantRepository->find($requestedProductVariantId);

        if (null === $productVariant) {
            return $this->json([
                'message' => 'Nie odnaleziono produktu, który próbujesz dodać do koszyka.',
            ], 400);
        }

        if (null !== $productVariant->getQuantity() && $productVariant->getQuantity() < $requestedQuantity) {
            return $this->json([
                'message' => 'Niewystarczająca ilość produktu na stanie.',
            ], 400);
        }

        $cart = $this->cartManager->get($user);

        $this->cartManager->addProductVariant($cart, $productVariant, $requestedQuantity);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->json([
            'message' => 'Pomyślnie dodano produkt do koszyka.',
        ]);
    }

    /**
     * @Route("/koszyk/usun-produkt", name="site_cart_remove_product", methods={"POST"})
     */
    public function removeProduct(Request $request, CartProductVariantRepository $cartProductVariantRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json([
                'message' => 'Wystąpił błąd autoryzacji. Zaloguj się ponownie.',
            ], 401);
        }

        $requestedCartProductVariantId = (int) $request->get('cartProductVariantId');

        $cartProductVariant = $cartProductVariantRepository->find($requestedCartProductVariantId);

        if (null === $cartProductVariant) {
            return $this->json([
                'message' => 'Nie odnaleziono produktu, który próbujesz usunąć z koszyka.',
            ], 400);
        }

        $cart = $this->cartManager->get($user);
        $cart->removeCartProductVariant($cartProductVariant);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cartProductVariant);
        $entityManager->flush();

        $this->addFlash('success', 'Pomyślnie usunięto produkt z koszyka.');

        return $this->json([]);
    }
}