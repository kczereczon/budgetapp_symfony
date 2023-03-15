<?php

namespace App\Seeders;

use Faker;

class CategorySeed extends \Evotodi\SeedBundle\Command\Seed
{
    public static function seedName(): string
    {
        return 'category';
    }

    public function load(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $faker = Faker\Factory::create();

        foreach (range(0, 5) as $i) {
            $category = new \App\Entity\Category();
            $category->setEmoji($faker->emoji());
            $category->setName($faker->word());
            $this->manager->getManager()->persist($category);
        }

        $this->manager->getManager()->flush();

        return 0;
    }

    public function unload(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $this->manager->getConnection()->exec('DELETE FROM category');

        return 0;
    }

}