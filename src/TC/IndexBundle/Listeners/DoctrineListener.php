<?php 

namespace TC\IndexBundle\Listeners; 

use Doctrine\ORM\Event\LifecycleEventArgs;

class DoctrineListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        // For categories: must handle depth and position changes. 
        if ($entity instanceof \TC\IndexBundle\Entity\Category) {
            $entity->prePersist($entityManager); 
        }
    }
}