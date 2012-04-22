<?php

namespace TC\IndexBundle\Importer;

use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;

class HtmlFileImporter extends AbstractImporter
{
    /** Expected format (without indentation): 
    
    <h2><a id="c">C</a></h2>
	    <h3><a id="mpir">MPIR</a></h3>
	        <ul>
	            <li><a href="http://tcuvelier.developpez.com/tutoriels/c/mpir/introduction/">Introduction à MPIR</a></li>
	        </ul>
    
    **/
    public function import($file)
    {
        var_dump(42); 
        $data = utf8_encode(file_get_contents($file));
        
        $data = explode('</h', $data, 2); 
        //var_dump($data);
        $title = array_shift($data); 
        $data = $data[0];
        
        // <h2><a id="c">C</a>
        $title = explode('>', $title); 
        // array(4) {
        //   [0]=>
        //   string(3) "<h2"
        //   [1]=>
        //   string(9) "<a id="c""
        //   [2]=>
        //   string(4) "C</a"
        //   [3]=>
        //   string(0) ""
        // }
        $title = $title[count($title) - 2]; 
        // string(4) "C</a"
        $title = explode('<', $title); 
        $title = $title[0]; 
        
        $root = new Category();
        $root->setTitle($title);
        $this->om->persist($root);
        
        $this->doCategory($data, $root); 
        
        $this->om->flush(); 
    }
    
    private function doCategory($data, $parent) {
        $data = explode('<h3>', $data);
        foreach($data as $d) {
            $this->doOneCategory($d, $parent); 
        }
    } 
    
    private function doOneCategory($data, $parent) {
        // If this bit of string is not present, everything goes to the parent. 
        if(strpos($data, '</h3>') === false) {
            $data = explode('<li>', $data);
            array_shift($data);
            foreach($data as $d) {
                $this->doOneElement($d, $parent); 
            }
        } else {
            // <a id="qt-begin">Débuter avec Qt</a></h3>
            // <ul>
            //      <li><a href="http://qt.developpez.com/tutoriels/introduction-qt/">Débuter dans la création d'interfaces graphiques avec Qt</a></li>
            //      <li><a href="#qt-updater">Continuer son apprentissage de Qt par l'exemple : un updater avec Qt</a></li>
            // </ul>
            
            // Creates the parent. 
            $data = explode('</a></h3>', $data);
            $title = explode('>', $data[0]); 
            $title = array_pop($title);
            $data = $data[1];
            
            $cat = new Category();
            $cat->setTitle($title);
            $cat->setParent($parent);
            $this->om->persist($cat);
            
            // Creates the children. 
            $data = explode('<li>', $data);
            array_shift($data);
            foreach($data as $d) {
                $this->doOneElement($d, $cat);
            }
        }
    }
    
    private function doOneElement($data, $parent) {
        // <a href="http://qt.developpez.com/faq/">La FAQ Qt</a></li>
        // Forget things like 
        // <a href="#qt-updater">Continuer son apprentissage de Qt par l'exemple : un updater avec Qt</a></li>
        
        if(strpos($data, 'href="#') !== false) {
            return; 
        }
        
        // Get the URL. 
        $data = explode('<a href="', $data);
        array_shift($data);
        $data = $data[0];
        $data = explode('">', $data);
        $url = $data[0]; 
        $data = $data[1]; 
        
        // Get the title. 
        $data = explode('</a>', $data);
        $title = trim($data[0]); 
        unset($data);
        
        // Creates the entity. 
        $el = new Item(); 
        $el->setCategory($parent);
        $el->setUrl($url);
        $el->setTitle($title);
        $this->om->persist($el); 
        
        var_dump(array($url, $title));
    }
} 