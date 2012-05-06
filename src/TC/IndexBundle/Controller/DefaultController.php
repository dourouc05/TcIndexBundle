<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
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
     */
    // * @Cache(maxage="31536000", smaxage="31536000", expires="+1 year")
    public function indexAction() {
        return $this->getIndexResponse(); 
    }
    
    /**
     * @Route("/index/no-cache") 
     */
    public function indexNoCacheAction() {
        return $this->getIndexRender(); 
    }
    
    /**
     * This is a natural URL, but not used by the application... Let's redirect. 
     * 
     * @Route("/index") 
     */
    public function subIndexAction() {
        return $this->redirect($this->generateUrl('tc_index_default_admin'), 301);
    }
    
    private function getIndexResponse() {
        if(function_exists('xcache_get')) {
            $cacheDriver = new \Doctrine\Common\Cache\XcacheCache();
            if($cacheDriver->contains('TCIndexBundle')) {
                return new Response($cacheDriver->fetch('TCIndexBundle')); 
            } else {
                $r = $this->getIndexRender();
                $cacheDriver->save('TCIndexBundle', $r->getContent()); 
                return $r; 
            }
        } else {
            return $this->getIndexRender();
        }
    }
    
    private function getIndexRender() {
        $cats = $this->getDoctrine()
                     ->getRepository('TCIndexBundle:Category')
                     ->findByDepth(0);
        try {
            $theme = $this->getDoctrine()
                        ->getRepository('TCIndexBundle:Configuration')
                        ->findOneByField('theme')
                        ->getValue(); 
        } catch(\Exception $e) {
            $theme = 'Default';
        }
                
        return $this->render('TCIndexBundle:Theme' . $theme . ':index.html.twig', array('categories' => $cats));
    }
    
    /**
     * @Route("/index/admin") 
     */
    public function adminAction() {
        return $this->render('TCIndexBundle:Default:admin.html.twig');
    }
}
