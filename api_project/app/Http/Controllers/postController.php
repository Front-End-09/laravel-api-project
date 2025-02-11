<?php

namespace App\Http\Controllers;

use App\Models\postModel;
use Illuminate\Http\Request;

class postController extends Controller
{
     public function indexPost(Request $request){
        $result = postModel::indexPostData($request);
        return $result;
     }
}
