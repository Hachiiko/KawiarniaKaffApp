<?php

namespace App\Controller;

use App\Entity\Factory\UserFactory;
use App\Form\Data\UserRegistrationData;
use App\Form\Type\UserRegistrationType;
use App\Security\Authenticator\SiteLoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/logowanie", name="site_login")
     *
     * @param  Security            $security
     * @param  AuthenticationUtils $authenticationUtils
     * @param  TranslatorInterface $translator
     *
     * @return Response
     */
    public function login(Security $security, AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        if (null !== $security->getUser()) {
            return $this->redirectToRoute('site_homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if (null !== $error) {
            $this->addFlash('error', $translator->trans($error->getMessageKey(), $error->getMessageData(), 'security'));
        }

        return $this->render('site/login.html.twig', [
            'last_username' => $lastUsername,
            'page_title' => 'Kawiarnia Kaff',
            'target_path' => $this->generateUrl('site_homepage'),
            'csrf_token_intention' => SiteLoginFormAuthenticator::LOGIN_CSRF_INTENTION,
            'username_parameter' => SiteLoginFormAuthenticator::LOGIN_USERNAME_PARAMETER_NAME,
            'password_parameter' => SiteLoginFormAuthenticator::LOGIN_PASSWORD_PARAMETER_NAME,
        ]);
    }

    /**
     * @Route("/rejestracja", name="site_register", methods={"GET", "POST"})
     *
     * @param  Request     $request
     * @param  UserFactory $userFactory
     *
     * @return Response
     */
    public function register(Request $request, UserFactory $userFactory): Response
    {
        $user = $this->getUser();

        if (null !== $user) {
            return $this->redirectToRoute('site_homepage');
        }

        $form = $this->createForm(UserRegistrationType::class, new UserRegistrationData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserRegistrationData $data */
            $data = $form->getData();

            $user = $userFactory->create(
                email: $data->email,
                plainPassword: $data->password,
                firstName: $data->firstName,
                lastName: $data->lastName,
                phone: $data->phone
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $token = new PostAuthenticationGuardToken($user, 'site', $user->getRoles());

            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_site', serialize($token));

            $this->addFlash('success', 'Pomyślnie utworzono konto.');

            return $this->redirectToRoute('site_homepage');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('site/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="site_logout")
     */
    public function logout(): void
    {
        // ...
    }
}
