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
     * @ORM\HasLifecycleCallbacks
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
		$this->position = -1; 
		$this->depth    = 0; 
    }
	
	public function __toString() {
		return $this->title; 
	}
    
    /** @PrePersist */
    public function prePersist() {
        // Handle depth subtleties (the child has a depth one unit greater than its parent, when it has one). 
        if(is_object($this->parent)) {
            $this->depth = $this->parent->depth + 1; 
        }
        
        // Handle position subtleties: if position is not set yet (-1), let's check what's in the table and take 
        // the next one. If it is set, don't touch unless the user wants to (handled by other methods)! 
        if($this->depth < 0) {
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