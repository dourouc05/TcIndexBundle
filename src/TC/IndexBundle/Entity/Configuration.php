<?php

namespace TC\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Configuration
 *
 * @author Thibaut
 * 
 * @ORM\Entity
 */
class Configuration {
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id; 
    
    /**
     * @var string $field
     *
     * The field. 
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $field; 

    /**
     * @var string $value
     *
     * The field's value. 
     *
     * @ORM\Column(type="string", length=255)
     */
    private $value; 

    /**
     * @var string $title
     *
     * The field's human-readable title. 
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title; 
    
    public function __construct($field, $title, $value) {
        $this->field = $field; 
        $this->title = $title; 
        $this->value = $value; 
    }
    
    public function getField() {
        return $this->field; 
    }
    
    public function setField($field) {
        $this->field = $field; 
    }
    
    public function getTitle() {
        return $this->title; 
    }
    
    public function setTitle($title) {
        $this->title = $title; 
    }
    
    public function getValue() {
        return $this->value; 
    }
    
    public function setValue($value) {
        $this->value = $value; 
    }
}
