<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;
use TC\IndexBundle\Importer\HtmlFileImporter;
use TC\IndexBundle\Importer\XmlArticleImporter;
use TC\IndexBundle\Importer\XmlCategoryImporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of ImporterController
 *
 * @author Thibaut
 * 
 * @Route("/import") 
 */
class ImporterController extends Controller {
    /**
     * @Route("/html") 
     */
    public function htmlFileImportAction() {
        $importer = new HtmlFileImporter($this->getDoctrine()->getEntityManager()); 
        $importer->import('C:\\Program Files (x86)\\EasyPHP-5.3.8.0\\www\\index\\index\\articles\\qt.php'); 
        return new Response(); 
    }
    
    /**
     * @Route("/xml/categories") 
     */
    public function xmlCategoriesImportAction() {
        $importer = new XmlCategoryImporter($this->getDoctrine()->getEntityManager(), 'C:\\Program Files (x86)\\EasyPHP-5.3.8.0\\www\\index'); 
        $importer->import('tutoriels'); 
        return new Response(); 
    }
    
    /**
     * @Route("/xml/articles") 
     */
    public function xmlArticlesImportAction() {
        $importer = new XmlArticleImporter($this->getDoctrine()->getEntityManager(), 'C:\\Program Files (x86)\\EasyPHP-5.3.8.0\\www\\index'); 
        $importer->importFolder('tutoriels'); 
        return new Response(); 
    }
}
