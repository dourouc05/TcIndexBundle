<?php

namespace TC\IndexBundle\Importer;

/**
 * @author Thibaut
 */
class NonExistantFileException extends \Exception {
    public function __construct($prefix, $file, $code = 0, Exception $previous = null) {
        parent::__construct('Importation de fichier ' . $prefix . ' : le fichier <code>' . $file . '</code> n\'existe pas. ', $code, $previous);
    }
}
