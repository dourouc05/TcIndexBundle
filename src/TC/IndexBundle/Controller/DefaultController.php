<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of DefaultController
 *
 * @author Thibaut
 */
class DefaultController extends Controller
{
    /**
     * @Route("/") 
     */
    public function indexAction() {
        $cats = $this->getDoctrine()
                     ->getRepository('TCIndexBundle:Category')
                     ->findByDepth(0);
        return $this->render('TCIndexBundle:ThemeDefault:index.html.twig', array('categories' => $cats));
    }
    
    /**
     * @Route("/index/no-cache") 
     */
    public function indexNoCacheAction() {
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
