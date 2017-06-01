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


    $noMoreProducts = false;

    $products_per_page = 8;


    $latest_post_id = 1;


    $repository = $this->getDoctrine()->getRepository('AppBundle:Product');
    $search_word = $request->query->get('search_word');




    $em = $this->getDoctrine()->getManager();
    $conn = $em->getConnection();
    //if($search_word){
    //  $products = $repository->findByName($search_word);
    //}
    $sql = "SELECT * FROM product WHERE id BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(1, $latest_post_id);
    $stmt->bindValue(2, $products_per_page);
    $stmt->execute();
    $products = $stmt->fetchAll();




    if($request->isXmlHttpRequest()){

        $latest_post_id = $request->get('latest_post_id');                      // getting highest data-post-id atrribute from product

        $latest_post_id = (int)$latest_post_id;                                 // convert latest_post_id form string to int
        $latest_post_id++;

        $query_helper = $products_per_page - 1;




        $stmt->bindValue(1, $latest_post_id);
        $stmt->bindValue(2, $latest_post_id + $query_helper);
        $stmt->execute();
        $products = $stmt->fetchAll();


        $html =  $this->renderView('products/next.html.twig',['next_products' => $products, 'latest_post_id' => $latest_post_id]); // rendering $html from twig template

        //return new Response($html);

        $items = array('html' => $html, 'noMoreProducts' => $noMoreProducts);
        return new JsonResponse($items);
        //return Response::create($html, 200,['noMoreProducts' => $noMoreProducts]);

      }

    return $this->render('products/index.html.twig',['products' => $products]);
  }

  /**
   * @Route("/products/add")
   */
   public function add(){


     $product = new Product();
     $product->setName("name2");
     $product->setPrice(22);
     $product->setDescription("desc");

     $em = $this->getDoctrine()->getManager();

     $em->persist($product);

     $em->flush();

     return new Response("added new product with id " . $product->getId());

    }



}
