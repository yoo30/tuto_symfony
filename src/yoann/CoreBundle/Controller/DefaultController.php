<?php

namespace yoann\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('yoannCoreBundle:Default:index.html.twig');
    }
}
