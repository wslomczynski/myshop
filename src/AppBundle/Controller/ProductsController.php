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
  public function index(Request $request){

    $products_per_page = 8;

    $repository = $this->getDoctrine()->getRepository('AppBundle:Product');     // getting acces to product table
    $products = $repository->findAll();                                         // select * from product;



    if($request->isXmlHttpRequest()){                                           // if request == ajax

        //$request = $this->container->get('request');
        $latest_post_id = $request->get('latest_post_id');                      // getting highest data-post-id atrribute from product

        $latest_post_id = (int)$latest_post_id;                                 // convert latest_post_id form string to int
        $latest_post_id++;                                                      // incrementing to target next "wave" of products

        $next_products = [];

        $noMoreProducts = false;

      for($i=$latest_post_id ; $i< $latest_post_id + $products_per_page ; $i++){ // creating new array and pushing products to array from
                                                                                // latest_post_id to products per page
          if($i >= count($products)){
            $noMoreProducts = true;
            break;
          }
            array_push($next_products,$products[$i]);
          }



        $html =  $this->renderView('products/next.html.twig',['next_products' => $next_products, 'latest_post_id' => $latest_post_id]); // rendering $html from twig template

        return new Response($html);


    }



    $products = array_slice($products, 0, $products_per_page);


    return $this->render('products/index.html.twig',['products' => $products]);
  }

  /**
   * @Route("/products/add")
   */
   public function add(){


     $product = new Product();
     $product->setName("macbookpro");
     $product->setPrice(22);
     $product->setDescription("desc");

     $em = $this->getDoctrine()->getManager();

     $em->persist($product);

     $em->flush();

     return new Response("added new product with id " . $product->getId());

    }

}
