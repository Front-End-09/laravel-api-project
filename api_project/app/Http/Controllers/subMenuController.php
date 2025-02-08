<?php

namespace App\Http\Controllers;

use App\Models\subMenuModel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\paginationModel;
use App\Http\Controllers\responseController;
use Illuminate\Support\Facades\Validator;
class subMenuController extends Controller
{
    public function indexSubMenu(Request $request){
        $result = subMenuModel::indexDataSubMenu($request);
        return $result;
    }
       // Store Data Menu
   public function storeSubMenu(Request $request){
    $validator = Validator::make(
    $request->all(),
        [
            'sub_menuName' => 'required',
            'url'          => 'required',
            'parentId'     => 'required'
        ]
    );
    if($validator->fails()){
        return responseController::client($validator->getMessageBag()->toArray());
    }else{
        $result = subMenuModel::storeDataSubMenu($request);
        return $result;
    }
}
}
