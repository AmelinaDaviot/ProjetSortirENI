<?php

namespace App\Security;

use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthentificationAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'securite_connexion';

    private UrlGeneratorInterface $urlGenerator;
    private ParticipantRepository $participantRepository;

    public function __construct(UrlGeneratorInterface $urlGenerator, ParticipantRepository $participantRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->participantRepository = $participantRepository;
    }

    public function authenticate(Request $request): Passport
    {
        $pseudoOrEmail = $request->request->get('pseudo_or_email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $pseudoOrEmail);

        // Verification de la connexion par email / pseudo
        $field = 'pseudo';

        if (filter_var($pseudoOrEmail, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }


        return new Passport(
        // Modification du UserBadge pour expliciter la recherche du champ (Email / Pseudo)
            new UserBadge($pseudoOrEmail, function($userIdentifier) use($field) {
                return $this->participantRepository->findOneBy([$field=>$userIdentifier]);

            }),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
         return new RedirectResponse($this->urlGenerator->generate('ville'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
