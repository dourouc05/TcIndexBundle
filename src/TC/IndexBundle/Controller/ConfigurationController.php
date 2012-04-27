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
    /**
     * @Route("/") 
     */
    public function indexAction(Request $request) {
        $options = $this->getDoctrine()
                        ->getRepository('TCIndexBundle:Configuration')
                        ->findAll();
        
        if(count($options) == 0) {
            $options[] = new Configuration('rubrique_id', 'Numéro de la rubrique', '1');
            $options[] = new Configuration('licence', 'Licence', '1');
            $options[] = new Configuration('licence_auteur', 'Auteur', 'Thibaut Cuvelier');
            $options[] = new Configuration('titre', 'Titre de la page', 'Index');
            $options[] = new Configuration('mots-cles', 'Mots-clés', 'Index');
            $options[] = new Configuration('description', 'Description', 'Index');
            
            foreach($options as $o) {
                $this->getDoctrine()->getEntityManager()->persist($o); 
            }
            $this->getDoctrine()->getEntityManager()->flush(); 
        }
        
        if ($request->getMethod() == 'POST') {
            foreach($options as $o) {
                $o->setValue($_POST[$o->getField()]); 
                $this->getDoctrine()->getEntityManager()->persist($o); 
            }
            $this->getDoctrine()->getEntityManager()->flush(); 
        }
        
        return $this->render('TCIndexBundle:Default:configuration.html.twig', array('options' => $options));
    }
    
    /**
     * @Route("/empty-caches") 
     */
    public function emptyCachesAction() {
        return new Response(); 
    }
}
