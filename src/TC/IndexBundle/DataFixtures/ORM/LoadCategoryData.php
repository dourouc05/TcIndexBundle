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
		
        $c2 = new Category();
        $c2->setTitle('42_c');
        $c2->setParent($c); 
        $manager->persist($c2);
		
        $c3 = new Category();
        $c3->setTitle('42_c_c');
        $c3->setParent($c2); 
        $manager->persist($c3);
		
        $c4 = new Category();
        $c4->setTitle('42_c_c_c');
        $c4->setParent($c3); 
        $manager->persist($c4);
		
        $manager->flush();
    }
}