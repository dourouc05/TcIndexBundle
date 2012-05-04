<?php

namespace TC\IndexBundle\Controller\User;

use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Admingenerated\TCIndexBundle\BaseUserController\ListController as BaseListController;
use TC\IndexBundle\Entity\User; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ListController extends BaseListController
{
    /**
     * @Route("/{pk}/switch-super-admin")
     */
    public function switchSuperAdminAction($pk) {
        $user = $this->getDoctrine()
                     ->getEntityManager()
                     ->createQuery('SELECT u FROM TCIndexBundle:User u WHERE u.id = :id')
                     ->setParameter('id', (int) $pk)
                     ->getSingleResult();
        
        $user->setSuperAdmin(! $user->isSuperAdmin()); 
        $this->getDoctrine()->getEntityManager()->persist($user); 
        $this->getDoctrine()->getEntityManager()->flush(); 
        
        return $this->redirect($this->generateUrl('TC_IndexBundle_User_list'));
    }
}
