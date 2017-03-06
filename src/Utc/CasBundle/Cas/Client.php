<?php
/**
 * Created by PhpStorm.
 * User: corentin
 * Date: 06/02/17
 * Time: 23:39
 */

namespace Utc\CasBundle\Cas;


use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Utc\CasBundle\Exception\AuthenticationCasException;
use Utc\CasBundle\Exception\InternalErrorCasException;
use Utc\CasBundle\Exception\InvalidProxyCallbackCasException;
use Utc\CasBundle\Exception\InvalidRequestCasException;
use Utc\CasBundle\Exception\InvalidServiceCasException;
use Utc\CasBundle\Exception\InvalidTicketCasException;
use Utc\CasBundle\Exception\InvalidTicketSpecCasException;
use Utc\CasBundle\Exception\UnauthorizedServiceProxyCasException;

class Client
{
    /**
     * @var Cas
     */
    private $cas;

    public function __construct(Cas $cas)
    {
        $this->cas = $cas;
    }

    public function validateServiceTicket($service, $ticket)
    {
        $data = file_get_contents($this->cas->getServiceValidateUrl($service, $ticket));
        $xmlEncoder = new XmlEncoder();
        $data = $xmlEncoder->decode($data, 'xml');

        if(!isset($data['cas:authenticationSuccess'])) {
            if(!isset($data['cas:authenticationFailure'])) {
                throw new AuthenticationCasException();
            }
            throw $this->createException($data['cas:authenticationFailure']);
        }

        return $data['cas:authenticationSuccess'];
    }

    /**
     * @param $failure
     * @throws AuthenticationCasException
     */
    private function createException($failure)
    {
        switch ($failure['@code']) {
            case 'INVALID_REQUEST':
                return new InvalidRequestCasException($failure['#']);
                break;

            case 'INVALID_TICKET_SPEC':
                return new InvalidTicketSpecCasException($failure['#']);
                break;

            case 'UNAUTHORIZED_SERVICE_PROXY':
                return new UnauthorizedServiceProxyCasException($failure['#']);
                break;

            case 'INVALID_PROXY_CALLBACK':
                return new InvalidProxyCallbackCasException($failure['#']);
                break;

            case 'INVALID_TICKET':
                return new InvalidTicketCasException($failure['#']);
                break;

            case 'INVALID_SERVICE':
                return new InvalidServiceCasException($failure['#']);
                break;

            case 'INTERNAL_ERROR':
                return new InternalErrorCasException($failure['#']);
                break;

            default:
                return new AuthenticationCasException($failure['#']);
                break;
        }
    }
}