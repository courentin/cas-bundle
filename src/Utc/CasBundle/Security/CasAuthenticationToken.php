<?php
/**
 * Created by PhpStorm.
 * User: corentin
 * Date: 06/02/17
 * Time: 21:15
 */

namespace Utc\CasBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class CasAuthenticationToken extends PostAuthenticationGuardToken
{
    private $ticket;

    public function __construct(UserInterface $user, $providerKey, array $roles, $ticket)
    {
        parent::__construct($user, $providerKey, $roles);
        $this->ticket = $ticket;
    }

    /**
     * @return string
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}