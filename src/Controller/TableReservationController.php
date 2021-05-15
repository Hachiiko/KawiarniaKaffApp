<?php

namespace App\Controller;

use App\Entity\Factory\TableReservationFactory;
use App\Entity\User;
use App\Form\Data\TableReservationDetailsData;
use App\Form\Type\TableReservationDetailsType;
use App\Service\CartManager;
use App\Service\TableReservationManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class TableReservationController extends AbstractController
{
    private CartManager $cartManager;

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    /**
     * @Route("/moje-konto/historia-rezerwacji", name="site_table_reservation_history")
     */
    public function history(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('Zaloguj się, wyświetlić historię swoich rezerwacji!');
        }

        return $this->render('site/account/table_reservations.html.twig', [
            'table_reservations' => $user->getTableReservations(),
        ]);
    }

    /**
     * @Route("/zarezerwuj-stolik", name="site_table_reservation")
     */
    public function create(Request $request, TableReservationManager $tableReservationManager, TableReservationFactory $tableReservationFactory): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('Zaloguj się, aby zarezerwować stolik!');
        }

        $availableHours = $tableReservationManager->getAvailableHours();

        $form = $this->createForm(TableReservationDetailsType::class, new TableReservationDetailsData, [
            'available_hours' => $availableHours,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TableReservationDetailsData $data */
            $data = $form->getData();

            $date = DateTime::createFromFormat('d/m/Y', $data->day);
            $date->setTime($data->hour, 0);

            $tableReservation = $tableReservationFactory->create(
                owner: $user,
                table: $tableReservationManager->getNonOccupiedTableForHour($date),
                firstName: $data->firstName,
                lastName: $data->lastName,
                phone: $data->phone,
                dateFrom: $date,
                dateTo: (clone $date)->modify('+1 hour')
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tableReservation);
            $entityManager->flush();

            $this->addFlash('success', 'Pomyślnie złożono rezerwację.');

            return $this->redirectToRoute('site_homepage');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Niepoprawne dane formularza!');
        }

        return $this->render('site/table_reservation.html.twig', [
            'form' => $form->createView(),
            'available_hours' => $availableHours,
        ]);
    }
}
