<?php

namespace TC\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TCIndexBundle:ThemeDefault:index.html.twig', array('name' => '4'));
    }
}
