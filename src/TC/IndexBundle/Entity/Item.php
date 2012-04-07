<?php

namespace TC\IndexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TC\IndexBundle\Entity\Item
 *
 * @ORM\Entity
 */
class Item
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
     * @var string $url
     *
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var string $synopsis
     *
     * @ORM\Column(type="text")
     */
    private $synopsis;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
     */
    private $category;
	
	
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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set synopsis
     *
     * @param text synopsis
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;
    }

    /**
     * Get synopsis
     *
     * @return text
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * Set category
     *
     * @param Category category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}