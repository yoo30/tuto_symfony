<?php

namespace yoann\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('yoannPlatformBundle:Default:index.html.twig');
    }
}
