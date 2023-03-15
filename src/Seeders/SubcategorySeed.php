<?php

namespace App\Seeders;

use App\Entity\Category;
use App\Entity\Subcategory;
use Evotodi\SeedBundle\Command\Seed;

class SubcategorySeed extends Seed
{
    public static function seedName(): string
    {
        return 'subcategory';
    }

    public function load(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $faker = \Faker\Factory::create();

        $categories = $this->manager->getManager()->getRepository(Category::class)->findAll();

        foreach ($categories as $category) {
            foreach (range(0, 5) as $i) {
                $subcategory = new \App\Entity\Subcategory();
                $subcategory->setCategory($category);
                $subcategory->setName($faker->word());
                $this->manager->getManager()->persist($subcategory);
            }
        }

        $this->manager->getManager()->flush();

        return 0;
    }

    public function unload(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $this->manager->getConnection()->exec('DELETE FROM subcategory');

        return 0;
    }
}