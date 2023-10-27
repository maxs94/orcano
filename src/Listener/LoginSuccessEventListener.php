<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Listener;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessEventListener
{
    public function __invoke(LoginSuccessEvent $event): void
    {
        $session = $event->getRequest()->getSession();
        if ($session instanceof SessionInterface) {
            /** @var User $cleanUser */
            $cleanUser = clone $event->getUser();

            // remove the hashed password
            $cleanUser->setPassword('');

            $session->set('currentUser', $cleanUser);
        }
    }
}
