<?php

namespace App\DataFixtures;

use App\Entity\Factory\TableReservationFactory;
use App\Entity\Table;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TableReservationFixtures extends Fixture implements DependentFixtureInterface
{
    private TableReservationFactory $tableReservationFactory;

    public function __construct(TableReservationFactory $tableReservationFactory)
    {
        $this->tableReservationFactory = $tableReservationFactory;
    }

    public function load(ObjectManager $manager)
    {
        /** @var User[] $users */
        $users = $manager->getRepository(User::class)->findAll();

        /** @var Table[] $tables */
        $tables = $manager->getRepository(Table::class)->findAll();

        foreach ($tables as $table) {
            $today = new \DateTime;

            for ($i = 0; $i < 10; $i++) {
                $reservation = $this->tableReservationFactory->create(
                    owner: $users[array_rand($users)],
                    table: $table,
                    firstName: 'Jan',
                    lastName: 'Kowalski',
                    phone: '123 123 123',
                    dateFrom: $today->setTime(8 + $i, 0),
                    dateTo: $today->setTime(8 + $i + 1, 0)
                );

                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            UserFixtures::class,
            TableFixtures::class,
        ];
    }
}
