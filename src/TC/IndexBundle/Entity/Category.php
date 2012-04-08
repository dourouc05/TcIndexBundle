<?php

namespace TC\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TC\IndexBundle\Entity\Category
 *
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
     * @var integer $depth
     *
     * Private variable, used to indicate the depth in the category tree (automatically handled). 
     *
     * @ORM\Column(type="integer")
     */
    private $depth;

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
	
	/** 
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
	 */ 
	private $items; 


    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items    = new \Doctrine\Common\Collections\ArrayCollection();
		$this->position = 0; 
		$this->depth    = 0; 
    }
	
	public function __toString() {
		return $this->title; 
	}
    
    public function prePersist() {
        if(is_object($this->parent)) {
            $this->depth = $this->parent->depth + 1; 
        }
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
     * @param boolean $prefixed Should the title be prefixed according to depth (adding --)? 
     * @return string 
     */
    public function getTitle($prefixed = false)
    {
        if($prefixed) {
            $p = ''; 
            for($i = 0; $i < $this->depth; ++$i) {
                // Alt+0151 and Alt+0160 (emdash and nbsp). File must be encoded in UTF8 w/o BOM. 
                $p .= '— '; 
            }
            return $p . $this->title;
        } else {
            return $this->title;
        }
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
    public function setParent(Category $parent)
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

    /**
     * Get children
     *
     * @return Category
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get items
     *
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get depth
     *
     * @return integer
     */
    public function getDepth()
    {
        return $this->depth;
    }
    
    /**
     * Set parent
     *
     * @param Category $parent
     */
    private function setDepth($depth)
    {
        $this->depth = $depth;
    }
}