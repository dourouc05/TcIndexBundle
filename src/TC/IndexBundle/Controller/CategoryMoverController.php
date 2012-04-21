<?php

namespace TC\IndexBundle\Controller;

use TC\IndexBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryMoverController extends Controller
{
    private $em; 
    private $q; 

    public function upAction($pk)
    {
        $id = $pk; 
        $this->getResult($id); 
        $this->q->pushAbove(); 
        return $this->end(); 
    }
    
    public function downAction($pk)
    {
        $id = $pk; 
        $this->getResult($id); 
        $this->q->pushBelow(); 
        return $this->end(); 
    }
    
    private function getResult($id) {
        $this->em = $this->getDoctrine()->getEntityManager(); 
        $this->q  = $this->em->createQuery('SELECT c FROM TCIndexBundle:Category c WHERE c.id = :id')
                         ->setParameter('id', $id)
                         ->getSingleResult();
    }
    
    private function end() {
        $this->q->prePersist($this->em);
        $this->em->persist($this->q);
        $this->em->flush();
        
        return $this->redirect($this->generateUrl('TC_IndexBundle_Category_list'));
    }
}
