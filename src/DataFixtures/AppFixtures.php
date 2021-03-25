<?php


namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        //créer 5 instances de Category et les sauvegarder en bdd
        $categoryNames = [
            "Voyage & Aventure",
            "Sport",
            "Divertissement",
            "Relations Humaines",
            "Autres"
        ];

        foreach($categoryNames as $cn){
            $newCategory = new Category();
            $newCategory->setName($cn);
            $manager->persist($newCategory);
        }

        $manager->flush();

        //récupérer les 5 instances avec un findAll()
        $categoryRepository = $manager->getRepository(Category::class);

        $categories = $categoryRepository->findAll();

        //en sélectionner une dans le tableau de résultats au hasard avec $faker et randomElement()

        //donner cette catégorie à un Wish

        for($i = 1; $i <= 200; $i++) {
            $wish = new Wish();

            $wish->setTitle($faker->sentence());
            $wish->setDescription($faker->realText());
            $wish->setAuthor($faker->userName);
            $wish->setIsPublished($faker->boolean(90));
            $wish->setDateCreated($faker->dateTimeBetween('- 2 years'));
            $wish->setLikes($faker->numberBetween(0, 5000));
            $wish->setCategory($faker->randomElement($categories));

            $manager->persist($wish);
        }

        $manager->flush();
    }
}