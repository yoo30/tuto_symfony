<?php

namespace yoann\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use yoann\PlatformBundle\Entity\Skill;

class Loadskill implements FixtureInterface
{

	public function load(ObjectManager $manager)
	{
		//liste des noms de compétences à ajouter
		$names = array('PHP', 'Symfony', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-note');

		foreach ($names as $name){
			//on crée la compétence
			$skill = new Skill();
			$skill->setName($name);

			//on la persiste
			$manager->persist($skill);
		}

		//on declenche l'enregistrement de toutes les categories
		$manager->flush();
	}


}