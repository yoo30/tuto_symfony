<?php

namespace yoann\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class AccueilController extends Controller
{

	public function indexAction()
	{

		//return new Response("mon propre Hello World !!!");
		return $this->render('yoannCoreBundle:homepage:homepage.html.twig');
	}
}