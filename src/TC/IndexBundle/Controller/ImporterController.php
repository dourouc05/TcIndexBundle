<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;
use TC\IndexBundle\Importer\CoursFileImporter;
use TC\IndexBundle\Importer\HtmlFileImporter;
use TC\IndexBundle\Importer\XmlArticleImporter;
use TC\IndexBundle\Importer\XmlCategoryImporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of ImporterController
 *
 * @author Thibaut
 */
class ImporterController extends Controller {
    /**
     * @Route("/") 
     */
    public function indexAction() {
        return $this->render('TCIndexBundle:DefaultImporters:importers.html.twig');
    }
    
    /**
     * @Route("/html") 
     */
    public function htmlFileImportAction(Request $request) {
        $success = false; 
        $error   = false; 
        $this->get('session')->clearFlashes();
        
        if ($request->getMethod() == 'GET' && isset($_GET['page'])) {
            try {
                $importer = new HtmlFileImporter($this->getDoctrine()->getEntityManager()); 
                $importer->import($_SERVER['DOCUMENT_ROOT'] . '/' . $_GET['page']); 

                $success = $this->render('TCIndexBundle:DefaultImporters:htmlFileImporterSuccess.html.twig', array('page' => $_GET['page']))->getContent(); 
            } catch(\Exception $e) {
                $error = $e->getMessage();
            }
        } else {
            $error = $this->render('TCIndexBundle:DefaultImporters:htmlFileImporterErrorRequest.html.twig')->getContent(); 
        }
        var_dump(get_class($this->get('session')));
        if(! $error) {
            $this->get('session')->setFlash('success', $success); 
        } else {
            $this->get('session')->setFlash('error', $error); 
        }
        
        return $this->render('TCIndexBundle:DefaultImporters:importers.html.twig');
    }
    
    /**
     * @Route("/cours") 
     */
    public function coursFileImportAction(Request $request) {
        if ($request->getMethod() == 'GET') {
            $importer = new CoursFileImporter($this->getDoctrine()->getEntityManager()); 
            $importer->import($_SERVER['DOCUMENT_ROOT'] . '/' . $_GET['page']); 
            return $this->render('TCIndexBundle:DefaultImporters:htmlFileImporter.html.twig', array('page' => $_GET['page']));
        }
        
        throw new \Exception('Would you have tried to get here by your own means?');
    }
    
    /**
     * @Route("/xml/categories") 
     */
    public function xmlCategoriesImportAction() {
        $importer = new XmlCategoryImporter($this->getDoctrine()->getEntityManager(), $_SERVER['DOCUMENT_ROOT']); 
        $importer->import('tutoriels'); 
        return $this->render('TCIndexBundle:DefaultImporters:xmlCategoryImporter.html.twig');
    }
    
    /**
     * @Route("/xml/articles") 
     */
    public function xmlArticlesImportAction() {
        $importer = new XmlArticleImporter($this->getDoctrine()->getEntityManager(), 'C:\\Program Files (x86)\\EasyPHP-5.3.8.0\\www\\index'); 
        $importer->importFolder('tutoriels'); 
        return $this->render('TCIndexBundle:DefaultImporters:xmlArticleImporter.html.twig');
    }
}