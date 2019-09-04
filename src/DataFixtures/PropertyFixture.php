<?php

namespace App\DataFixtures;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 12; $i++) {
            $property = new Property();


            /** @var TYPE_NAME $faker */
            //$sante->setDate(new \DateTime('now'));
            $property->setCreatedAt(new \DateTime('now'));
			 $property->setUpdatedAt(new \DateTime('now'));
            $property->setcity('El-Jadida');
            $property->setTitle('Mon neuvieme bien');
            $property->setFloor(4);
            $property->setHeat('Electrique');
			$property->setLat(75000);
			 $property->setLng(60000);
            $property->setPrice(45000);
			$property->setRooms(6);
			$property->setBedrooms(2);
			$property->setSurface(40);
			$property->setPostalCode(74581);
			$property->setAddress('3200,les herissons, El-Jadida');
			$property->setDescription('Tous mes bien sont chez mon dieu, et dieu va m\'epargner le mal, et dieu est grand  ');
			 






            $manager->persist($property);
        }

        $manager->flush();
    }
}
