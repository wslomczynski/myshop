<?php

namespace AppBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{

  /**
   * @Route("/products")
   */
  public function indexAction(Request $request){

    $noMoreProducts = false;
    $category = 1;

    $products_per_page = 8;



    $repository = $this->getDoctrine()->getRepository('AppBundle:Product');
    $repository_image = $this->getDoctrine()->getRepository('AppBundle:ProductImage');
    $repository_category = $this->getDoctrine()->getRepository('AppBundle:ProductCategory');
    $search_word = $request->query->get('search_word');


    if($search_word){
      $products = $repository->findByName($search_word);
    } else {
      $products = $repository->findAll();
    }





    $firstProducts = array_slice($products,0,$products_per_page);

    if($request->isXmlHttpRequest()){

      $latest_post_id = $request->get('latest_post_id');                      // getting highest data-post-id atrribute from products
      $latest_post_id = (int)$latest_post_id;                                 // convert latest_post_id form string to int
      $latest_post_id++;

      $next_products = array_slice($products,$latest_post_id,$products_per_page);



      $html =  $this->renderView('products/next.html.twig',['next_products' => $next_products, 'latest_post_id' => $latest_post_id]); // rendering $html from twig template

        //return new Response($html);

      $items = array('html' => $html, 'noMoreProducts' => $noMoreProducts);
      return new JsonResponse($items);
        //return Response::create($html, 200,['noMoreProducts' => $noMoreProducts]);

      }




    if($firstProducts){
      return $this->render('products/index.html.twig',['products' => $firstProducts]);
    }

    return new Response("no products available, go to admin panel to add a product");


  }




}
