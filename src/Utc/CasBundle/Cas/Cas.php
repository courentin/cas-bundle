<?php
/**
 * Created by PhpStorm.
 * User: corentin
 * Date: 06/02/17
 * Time: 23:08
 */

namespace Utc\CasBundle\Cas;


class Cas
{
    private $url;
    private $loginPath;
    private $logoutPath;
    private $serviceValidatePath;

    public function __construct($url, $loginPath, $logoutPath, $serviceValidatePath)
    {
        $this->url = $url;
        $this->loginPath = $loginPath;
        $this->logoutPath = $logoutPath;
        $this->serviceValidatePath = $serviceValidatePath;
    }

    protected function generateUrl($path, $service, $ticket = null)
    {
        $params = [
            'service' => $service,
            'ticket'  => $ticket,
        ];
        return sprintf("%s/%s?%s", trim($this->url, '/'), trim($path, '/'), http_build_query($params));
    }

    public function getLoginUrl($serviceUrl)
    {
        return $this->generateUrl($this->loginPath, $serviceUrl);
    }

    public function getLogoutUrl($serviceUrl)
    {
        return $this->generateUrl($this->logoutPath, $serviceUrl);
    }

    public function getServiceValidateUrl($serviceUrl, $ticket)
    {
        return $this->generateUrl($this->serviceValidatePath, $serviceUrl, $ticket);
    }

    public function getBaseUrl()
    {
        return $this->url;
    }
}