<?php

namespace App\Http\Controllers;

use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use App\FirmasElectronicas;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;




class ImageUploadController extends Controller
{
    //

    public function home(){

    }

    public function obtener_rutas(){
        $uploads = FirmasElectronicas::all();
        return response()->json($uploads);
    }

    public function uploadImages(Request $request){
        $image = $request->file('image_name');
        $name = $request->file('image_name')->getClientOriginalName();
        $image_name = $request->file('image_name')->getRealPath();;
        Cloudder::upload($image_name, null);
        list($width, $height) = getimagesize($image_name);
        $image_url= Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);
        //save to uploads directory
        $image->move(public_path("uploads"), $name);
        //Save images
        $id = $this->saveImages($request, $image_url);

       return $id;

   }

   public function saveImages(Request $request, $image_url)
   {
       $image = new FirmasElectronicas();
       $image->image_name = $request->file('image_name')->getClientOriginalName();
       $image->image_url = $image_url;

       $image->save();
       //return $image->image_url;
       return $image->id;
   }

}
