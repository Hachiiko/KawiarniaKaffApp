<?php

namespace App\Controller\Admin;

use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     *
     * @param  AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'translation_domain' => 'admin',
            'page_title' => 'Kawiarnia Kaff',
            'target_path' => $this->generateUrl('admin'),
            'csrf_token_intention' => LoginFormAuthenticator::LOGIN_CSRF_INTENTION,
            'username_parameter' => LoginFormAuthenticator::LOGIN_USERNAME_PARAMETER_NAME,
            'password_parameter' => LoginFormAuthenticator::LOGIN_PASSWORD_PARAMETER_NAME,
        ]);
    }

    /**
     * @Route("/logout", name="admin_logout")
     */
    public function logout(): void
    {
        // ...
    }
}
