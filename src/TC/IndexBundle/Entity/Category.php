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
    // Below: useful fields. 
    
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
     * The category's title. 
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var text $text
     * 
     * The category's text (may be HTML). 
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;
    
    // Below: tree management fields. 

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
    
    // NO ORM! Simple variable indicating what to do when persisting about position (push one level higher or lower). 
    // The reason to do it when persisting is you have the EM. It is not possible to be pushed more than one level. 
    //   -: go below. 
    //   0: don't move. 
    //   +: go above. 
    private $positionPush = 0; 
	
	/** 
	 * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
	 */ 
	private $children; 
	
	/**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", fetch="EAGER")
     * 
     *///@ORM\OrderBy({"position" = "ASC"})
    private $parent;
	
	/** 
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
	 */ 
	private $items; 

    /**
     * @var integer $order
     *
     * Self-generated value for sorts
     *
     * @ORM\Column(type="integer", name="disorder")
     */
    private $order;


    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items    = new \Doctrine\Common\Collections\ArrayCollection();
		$this->position = -1; 
		$this->depth    = 0; 
    }
	
	public function __toString() {
		return (string) $this->getTitle(true); 
	}
    
    // Explicitely called by the listener. 
    public function prePersist($em) {
        $em->flush(); // Required for the requests to work. 
        
        // Handle depth subtleties (the child has a depth one unit greater than its parent, when it has one). 
        if(is_object($this->parent)) {
            $this->depth = $this->parent->depth + 1; 
        }
        
        // Handle position subtleties: if position is not set yet (-1), let's check what's in the table and take 
        // the next one. If it is set, don't touch unless the user wants to (handled by other methods)! 
        if($this->position < 0) {
            
            // WHERE ... = NULL is not valid DQL. 
            if($this->parent == NULL) {
                $q = $em->createQuery('SELECT COUNT(c) FROM TCIndexBundle:Category c WHERE c.parent IS NULL AND c.position > -1'); 
            } else {
                $q = $em->createQuery('SELECT COUNT(c) FROM TCIndexBundle:Category c WHERE c.parent = :parent AND c.position > -1')
                        ->setParameter('parent', $this->parent); 
            }
            
            $this->position = (int) $q->getSingleScalarResult(); 
        }
        
        // Change positions. 
        if($this->positionPush != 0) {
            // Prepare the common part of the request. 
            if($this->parent == NULL) {
                $q = $em->createQuery('SELECT c FROM TCIndexBundle:Category c WHERE c.parent IS NULL AND c.position = :pos'); 
            } else {
                $q = $em->createQuery('SELECT c FROM TCIndexBundle:Category c WHERE c.parent = :parent AND c.position = :pos')
                        ->setParameter('parent', $this->parent); 
            }
        
            // Push me above
            if($this->positionPush > 0 && $this->position > 0) {
                // Get the one above, exchange positions. 
                // This request could fail: there may be no other item. 
                try {
                    $q = $q->setParameter('pos', $this->position - 1)
                           ->getSingleResult(); 
                }
                catch(\Exception $e) { return; }
                $this->position -= 1; 
                $q->position    += 1; 
            }
            
            // Push me below
            else {
                // Get the one below, exchange positions.
                // This request may fail: there may not be any item lower. 
                try {
                    $q = $q->setParameter('pos', $this->position + 1)
                           ->getSingleResult(); 
                }
                catch(\Exception $e) { return; }
                $this->position += 1; 
                $q->position    -= 1; 
            }
            
            $this->positionPush = 0; 
            $em->persist($q); 
            $em->persist($this); 
            $em->flush(); 
        }
        
        // Update order. 
        $this->getOrder(); 
            $em->persist($this); 
            $em->flush(); 
        var_dump(4);
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
     * Set text
     *
     * @param integer $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set position
     *
     * @param integer $title
     */
    private function setPosition($position)
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
    
    public function pushAbove() {
        $this->positionPush = 1;
    }
    
    public function pushBelow() {
        $this->positionPush = -1;
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
    
    // Returns 20 ^ (6 - depth) - max depth: 6, as depth increases when going down the tree.
    public function getDepthExponent() {
        return pow(20, 6 - $this->depth);
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

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        if(isset($this->parent)) {
            return $this->order = $this->parent->getOrder() + $this->getDepthExponent() * ($this->position + 1); 
        } else {
            return $this->order = $this->getDepthExponent() * ($this->position + 1); 
        }
    }
    
    /**
     * Set parent
     *
     * @param integer $order
     */
    private function setOrder($order)
    {
        $this->order = $order;
    }
}
