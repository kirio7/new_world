<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $siretOrEmail = $request->request->get('_username', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $siretOrEmail);

        return new Passport(
            new UserBadge($siretOrEmail),
            new PasswordCredentials($request->request->get('_password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // redirection vers la page demandée avant login
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // redirection selon le rôle
        $roles = $token->getRoleNames(); // récupère un tableau comme ['ROLE_USER'], ['ROLE_ADMIN'], ['ROLE_PRODUCTEUR']

        if (in_array('ROLE_SI', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('si_dashboard'));
        }

        if (in_array('ROLE_ADMIN', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
        }

        if (in_array('ROLE_PRODUCTEUR', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('producteur_dashboard'));
        }

        // par défaut, redirection pour utilisateur classique
        return new RedirectResponse($this->urlGenerator->generate('app_product_page'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}