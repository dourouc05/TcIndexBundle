<?php

namespace TC\IndexBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager; 
use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;
//use TC\IndexBundle\Importer\HtmlFileImporter;

class LoadCategoryData implements FixtureInterface
{
    public function load2(ObjectManager $manager)
    {
        $c3 = new Category();
        $c3->setTitle('C');
        $manager->persist($c3);
        $importer = new HtmlFileImporter($manager); 
        //$importer->import('C:\\Program Files (x86)\\EasyPHP-5.3.8.0\\www\\index\\index\\articles\\c.php'); 
    }
    
    public function load(ObjectManager $manager)//_TestSet
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
		
        $c7 = new Category();
        $c7->setTitle('42_c_c_c_d');
        $c7->setParent($c4); 
        $manager->persist($c7);
		
        $c8 = new Category();
        $c8->setTitle('42_c_c_c_e');
        $c8->setParent($c4); 
        $manager->persist($c8);
		
        $c9 = new Category();
        $c9->setTitle('42_c_c_c_f');
        $c9->setParent($c4); 
        $manager->persist($c9);
		
        $c10 = new Category();
        $c10->setTitle('42_c_c_c_g');
        $c10->setParent($c4); 
        $manager->persist($c10);
		
        $c11 = new Category();
        $c11->setTitle('42_c_c_c_h');
        $c11->setParent($c4); 
        $manager->persist($c11);
		
        $c12 = new Category();
        $c12->setTitle('42_c_c_c_i');
        $c12->setParent($c4); 
        $manager->persist($c12);
		
        $c13 = new Category();
        $c13->setTitle('42_c_c_c_j');
        $c13->setParent($c4); 
        $manager->persist($c13);
		
        $c14 = new Category();
        $c14->setTitle('42_c_c_c_k');
        $c14->setParent($c4); 
        $manager->persist($c14);
		
        $c15 = new Category();
        $c15->setTitle('42_c_c_c_l');
        $c15->setParent($c4); 
        $manager->persist($c15);
		
        $c16 = new Category();
        $c16->setTitle('42_c_c_c_m');
        $c16->setParent($c4); 
        $manager->persist($c16);
		
        $c17 = new Category();
        $c17->setTitle('42_c_c_c_n');
        $c17->setParent($c4); 
        $manager->persist($c17);
		
        $manager->flush();
        
        
        
        $i1 = new Item(); 
        $i1->setTitle("I1");
        $i1->setCategory($c6); 
        $manager->persist($i1);
        
        $i2 = new Item(); 
        $i2->setTitle("I2");
        $i2->setCategory($c6); 
        $manager->persist($i2);
        
        $i3 = new Item(); 
        $i3->setTitle("I3");
        $i3->setCategory($c4); 
        $manager->persist($i3);
        
        $manager->flush();
    }
}