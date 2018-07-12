<?php

namespace yoann\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use yoann\PlatformBundle\Entity\Advert;
use yoann\PlatformBundle\Entity\Image;
use yoann\PlatformBundle\Entity\Application;

class AdvertController extends Controller
{

	public function indexAction($page)
	{

		//on ne sais pas combien de pages il y a mais on sais qu'une page doit etre superieur ou egale à 1
		if ($page < 1){
			//on de clenche une exception NotFoundHttpException, cela va afficher une page d'ereur 404
			throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

    // Notre liste d'annonce en dur
    $listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );

    // Et modifiez le 2nd argument pour injecter notre liste
    return $this->render('yoannPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts
    ));
	}

	public function good_byeAction()
	{
		
		$content2 = $this->get('templating')
						  ->render('yoannPlatformBundle:Advert:good_bye.html.twig', array('prenom' =>'yoann', 'role'=>'developpeur junior'));

		return new Response($content2);
	}


	public function viewAction($id)
	{

    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('yoannPlatformBundle:Advert')->find($id);

    if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // on recupere la liste des candidatures de cette annonce
    $listApplications = $em
        ->getRepository('yoannPlatformBundle:Application')
        ->findBy(array('advert' => $advert));

    // le render ne change pas, on passait un tableau, maintenant un objet
    return $this->render('yoannPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert,
      'listApplications' => $listApplications
    ));
	}


	    // On récupère tous les paramètres en arguments de la méthode
    public function viewSlugAction($slug, $year, $format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$format."."
        );
    }


    public function addAction(Request $request)
    {
        //creation de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche développpeur une bombe');
        $advert->setAuthor('Amelie');
        $advert->setContent('Nous recherchons  Blabla…');
        $advert->setDate(new \Datetime());

        //creation d'une premiére candidature
        $application1 = new Application();
        $application1->setAuthor('Amelie');
        $application1->setContent("J'ai toutes les qualités requises mais les technologies ne m'aiment pas");
        $application1->setDate(new \Datetime());

        //création d'une deuxieme candidature
        $application2 = new Application();
        $application2->setAuthor('Matteo');
        $application2->setContent("Je suis trés motivé car c'est un projet Star Wars");
        $application2->setDate(new \Datetime());

/*        //création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de reve');

        //on lie l'image à l'annonce
        $advert->setImage($image);*/

        // Étape 1 bis : si on n'avait pas défini le cascade={"persist"},
        // on devrait persister à la main l'entité $image
        // $em->persist($image);

        //on lie les candidatures à l'annonce
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        // On récupère l'EntityManager
        $em =$this->getDoctrine()->getManager();

        //etape 1 : on PERSISTE l'entité
        $em->persist($advert);


        // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
        // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
        $em->persist($application1);
        $em->persist($application2);

        //etape 2 : on FLUSH tout ce qui a été persisté avant
        $em->flush();



        //on recupere le service
        $antispam = $this->container->get('yoann_platform.antispam');

        //essai $text
        $text = '...';
        if ($antispam->isSpam($text)){

            throw new \Exception("Votre message a été detecté comme spam !!");
            
        }


    	//si la requte est en POST, c'est que le visiteur a soumis le formulaire
    	if ($request->isMethod('POST')){
    		//ici on s'ocuppera de la creation de la gestion du formulaire

    		$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

    		//puis on redirige vers la page de visualisation, de cette annonce 

    		return $this->redirectToRoute('yoann_platform_view', array('id' => $advert->getId()));
    	}
    	
    	//si on n'est pas en POST, alors on affiche le formulaire 
    		return $this->render('yoannPlatformBundle:Advert:add.html.twig');

    }

    public function editAction($id, Request $request)
    {
    	//ici on recuperera l'annonce correspondante à $id
    	//meme mecanisme que pour l'ajout
    	if ($request->isMethod('POST')){
    		$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

    		return $this->redirectToRoute('yoann_platform_view', array('id' => 5));
    	}

    	return $this->render('yoannPlatformBundle:Advert:edit.html.twig');
    }

    public function deleteAction($id)
    {

    	//ici on recuperera l'annonce correspondant à $id
    	//ici on gerera la suppression de l'annonce en question
    return $this->render('yoannPlatformBundle:Advert:delete.html.twig');	
    }

    public function menuAction()
    {
    	//on fixz en dure une liste ici, bien entendu par la suite on la recuperera depuis la BDD
    	$listAdverts = array(
    		array('id'=>2, 'title'=> 'Recherche développeur Symfony'),
    		array('id'=>5, 'title'=> 'Mission de webmaster'),
    		array('id'=>9, 'title'=> 'Offre de stage webdesigner')
    	);

    	return $this->render('yoannPlatformBundle:Advert:menu.html.twig', array(
    		//tout l'interet est ici : le controleur passer les variables nécessaires au template
    		'listAdverts' => $listAdverts

    	));
    }
}