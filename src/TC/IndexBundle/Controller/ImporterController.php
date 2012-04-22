<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;
use TC\IndexBundle\Importer\HtmlFileImporter;
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
}
