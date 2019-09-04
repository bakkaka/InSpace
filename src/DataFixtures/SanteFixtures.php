<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Sante;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class SanteFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $sante = new Sante();


            /** @var TYPE_NAME $faker */
            //$sante->setDate(new \DateTime('now'));
            $sante->setCreatedAt(new \DateTime('now'));
            $sante->setUpdatedAt(new \DateTime('now'));
            $sante->setcity('Tanger');
            $sante->setFirstName('Khadija');
            $sante->setLastName('Stof');
            $sante->setSpecialite('Genicologue');
            $sante->setIsActivated(1);






            $manager->persist($sante);
        }

        $manager->flush();
    }
}