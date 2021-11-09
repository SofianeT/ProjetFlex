<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Cours;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CoursFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $faker =  \Faker\Factory::create('fr_FR');

       // Creer 3 categories fakées

       for($i = 1; $i <= 3 ; $i++)
       {
           $category = new Category();
           $category ->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
                     
            $manager->persist($category);
            

          //Creer entre 3 et 6 articles  
            for($j = 1; $j <= mt_rand(4, 6) ; $j++)
            {
                $cours = new Cours();

                $content = '<p>' . join('</p><p>',$faker->paragraphs(5)) .  '</p>';
                
                $cours->setTitle($faker->sentence())
                      ->setContent($content)
                      ->setImage($faker->imageUrl())
                      ->setCreateAt($faker->dateTimeImmutableBetween('-6 months'))
                      ->setCategory($category);
                $manager->persist($cours);
            }
                
            //On donne des commmentaires
            for($k = 1; $k <= mt_rand(4, 10) ; $k++)
            {
               $comment = new Comment();
               $content = '<p>' . join('</p><p>',$faker->paragraphs(2)) .  '</p>';

               $days = (new \DateTime())->diff($cours->getCreateAt())->days;

               $comment ->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreateAt($faker->dateTimeBetween('-'. $days . 'days'))
                        ->setCours($cours);
              $manager->persist($comment);

            }

        

        }

        $manager->flush();
    }
}
