<?php

namespace TC\IndexBundle\Importer;

use TC\IndexBundle\Entity\Category;
use TC\IndexBundle\Entity\Item;

/**
 * Imports a "standard" DvpXML article in the categories indicated by their subfolder. 
 * 
 * For example, if root is /tutoriels/, then, /tutoriels/qt/ being the path for the Qt category, 
 * the article /tutoriels/qt/1/ will be stored in this category; if there is a category Qt 3D 
 * with the path /tutoriels/qt/3d/, then all tutorials in this folder will be put in that category. 
 * 
 * Category importation are made in the XmlCategoriesImporter class. 
 *
 * @author Thibaut
 */
class XmlArticleImporter extends AbstractImporter {
    public function import($file) {
        
    }
}