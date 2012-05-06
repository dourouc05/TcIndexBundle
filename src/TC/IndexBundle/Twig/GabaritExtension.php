<?php

namespace TC\IndexBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Bundle\DoctrineBundle\Registry; 
use TC\IndexBundle\Controller\ConfigurationController; 

/**
 * A Twig extension for Developpez.com template. 
 * 
 * Many comments for the database part (say there will be no need to make it
 * useable everywhere, but still here "just in case"). 
 *
 * @author Thibaut
 */
class GabaritExtension extends \Twig_Extension {
    private $options; 
    
    function __construct(Registry $doctrine) {
        $options = $doctrine->getRepository('TCIndexBundle:Configuration')
                            ->findAll(); 
        
        $this->options = array(); 
        foreach($options as $o) {
            $this->options[$o->getField()] = $o->getValue();
        }
        
        if(count($options) != count(ConfigurationController::$availableOptions)) {
            foreach(ConfigurationController::$availableOptions as $o) {
                if(! array_key_exists($o[0], $this->options)) {
                    $this->options[$o[0]] = $o[2];
                }
            }
        }
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'gabarit';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'gab_up'        => new \Twig_Function_Method($this, 'gabUp',       array('is_safe' => array('html'))),
            'gab_down'      => new \Twig_Function_Method($this, 'gabDown',     array('is_safe' => array('html'))), 
            'gab_up_body'   => new \Twig_Function_Method($this, 'gabUpBody',   array('is_safe' => array('html'))), 
            'gab_down_body' => new \Twig_Function_Method($this, 'gabDownBody', array('is_safe' => array('html'))), 
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFilters() {
        return array(
            'gab_up' => new \Twig_Filter_Method($this, 'gabUp', array('is_safe' => array('html'))),
        );
    }
    
    public function gabUp($css = '') {
        ob_start();
        $rubrique = $this->options['rubrique_id']; 
        $gabarit_utf8 = true; 
        $titre_page = $this->options['titre']; 
        // Administration pages are accessed by /... URLs, don't show the same title. 
        if(isset($_SERVER['PATH_INFO'])) {
            $titre_page = '[Administration] ' . $titre_page; 
        } 
        $meta_description = $this->options['description']; 
        $meta_keywords = $this->options['mots-cles']; 
        $urlCss = $css;
        include $_SERVER['DOCUMENT_ROOT'] . '/template/entete.php'; 
        return utf8_encode(ob_get_clean()); 
    }
    
    public function gabDown() {
        ob_start();
        $rubrique = $this->options['rubrique_id']; 
        $fichierCachePied = $_SERVER['DOCUMENT_ROOT'] . '/template/caches/piedxhtml' . $rubrique . '.cache'; 
        $Licence = $this->options['licence']; 
        $gabarit_utf8 = true; 
        $Auteur = $this->options['licence_auteur']; 
        $Annee = date('Y'); 
        $GoogleAnalytics = $this->options['google-analytics'];
        include $_SERVER['DOCUMENT_ROOT'] . '/template/pied.php'; 
        return ob_get_clean(); 
    }
    
    public function gabUpBody() {
        return utf8_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/template/caches/tetexhtml' . $this->options['rubrique_id'] . '.cache'));
    }
    
    public function gabDownBody() {
        return utf8_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/template/caches/piedxhtml' . $this->options['rubrique_id'] . '.cache'));
    }
}
