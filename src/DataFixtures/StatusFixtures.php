<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class StatusFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $status = new Status();
        $status->setName('Status - Planned');
        $manager->persist($status);
        $manager->flush();

        $status = new Status();
        $status->setName('In Production');
        $manager->persist($status);
        $manager->flush();

        $status = new Status();
        $status->setName('Completed');
        $manager->persist($status);
        $manager->flush();

    }
    public static function getGroups(): array
     {
         return ['group1'];
     }
}
