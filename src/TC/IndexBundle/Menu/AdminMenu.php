<?php

namespace TC\IndexBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AdminMenu
 *
 * @author Thibaut
 */
class AdminMenu {
    private $factory;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory) {
        $this->factory = $factory;
    }
    
    /**
     * @param Request $request
     */
    public function createAdminMenu(Request $request) {
        $menu = $this->factory->createItem('root');

        $menu->setCurrentUri($request->getRequestUri());
        
        $menu->setchildrenAttributes(array('id' => 'main_navigation', 'class' => 'menu'));
        
        // Main menu
        $help = $menu->addChild('Menu', array('uri' => '#'));
        $help->setLinkAttributes(array('class' => 'sub main'));
        $help->addChild('Accueil', array('route' => 'tc_index_default_admin'));
        $help->addChild('Importer', array('route' => 'tc_index_importer_index'));
        $help->addChild('Catégories', array('route' => 'TC_IndexBundle_Category_list'));
        $help->addChild('Items', array('route' => 'TC_IndexBundle_Item_list'));
        $help->addChild('Configuration', array('route' => 'tc_index_configuration_index'));
        $help->addChild('Vider les caches', array('route' => 'tc_index_configuration_emptycaches'));
        $help->addChild('Visualiser (avec caches)', array('route' => 'tc_index_default_index'));
        $help->addChild('Visualiser (sans caches)', array('route' => 'tc_index_default_indexnocache'));
        
        return $menu;
    }
}
