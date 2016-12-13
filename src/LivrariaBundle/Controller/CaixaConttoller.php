<?php

namespace LivrariaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use LivrariaBundle\Entity\Cupom;
use \LivrariaBundle\Entity\CupomItem;

/**
 * Description of CaixaConttoller
 *
 * @author aluno
 */
class CaixaConttoller  extends Controller
{
   /**
    * @Route("/caixa", name="caixa-index")
    */
    public function pdvAction(Request $request)
   {
        $em = $this->getDoctrine()->GetManager();
        
        $cupom = new Cupom();
        $cupom->setData(new \DateTime());
        $cupom->setValorTotal(0);
        $cupom->setVendedor(1);
        
        $em->persist($cupom);
        $em->flush();
        
        $request->getSession()->set("cupom-id", $cupom->getId());
        
        
        return $this->render("LivrariaBundle:Caixa:pdv.html.twig"); 
                
   }
   
    /**
    * @Route("/caixa/carregar")
    * @Method("POST")
    */
   public function carregarProdutoAction(Request $request)
   {
       $em = $this->getDoctrine()->GetManager();
       
       $codProd = $em = $this->getDoctrine()->GetManager();
       
       $produto = $em->getRepositoy("LivrariaBundle:Produtos")
               ->find($codProd);
       if ($produto == null)
           {
           return $this->json('erro');
       } 
       
       $novoItem = new CupomItem();
       $novoItem->setCupomId($request->getSession()->get('cupom-id'));
       $novoItem->setDescricaoItem($produto->getNome());
       $novoItem->setItemCod($codProd);
       $novoItem->setQuantidade(1);
       $novoItem->setValorUnitario($produto->getPreco());
       
       $em->persist($cupom);
       $em->flush();
    
        return $this->json("Ok");
       
   }
   
    /**
    * @Route("/caixa/estorno/{item}")
    */
   public function estornarItemAction(Request $request, $item)
   {
        $cupomId = $request->getSession()->get(cupom-id);
    
        $em = $this->getDoctrine()->GetManager();
        
        $itemOld = $em->getRepository("LivrariaBundle:CupomItem")
                ->findBy(array(
                   'cupomId' => $cupomId,
                    'ordemItem' => $item
                    
                ));
        $itemEstorno = new CupomItem();
        $itemEstorno->setCupomId($cupomId);
        $itemEstorno->setDescricaoItem("Estorno do Item: $item");
        $itemEstorno->setItemCod(1001);
        $itemEstorno->setQuantidade(1);
        $itemEstorno->setValorUnitario($item->getValorUnitario() * -1);
        
        $em->persist($cupom);
        $em->flush();
    
        return $this->json("Ok");
               
   }
   
     /**
    * @Route("/caixa/cancelar")
    */
   public function cancelarVendaAction(Request $request)
   {
    $cupomId = $request->getSession()->get(cupom-id);
    
    $em = $this->getDoctrine()->GetManager();
    $cupom = $em->getRepository("LivrariaBundle:Cupom")->find($em);
    
    $cupom->setStatus("CANCELADO");
    
    $em->persist($cupom);
    $em->flush();
    
    return $this->redirectToRoute('caixa-index');
   }
   
    /**
    * @Route("/caixa/finalizar", name="concluir")
    */
   public function finalizarVendaAction(Request $request)
   {
    $cupomId = $request->getSession()->get(cupom-id);
    
    $em = $this->getDoctrine()->GetManager();
    $cupom = $em->getRepository("LivrariaBundle:Cupom")->find($em);
    
    $cupom->setStatus("FINALIZADO");
    
    $em->persist($cupom);
    $em->flush();
    
    return $this->json("Ok");
    
    // baixar no estoque
    //fechar total
      
   }
   
    /**
    * @Route("/caixa/listar")
    */
     public function listarItensAction(Request $request)
   {
        $em = $this->getDoctrine()->GetManager();
        $itens = $em->getRepository("LivrariaBundle:CupomItem")
                ->findBy(array(
                   "cupomId"=> $request->getSession()->get("cupom-id")
                ));
        
        return  $this->json($itens);
         
   }
   
   
}
