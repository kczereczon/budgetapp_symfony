<?php

namespace App\Seeders;

use App\Entity\Subcategory;
use Evotodi\SeedBundle\Command\Seed;

class TransactionSeed extends Seed
{
    public static function seedName(): string
    {
        return 'transaction';
    }

    /**
     * @throws \Exception
     */
    public function load(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $faker = \Faker\Factory::create();

        $subcategories = $this->manager->getManager()->getRepository(Subcategory::class)->findAll();
        shuffle($subcategories);

        foreach ($subcategories as $subcategory) {
            foreach (range(0, random_int(1, 5)) as $i) {
                $transaction = new \App\Entity\Transaction();
                $transaction->setSubcategory($subcategory);
                $transaction->setName($faker->word());
                $transaction->setPrice($faker->numberBetween( 5, 1000));
                $transaction->setPaidAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 days')));
                $this->manager->getManager()->persist($transaction);
            }
        }

        $this->manager->getManager()->flush();

        return 0;
    }

    public function unload(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $this->manager->getConnection()->exec('DELETE FROM transaction');

        return 0;
    }
}