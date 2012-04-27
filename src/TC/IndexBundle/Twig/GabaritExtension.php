<?php

namespace TC\IndexBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Bundle\DoctrineBundle\Registry; 

/**
 * A Twig extension for Developpez.com template. 
 * 
 * Many comments for the database part (say there will be no need to make it
 * useable everywhere, but still here "just in case"). 
 *
 * @author Thibaut
 */
class GabaritExtension extends \Twig_Extension
{
    private $options; 
    
    function __construct(Registry $doctrine) {
        $options = $doctrine->getRepository('TCIndexBundle:Configuration')
                            ->findAll(); 
        
        $this->options = array(); 
        foreach($options as $o) {
            $this->options[$o->getField()] = $o->getValue();
        }
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'gabarit';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'gab_up'      => new \Twig_Function_Method($this, 'gabUp',   array('is_safe' => array('html'))),
            'gab_down'    => new \Twig_Function_Method($this, 'gabDown', array('is_safe' => array('html')))
        );
    }
    
    public function gabUp()
    {
        ob_start();
        $rubrique = $this->options['rubrique_id']; 
        $gabarit_utf8 = false; 
        $titre_page = $this->options['titre']; 
        $meta_description = $this->options['description']; 
        $meta_keywords = $this->options['mots-cles']; 
        $urlCss = './css.css';
        include $_SERVER['DOCUMENT_ROOT'] . '/template/entete.php'; 
        return utf8_encode(ob_get_clean()); 
    }
    
    public function gabDown()
    {
        ob_start();
        $rubrique = $this->options['rubrique_id']; 
        $fichierCachePied = $_SERVER['DOCUMENT_ROOT'] . '/template/caches/piedxhtml' . $rubrique . '.cache'; 
        $Licence = $this->options['licence']; 
        $gabarit_utf8 = true; 
        $Auteur = $this->options['licence_auteur']; 
        $Annee = date('Y'); 
        include $_SERVER['DOCUMENT_ROOT'] . '/template/pied.php'; 
        return ob_get_clean(); 
    }
}
