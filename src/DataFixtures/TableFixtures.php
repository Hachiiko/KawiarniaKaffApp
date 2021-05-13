<?php

namespace App\DataFixtures;

use App\Entity\Table;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TableFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $table = new Table();
            $table->setName('Stolik nr ' . $i);

            $manager->persist($table);
        }

        $manager->flush();
    }
}
