<?php

namespace TC\IndexBundle\Controller\Category;

use Admingenerated\TCIndexBundle\BaseCategoryController\ListController as BaseListController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ListController extends BaseListController
{
    private $em; 
    private $q; 
    
    protected function getQuery() {
        $query = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQueryBuilder()
                    ->select('c')
                    ->from('TC\IndexBundle\Entity\Category', 'c')
                    ->orderBy('c.order', 'ASC');

        $this->processSort($query);
        $this->processFilters($query);
        
        return $query->getQuery();
    }

    /**
     * @Route("/{pk}/move-up")
     */
    public function upAction($pk) {
        $id = $pk; 
        $this->getResult($id); 
        $this->q->pushAbove(); 
        return $this->end(); 
    }

    /**
     * @Route("/{pk}/move-down")
     */
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
