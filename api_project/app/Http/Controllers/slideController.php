<?php

namespace App\Http\Controllers;

use App\Models\slideModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class slideController extends Controller
{
    // Index Slide
    public function indexSlide(Request $request){
        $result = slideModel::indexDataSlide($request);
        return $result;
    }

    // Store Data Slide
    public function storeSlide(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'web_menu_id'           => 'required',
                'web_uploaded_files_id' => 'required',
                'title'                 => 'required',
                'title_kh'              => 'required',
                'description'           => 'required',
                'description_kh'        => 'required',
                'sub_title_en'          => 'required',
                'sub_title_kh'          => 'required',
                'btn_name_en'           => 'required',
                'btn_name_kh'           => 'required',
                'url'                   => 'required',
                'sequence'              => 'required'
            ]
        );
        if($validator->fails()){
            return responseController::client($validator->getMessageBag()->toArray());
        }else{
            $result = slideModel::storeDataSlide($request);
            dd( $result );
            return $result;
        }
    }

}
