<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;

/**
 * Description of DefaultController
 *
 * @author Thibaut
 */
class DefaultController extends Controller
{
    /**
     * Cached for a year. 
     * 
     * @Route("/") 
     * @Cache(maxage="31536000", smaxage="31536000", expires="+1 year")
     */
    public function indexAction() {
        return $this->getIndexResponse(); 
    }
    
    /**
     * This is a natural URL, but not used by the application... Let's redirect. 
     * 
     * @Route("/index") 
     */
    public function subIndexAction() {
        return $this->redirect($this->generateUrl('tc_index_default_admin'), 301);
    }
    
    /**
     * @Route("/index/no-cache") 
     */
    public function indexNoCacheAction() {
        $sc = $this->get('security.context');
        var_dump($sc->isGranted(array(new Expression('hasRole("ROLE_ADMIN")'))));
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
