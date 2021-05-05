<?php

declare(strict_types=1);

namespace App\Security\EntryPoint;

use App\Security\Authenticator\SiteLoginFormAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class SiteAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private UrlGeneratorInterface $urlGenerator;
    private SessionInterface $session;

    public function __construct(UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        if (null !== $authException) {
            $this->session->getFlashBag()->add('warning', $authException->getMessage());
        }

        return new RedirectResponse($this->urlGenerator->generate(SiteLoginFormAuthenticator::LOGIN_ROUTE));
    }
}