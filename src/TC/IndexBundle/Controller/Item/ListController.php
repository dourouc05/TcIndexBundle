<?php

namespace TC\IndexBundle\Controller\Item;

use Admingenerated\TCIndexBundle\BaseItemController\ListController as BaseListController;

class ListController extends BaseListController
{
    protected function getQuery()
    {
        $query = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQueryBuilder()
                    ->select('i')
                    ->from('TC\IndexBundle\Entity\Item', 'i')
                    ->join('i.category', 'c')
                    ->orderBy('c.order', 'ASC');

        $this->processSort($query);
        $this->processFilters($query);
        
        return $query->getQuery();
    }
}
