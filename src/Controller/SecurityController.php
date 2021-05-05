<?php

namespace App\Controller;

use App\Security\Authenticator\SiteLoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="site_login")
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
     * @Route("/logout", name="site_logout")
     */
    public function logout(): void
    {
        // ...
    }
}
