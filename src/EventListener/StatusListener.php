<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class StatusListener
{
    private Security $security;
    private RouterInterface $router;
    private RequestStack $requestStack;

    public function __construct(Security $security, RouterInterface $router, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $user = $this->security->getUser();
        $session = $this->requestStack->getCurrentRequest()->getSession();

        if ($user && method_exists($user, 'isStatus') && !$user->isStatus()) {
            // Désactiver la session si l'utilisateur est désactivé
            $session->invalidate();
            $session->getFlashBag()->add('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
            
            // Redirige vers la page de déconnexion ou une autre route
            $response = new RedirectResponse($this->router->generate('app_logout'));
            $event->setResponse($response);
            $event->stopPropagation();
        }
    }

    #[AsEventListener(event: 'security.interactive_login')]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $session = $this->requestStack->getCurrentRequest()->getSession();

        if ($user && method_exists($user, 'isStatus') && !$user->isStatus()) {
            // Invalider la session si l'utilisateur est désactivé
            $session->invalidate();
            $session->getFlashBag()->add('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
            
            // Ici, vous devez rediriger l'utilisateur dans votre contrôleur
            $response = new RedirectResponse($this->router->generate('app_login'));
            $event->getRequest()->setResponse($response);
        }
    }
}
