<?php

namespace Utc\CasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('UtcCasBundle:Default:index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
