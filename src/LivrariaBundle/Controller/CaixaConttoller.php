<?php

namespace LivrariaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CaixaConttoller
 *
 * @author aluno
 */
class CaixaConttoller  extends Controller
{
   /**
    * @Route("/caixa")
    */
    public function pdvAction()
   {
        return $this->render("LivrariaBundle:Caixa:pdv.html.twig"); 
                
   }
}
