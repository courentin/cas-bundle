<?php

namespace Utc\CasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UtcCasBundle:Default:index.html.twig');
    }
}
