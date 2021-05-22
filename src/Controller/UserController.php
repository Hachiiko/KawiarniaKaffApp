<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Data\UserEditData;
use App\Form\Type\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserController extends AbstractController
{
    /**
     * @Route("/moje-konto", name="site_account_edit")
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('Zaloguj się, aby edytować swój profil!');
        }

        $form = $this->createForm(UserEditType::class, UserEditData::createFromUser($user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserEditData $data */
            $data = $form->getData();

            $user->setFirstName($data->firstName);
            $user->setLastName($data->lastName);
            $user->setPhone($data->phone);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Pomyślnie zaktualizowano profil.');

            return $this->redirectToRoute('site_account_edit');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Niepoprawne dane formularza!');
        }

        return $this->render('site/account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}