<?php

namespace ElasticaClient\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ElasticaClientFrontendBundle:Default:index.html.twig');
    }
}
