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
        $main = $menu->addChild('Actions', array('route' => 'tc_index_default_admin'));
        $main->setLinkAttributes(array('class' => 'sub main'));
        $main->addChild('Importer', array('route' => 'tc_index_importer_index'));
        $main->addChild('Catégories', array('route' => 'TC_IndexBundle_Category_list'));
        $main->addChild('Items', array('route' => 'TC_IndexBundle_Item_list'));
        $main->addChild('Utilisateurs', array('route' => 'TC_IndexBundle_User_list'));
        $main->addChild('Configuration', array('route' => 'tc_index_configuration_index'));
        $main->addChild('Vider les caches', array('route' => 'tc_index_configuration_emptycaches'));
        
        // Visualize
        $vis = $menu->addChild('Visualiser', array('route' => 'tc_index_default_indexnocache'));
        $vis->setLinkAttributes(array('class' => 'sub main'));
        $vis->addChild('Avec cache', array('route' => 'tc_index_default_index'));
        $vis->addChild('Sans cache', array('route' => 'tc_index_default_indexnocache'));
        
        // Logout
        $log = $menu->addChild('Déconnexion', array('route' => 'fos_user_security_logout'));
        $log->setLinkAttributes(array('class' => 'sub main'));
        
        return $menu;
    }
}
