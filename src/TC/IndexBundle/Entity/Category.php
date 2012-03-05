<?php

namespace TC\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TC\IndexBundle\Entity\Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
{
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var integer $position
     *
     * @ORM\Column(type="integer")
     */
    private $position;
	
	/** 
	 * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
	 */ 
	private $children; 
	
	/**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     */
    private $parent;


    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	public function __toString() {
		return $this->title; 
	}
	

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set position
     *
     * @param integer $title
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set parent
     *
     * @param Category $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }
}