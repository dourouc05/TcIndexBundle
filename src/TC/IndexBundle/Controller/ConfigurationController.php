<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TC\IndexBundle\Entity\Configuration;

/**
 * Description of ConfigurationController
 *
 * @author Thibaut
 */
class ConfigurationController extends Controller {
    public static $availableOptions = 
            array(
                // Field, description, default value. 
                array('rubrique_id', 'Numéro de la rubrique', '1'), 
                array('licence', 'Licence', '1'), 
                array('licence_auteur', 'Auteur', 'Thibaut Cuvelier'), 
                array('titre', 'Titre de la page', 'Index'), 
                array('mots-cles', 'Mots-clés', 'Index'), 
                array('description', 'Description', 'Index'), 
                );
    
    /**
     * @Route("/") 
     */
    public function indexAction(Request $request) {
        $options = $this->getDoctrine()
                        ->getRepository('TCIndexBundle:Configuration')
                        ->findAll();
        
        if ($request->getMethod() == 'POST') {
            foreach($options as $o) {
                $o->setValue($_POST[$o->getField()]); 
                $this->getDoctrine()->getEntityManager()->persist($o); 
            }
            $this->getDoctrine()->getEntityManager()->flush(); 
        }
        
        if(count($options) != count(self::$availableOptions)) {
            foreach(self::$availableOptions as $c) {
                $continue = false; 
                foreach($options as $o) {
                    if($o->getField() == $c[0]) {
                        $continue = true;
                        break; 
                    }
                }
                
                if($continue) {
                    continue; 
                }
                
                $o = new Configuration($c[0], $c[1], $c[2]);
                $this->getDoctrine()->getEntityManager()->persist($o); 
                $options[] = $o; 
            }
            
            $this->getDoctrine()->getEntityManager()->flush(); 
        }
        
        return $this->render('TCIndexBundle:Default:configuration.html.twig', array('options' => $options));
    }
    
    /**
     * @Route("/empty-caches") 
     */
    public function emptyCachesAction() {
        $this->emptyDir($this->get('kernel')->getCacheDir());
        file_get_contents('http://' . $_SERVER['SERVER_NAME'] . str_replace('/empty-caches', '/', $_SERVER['REQUEST_URI'])); // warm up
        return $this->render('TCIndexBundle:Default:cacheEmptied.html.twig'); 
    }
    
    private function emptyDir($dir, $deleteSelf = false) {
        if(!$dh = @opendir($dir)) {
            return;
        }
        
        while (false !== ($obj = readdir($dh))) {
            if($obj=='.' || $obj=='..') {
                continue;
            }
            if (!@unlink($dir.'/'.$obj)) {
                $this->emptyDir($dir.'/'.$obj, true);
            }
        }

        closedir($dh);
        
        if ($deleteSelf){
            @rmdir($dir);
        }
    }
}
