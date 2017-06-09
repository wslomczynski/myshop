<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\FileBag;
class AdminController extends Controller
{


  /**
   * @Route("/admin", name="admin")
   */
   public function index(Request $request){
        $file = $request->files->get('file');

       if($file){

         $path_to_save = $this->get('kernel')->getRootDir() . '\..\web\uploadedImages';
         $filename = uniqid().".".$file->getClientOriginalExtension();
         $file->move($path_to_save,$filename);

       }


     //$request->get('file');
      return $this->render('admin/index.html.twig');
   }
}
