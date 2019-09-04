<?php

namespace App\DataFixtures;
use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OptionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
 //Creating default roles
            $option = new Option();
            $option->setName('HightTech');
           $manager->persist($option);
		   
		    $option = new Option();
            $option->setName('Blog');
           $manager->persist($option);
		   $option = new Option();
            $option->setName('Informatique');
           $manager->persist($option);
		   
		   $option = new Option();
            $option->setName('Science');
           $manager->persist($option);
           

            $manager->flush();
            //$manager->clear();
    }
}
