<?php

namespace yoann\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use yoann\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{

// dans l'argument de la methode load, l'objet $manager est l'EntityManager
	public function load(ObjectManager $manager)
	{
		//liste des noms de categorie à ajouter
		$names = array(
			'Développement web',
			'Developpement mobile',
			'Graphisme',
			'Intégration',
			'Réseau'
		);

		foreach ($names as $name){
			//on crée la categorie
			$category = new Category();
			$category->setName($name);

			//on la persiste
			$manager->persist($category);
		}

		//on déclenche l'enregistrement de toutes les categories
		$manager->flush();
	}


}