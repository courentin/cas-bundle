<?php
/**
 * Created by PhpStorm.
 * User: corentin
 * Date: 28/01/17
 * Time: 16:06
 */

namespace Utc\CasBundle\Security;


use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Utc\CasBundle\Cas\Client;
use Utc\CasBundle\Entity\CasUser;

class CasUserProvider implements UserProviderInterface
{

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($ticket)
    {
        $data = $this->client->validateServiceTicket("http://cas-utc.dev/app_dev.php/", $ticket);

        $user = new CasUser();
        $user->setUsername($data['cas:user']);
        $data = $data['cas:attributes'];

        $date = str_replace('[Europe/Paris]', '', $data['cas:authenticationDate']);
        $user
            ->setEmail($data['cas:mail'])
            ->setLastname($data['cas:sn'])
            ->setAuthenticationDate(new \DateTime($date))
            ->setFirstname($data['cas:givenName'])
            ->setType($data['cas:ou'])
            ->setTicket($ticket)
            ->setDisplayName($data['cas:displayName'])
        ;

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if(!$user instanceof CasUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getTicket());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === CasUser::class;
    }
}