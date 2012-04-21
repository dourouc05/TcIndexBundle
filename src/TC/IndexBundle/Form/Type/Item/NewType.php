<?php

namespace TC\IndexBundle\Form\Type\Item;

use Admingenerated\TCIndexBundle\Form\BaseItemType\NewType as BaseNewType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;

class NewType extends AbstractType//BaseNewType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
           $builder->add('title', 'text', array(  'required' => true,));
           $builder->add('url', 'text', array(  'required' => false,));
           $builder->add('synopsis', 'textarea', array(  'required' => false,));
           $builder->add('category', 'entity', array(  'em' => 'default',  'class' => 'TC\\IndexBundle\\Entity\\Category',  'multiple' => false,  'required' => false,
                                                        'query_builder' => function(\Doctrine\ORM\EntityRepository $r) {
                                                                return $r->createQueryBuilder('c')->add('orderBy', 'c.order ASC');
                                                        }));
    }

    public function getName()
    {
        return 'new_item';
    }

    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
    }
}
