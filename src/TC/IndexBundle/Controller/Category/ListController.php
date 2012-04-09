<?php

namespace TC\IndexBundle\Controller\Category;

use Admingenerated\TCIndexBundle\BaseCategoryController\ListController as BaseListController;

class ListController extends BaseListController
{
    protected function getQuery()
    {
        $query = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQueryBuilder()
                    ->select('c')
                    ->from('TC\IndexBundle\Entity\Category', 'c')
                    ->orderBy('c.position', 'ASC');

        $this->processSort($query);
        $this->processFilters($query);
        
        return $query->getQuery();
    }
}
