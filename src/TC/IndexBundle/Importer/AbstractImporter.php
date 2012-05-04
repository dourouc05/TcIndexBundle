<?php

namespace TC\IndexBundle\Importer;

use Doctrine\Common\Persistence\ObjectManager; 

abstract class AbstractImporter {
    protected $om; 

    function __construct(ObjectManager $om) {
        $this->om = $om; 
    }
    
    abstract function import($what); 
}