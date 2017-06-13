<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\FileBag;

use AppBundle\Entity\ProductImage;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductCategory;


class AdminController extends Controller
{


  /**
   * @Route("/admin", name="admin")
   */
   public function index(Request $request){

        
        $product_repository = $this->getDoctrine()->getRepository('AppBundle:Product');
        $image_repository = $this->getDoctrine()->getRepository('AppBundle:ProductImage');
        $category_repository = $this->getDoctrine()->getRepository('AppBundle:ProductCategory');
        $em = $this->getDoctrine()->getManager();

        $params = $request->request->all();


        $file = $request->files->get('file');


       if($file){

         $img = new ProductImage();
         $img->setName($file->getClientOriginalName());
         $em->persist($img);
         $em->flush();


         $path_to_save = $this->get('kernel')->getRootDir() . '\..\web\uploadedImages';
         $filename = $file->getClientOriginalName();
         $file->move($path_to_save,$filename);

         $params['category'] = $category_repository->find(intval($params['category']));

         //$category = new ProductCategory();
         $product = new Product();
         $product->setName($params['name']);
         $product->setPrice($params['price']);
         $product->setDescription($params['description']);

         $product->setCategory($params['category']);
         $product->setImage($img);


         $em->persist($product);
         $em->flush();


       }



      return $this->render('admin/index.html.twig',['categories' => $category_repository->findAll()]);
   }
}
