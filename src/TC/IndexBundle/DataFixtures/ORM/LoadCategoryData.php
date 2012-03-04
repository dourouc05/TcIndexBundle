<?php

namespace TC\IndexBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager; 
use TC\IndexBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $c = new Category();
        $c->setTitle('42');
        $manager->persist($c);
		
        $c = new Category();
        $c->setTitle('422');
        $manager->persist($c);
		
        $manager->flush();
    }
}