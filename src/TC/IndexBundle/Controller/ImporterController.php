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
        return $this->basicScheme($request, 'html', function($em, $page) {
            $importer = new HtmlFileImporter($em); 
            $importer->import($_SERVER['DOCUMENT_ROOT'] . '/' . $page); 
            return array('page' => $_GET['page']); 
        }); 
    }
    
    /**
     * @Route("/cours") 
     */
    public function coursFileImportAction(Request $request) {
        return $this->basicScheme($request, 'cours', function($em, $page) {
            $importer = new CoursFileImporter($em); 
            $importer->import($_SERVER['DOCUMENT_ROOT'] . '/' . $page); 
            return array('page' => $_GET['page']); 
        }); 
    }
    
    /**
     * @Route("/xml/categories") 
     */
    public function xmlCategoriesImportAction(Request $request) {
        return $this->basicScheme(null, 'xmlCategory', function($em) {
            $importer = new XmlCategoryImporter($em, $_SERVER['DOCUMENT_ROOT']); 
            $importer->import('tutoriels'); 
            return array(); 
        }); 
    }
    
    /**
     * @Route("/xml/articles") 
     */
    public function xmlArticlesImportAction(Request $request) {
        return $this->basicScheme(null, 'xmlArticle', function($em) {
            $importer = new XmlArticleImporter($em, $_SERVER['DOCUMENT_ROOT']); 
            $importer->importFolder('tutoriels'); 
            return array(); 
        }); 
    }
    
    private function basicScheme($request, $prefix, $importerClosure) {
        $success = false; 
        $error   = false; 
        
        $sucTpl = 'TCIndexBundle:DefaultImporters:' . $prefix . 'ImporterSuccess.html.twig'; 
        $errTpl = 'TCIndexBundle:DefaultImporters:' . $prefix . 'ImporterError.html.twig'; 
        
        $em = $this->getDoctrine()->getEntityManager(); 
        $this->get('session')->clearFlashes();
        
        // File importers need the request (non-null $request); batch importers do not (null $request). 
        if (! $request || ($request->getMethod() == 'GET' && isset($_GET['page']))) {
            try {
                if(isset($_GET['page'])) {
                    $parameters = $importerClosure($em, $_GET['page']); 
                } else {
                    $parameters = $importerClosure($em); 
                }
                
                $success = $this->render($sucTpl, $parameters)->getContent(); 
            } catch(\Exception $e) {
                $error = $this->render($errTpl, array('error' => $e->getMessage()))->getContent();
            }
        } else {
            $error = $this->render($errTpl)->getContent(); 
        }
        
        if(! $error) {
            $this->get('session')->setFlash('success', $success); 
        } else {
            $this->get('session')->setFlash('error', $error); 
        }
        
        return $this->render('TCIndexBundle:DefaultImporters:importers.html.twig');
    }
}