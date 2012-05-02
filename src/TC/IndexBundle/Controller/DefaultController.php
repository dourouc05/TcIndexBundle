<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Description of DefaultController
 *
 * @author Thibaut
 */
class DefaultController extends Controller
{
    /**
     * @Route("/") 
     * @Cache(expires="+1 year")
     */
    public function indexAction() {
        return $this->getIndexResponse(); 
    }
    
    /**
     * @Route("/index/no-cache") 
     */
    public function indexNoCacheAction() {
        return $this->getIndexResponse(); 
    }
    
    private function getIndexResponse() {
        $cats = $this->getDoctrine()
                     ->getRepository('TCIndexBundle:Category')
                     ->findByDepth(0);
        return $this->render('TCIndexBundle:ThemeDefault:index.html.twig', array('categories' => $cats));
    }
    
    /**
     * @Route("/index/admin") 
     */
    public function adminAction() {
        return $this->render('TCIndexBundle:Default:admin.html.twig');
    }
}
