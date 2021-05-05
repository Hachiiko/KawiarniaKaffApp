<?php

namespace App\Controller\Admin;

use App\Security\Authenticator\AdminLoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     *
     * @param  Security            $security
     * @param  AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(Security $security, AuthenticationUtils $authenticationUtils): Response
    {
        if (null !== $security->getUser()) {
            return $this->redirectToRoute('admin');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'translation_domain' => 'admin',
            'page_title' => 'Kawiarnia Kaff',
            'target_path' => $this->generateUrl('admin'),
            'csrf_token_intention' => AdminLoginFormAuthenticator::LOGIN_CSRF_INTENTION,
            'username_parameter' => AdminLoginFormAuthenticator::LOGIN_USERNAME_PARAMETER_NAME,
            'password_parameter' => AdminLoginFormAuthenticator::LOGIN_PASSWORD_PARAMETER_NAME,
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout(): void
    {
        // ...
    }
}
