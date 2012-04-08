<?php

namespace TC\IndexBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager; 
use TC\IndexBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $b = new Category();
        $b->setTitle('21');
        $manager->persist($b);
        
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
        
        $d = new Category();
        $d->setTitle('84');
        $manager->persist($d);
		
        $d2 = new Category();
        $d2->setTitle('84_c');
        $d2->setParent($d); 
        $manager->persist($d2);
        
        // Let's test when children do not follow parent. 
        $c5 = new Category();
        $c5->setTitle('42_c_c_d');
        $c5->setParent($c3); 
        $manager->persist($c5);
		
        $c6 = new Category();
        $c6->setTitle('42_c_c_c_c');
        $c6->setParent($c4); 
        $manager->persist($c6);
		
        $manager->flush();
        
        // Let's test position exchange. 
        $c5->pushAbove(); 
        $manager->persist($c5);
        $manager->flush();
    }
}