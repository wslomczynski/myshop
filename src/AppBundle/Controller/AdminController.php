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

         //////////////////////// -------------- cutting photo ----------------------------------//////////////////////

         $file = \imagecreatefromjpeg($path_to_save . "\\" . $filename);


         $x = imagesx($file);
         $y = imagesy($file);

         $tmp_x = 0;
         $tmp_y = 0;

         $final_x = 179;
         $final_y = 91;


         if($y<$x) $tmp_x = ceil(($x-$final_x*$y/$final_y)/2);
         elseif($x<$y) $tmp_y = ceil(($y-$final_y*$x/$final_x)/2);

         $resized_file = \imagecreatetruecolor($final_x, $final_y);
         \imagecopyresampled($resized_file, $file, 0, 0, $tmp_x, $tmp_y, $final_x, $final_y, $x-2*$tmp_x, $y-2*$tmp_y);

         \imagejpeg($resized_file, $path_to_save . "\\" . $filename, 100);

         //////////////////////// -------------- cutting photo -------------------------------////////////////////////////



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
