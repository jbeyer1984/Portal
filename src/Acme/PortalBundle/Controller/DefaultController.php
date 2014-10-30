<?php

namespace Acme\PortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmePortalBundle:Default:index.html.twig', array('name' => $name));
    }
}
