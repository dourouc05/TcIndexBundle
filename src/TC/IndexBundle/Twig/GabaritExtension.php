<?php

namespace TC\IndexBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

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
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'gabarit4';
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
        $rubrique = 65; 
        $gabarit_utf8 = false; 
        $titre_page = $meta_description = $meta_keywords = 'Index'; 
        $urlCss = './css.css';
        include $_SERVER['DOCUMENT_ROOT'] . '/template/entete.php'; 
        return utf8_encode(ob_get_clean()); 
    }
    
    public function gabDown()
    {
        ob_start();
        $rubrique = 65; 
        $fichierCachePied = $_SERVER['DOCUMENT_ROOT'] . '/template/caches/piedxhtml65.cache'; 
        $Licence = '1'; 
        $gabarit_utf8 = true; 
        $Auteur = 'Thibaut Cuvelier';
        $Annee = date('Y'); 
        include $_SERVER['DOCUMENT_ROOT'] . '/template/pied.php'; 
        return ob_get_clean(); 
    }
}
