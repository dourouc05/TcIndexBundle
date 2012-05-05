<?php

namespace TC\IndexBundle\Importer;

use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Persistence\ObjectManager; 
use Doctrine\ORM\NoResultException; 
use Doctrine\ORM\ORMException; 

/**
 * Imports a "cours" XML file. 
 *
 * @author Thibaut
 */
class CoursFileImporter extends AbstractImporter {
    private $xml; 
    private $categories; 
    
    public function import($file) {
        if(! file_exists($file) || ! is_file($file)) {
            throw new NonExistantFileException('cours', $file);
        }
        
        $data = file_get_contents($file);
        $this->xml = new \SimpleXMLElement($data); // takes care of the encoding
        
        $this->doCategories(); 
        $this->doArticles(); 
        $this->om->flush();
    }
    
    private function doCategories() {
        $this->categories = array(); 
        
        foreach($this->xml->cours->categorie as $c) {
            $this->doCategory($c);
        }
    }
    
    private function doCategory(\SimpleXMLElement $c, Category $parent = null) {
        $cat = new Category(); 
        $cat->setTitle((string) $c['nom']);
        
        if($parent) {
            $cat->setParent($parent);
        }
        
        $this->om->persist($cat);
        
        $this->categories[(string) $c['id']] = $cat;
        
        foreach($c->categorie as $subcat) {
            $this->doCategory($subcat, $cat); 
        }
    }
    
    private function doArticles() {
        foreach($this->xml->articles->article as $a) {
            $item = new Item(); 
            $item->setUrl($a->link['url']); 
            $item->setTitle((string) $a->link); 
            $item->setSynopsis((string) $a->resume);
            
            if(isset($a->categorie['refid'])) {
                $item->setCategory($this->categories[(string) $a->categorie['refid']]);
            }
            
            $this->om->persist($item);
        }
    }
}
