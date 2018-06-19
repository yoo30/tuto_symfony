<?php

namespace yoann\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class AdvertController
{
	public function indexAction()
	{
		return new Response("Notre propre Heloo World !");
	}
}