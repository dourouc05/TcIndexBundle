<?php

namespace TC\IndexBundle\Importer;

use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;
use Doctrine\Common\Persistence\ObjectManager; 
use Symfony\Component\Finder\Finder;

/**
 * Imports a "standard" DvpXML article in the categories indicated by their subfolder. 
 * 
 * For example, if root is /tutoriels/, then, /tutoriels/qt/ being the path for the Qt category, 
 * the article /tutoriels/qt/1/ will be stored in this category; if there is a category Qt 3D 
 * with the path /tutoriels/qt/3d/, then all tutorials in this folder will be put in that category. 
 * 
 * Category importation are made in the XmlCategoriesImporter class. 
 *
 * @author Thibaut
 */
class XmlArticleImporter extends AbstractImporter {
    private $root; 
    
    public function __construct(ObjectManager $om, $root = '') {
        parent::__construct($om);
        $this->root = $root; 
    }
    
    public function importFolder($folder) {
        $finder = new Finder(); 
        $finder->in($this->root . '/' . $folder)
               ->name('index.php')
               ->files()
               ->ignoreDotFiles(true);
        
        foreach($finder as $article) {
            $this->import(str_replace('index.php', '', $article->getRealpath()));
        }
    }
    
    // Imports an article from the specified folder (index.php & most recent .xml). 
    public function import($folder) {
        if(! file_exists($folder . '/index.php')) {
            return; 
        }
        
        $finder = new Finder(); 
        $finder->in($folder)
               ->name('*.xml')
               ->files()
               ->ignoreDotFiles(true)
               ->sort(function (\SplFileInfo $a, \SplFileInfo $b) {
                        return $a->getMTime() - $b->getMTime();
                    });
        
        $xml = '';
        foreach($finder as $x) {
            $xml = $x->getRealpath(); 
            break; 
        }
        
        $xml = new \SimpleXMLElement(file_get_contents($xml));
        
        $path = str_replace(array($this->root . '/', $this->root . '\\', '\\'), array('', '', '/'), $folder); 
        $path = explode('/', $path); 
        array_pop($path); // Trailing slash
        array_pop($path); // Subfolder for the article
        $path = implode('/', $path); 
        $path .= '/'; // To follow the convention for paths. 
        
        $parent = $this->om
                       ->createQuery('SELECT c FROM TCIndexBundle:Category c WHERE c.path = :p')
                       ->setParameter('p', $path)
                       ->getSingleResult();
        
        $path = explode('developpez.com/', $xml->entete->urlhttp, 2); 
        $path = $path[1];
        
        $item = new Item(); 
        $item->setCategory($parent); 
        $item->setSynopsis($xml->synopsis->paragraph[0]); 
        $item->setTitle($xml->entete->titre->article); 
        $item->setUrl($xml->entete->urlhttp); 
        $item->setPath($path); 
        
        $this->om->persist($item);
        $this->om->flush(); 
    }
}
