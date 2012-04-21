<?php

namespace TC\IndexBundle\Form\Type\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;
use Doctrine\ORM\EntityRepository; 

class FiltersType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('title', 'text', array('required' => false));
        $builder->add('parent', 'entity', array(
                                                    'em' => 'default', 
                                                    'class' => 'TC\\IndexBundle\\Entity\\Category', 
                                                    'query_builder' => function(EntityRepository $er) {
                                                        return $er->createQueryBuilder('c')
                                                                  // ->orderBy('c.parent.position', 'ASC')
                                                                  // ->orderBy('c.position', 'ASC')
                                                                  ->orderBy('c.order', 'ASC')
                                                                  ;
                                                    }, 
                                                    'multiple' => false, 
                                                    'required' => false
                                               )
                     );
    }

    public function getName()
    {
        return 'filters_category';
    }

    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
    }
}
