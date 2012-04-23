<?php

namespace TC\IndexBundle\Importer;

use TC\IndexBundle\Entity\Category;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Persistence\ObjectManager; 
use Doctrine\ORM\NoResultException; 

/**
 * Imports folders as categories. 
 *
 * @author Thibaut
 */
class XmlCategoryImporter extends AbstractImporter {
    private $root; 
    
    public function __construct(ObjectManager $om, $root) {
        parent::__construct($om);
        $this->root = $root; 
    }
    
    public function import($folder) {
        $finder = new Finder(); 
        $finder->in($this->root . '/' . $folder)
               ->directories()
               ->exclude('images')
               ->exclude('fichiers')
               ->exclude('videos')
               ->ignoreDotFiles(true);
        
        // Array of paths for categories to create. 
        $categories = array(); 
        foreach($finder as $file) {
            if(! file_exists($file->getRealpath() . '/index.php')) {
                // Beware! Windows compatibility! 
                $categories[] = str_replace('\\', '/', $file->getRelativePathname()); 
            } 
        }
        
        // For example, the array is: 
            // array
            //  0 => string 'qt' (length=2)
            //  1 => string 'qt/extend' (length=9)
            //  2 => string 'qt/extend/devil/subcat' (length=22)
            //  3 => string 'qt/qml' (length=6)
            //  4 => string 'qt/qml/mobile-meego' (length=19)
        // There, one must infer qt\extend\devil because of qt\extend\devil\subcat. 
        $cats = array();
        $last = null; 
        foreach($categories as $c) {
            if($last == null) {
                $last = $c; 
            } elseif (strpos($c, $last) === false) {
                $cats[] = explode('/', $last); 
                $last = $c; 
            } else {
                $last = $c; 
            }
        }
        $cats[] = explode('/', $categories[count($categories) - 1]); // the last one is not getting in the list. 
        var_dump($cats);
        // The result: 
            // array
            //  0 => 
            //    array
            //      0 => string 'qt' (length=2)
            //      1 => string 'extend' (length=6)
            //      2 => string 'devil' (length=5)
            //      3 => string 'subcat' (length=6)
            //  1 => 
            //    array
            //      0 => string 'qt' (length=2)
            //      1 => string 'qml' (length=3)
            //      2 => string 'mobile-meego' (length=12)
        
        // Now create the categories. 
        foreach($cats as $c) {
            var_dump($c);
            //$parent = null; 
            $accumulator = '/'; 
            for($i = 0; $i < count($c); ++$i) {
                /*if(isset($create[$c[$i]])) {
                    $parent = $create[$c[$i]]; 
                }
                
                $create[$c[$i]] = new Category(); 
                $create[$c[$i]]->setTitle($c[$i]);
                $create[$c[$i]]->setPath($c[$i]);
                if($parent) {
                    $create[$c[$i]]->setParent($parent);
                }*/
                
                $cat = new Category(); 
                $cat->setTitle($accumulator . $c[$i] . '/'); 
                $cat->setPath($cat->getTitle()); 
                
                try {
                    var_dump($accumulator); 
                    $parent = $this
                            ->om
                            ->createQuery('SELECT c FROM TCIndexBundle:Category c WHERE c.path = :p')
                            ->setParameter('p', $accumulator)
                            ->getSingleResult(); 
                    $cat->setParent($parent);
                } catch(NoResultException $e) {
                    // Nothing else to do. 
                } 
                var_dump($cat); 
                $accumulator .= $c[$i] . '/';
                $this->om->persist($cat); 
                try {
                    $this->om->flush(); 
                } catch(\PDOException $e) {
                    // Already in database. 
                    $this->om->detach($cat);
                    continue; 
                }
            }
        }
    }
}
