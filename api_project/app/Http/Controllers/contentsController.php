<?php

namespace App\Http\Controllers;

use App\Models\contentsModel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\paginationModel;
use App\Http\Controllers\responseController;
use Illuminate\Support\Facades\Validator;

class contentsController extends Controller
{

    // Index Menu
   function indexMenu(Request $request){
       $result = contentsModel::indexDataMenu($request);
       return $result;
   }

   // Store Data Menu
   public function storeMenu(Request $request){
        $validator = Validator::make(
        $request->all(),
            [
                'menuName' => 'required',
                'url'      => 'required'
            ]
        );
        if($validator->fails()){
            return responseController::client($validator->getMessageBag()->toArray());
        }else{
            $result = contentsModel::storeDataMenu($request);
            return $result;
        }
   }

   public function updateMenu(Request $request){
    $validate = Validator::make(
        $request->all(),
        [
            'menuId'   => 'required',
            'menuName' => 'required',
            'url'      => 'required'
        ]
      );
      if($validate->fails())
      {
        return responseController::client($validate->getMessageBag()->toArray());
      }else{
        $result = contentsModel::updateDataMenu($request);
        return $result;
      }
    }

   // Delete Menu
   public function deleteMenu($id){
        $result = contentsModel::deleteMenu($id);
        return  $result;
   }

   public function detailMenu($id){
      $result = contentsModel::detailDataMenu($id);
       return $result;
    }

}
