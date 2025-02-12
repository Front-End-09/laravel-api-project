<?php

namespace App\Http\Controllers;

use App\Models\dropdownMenuModel;
use Illuminate\Http\Request;

class dropdownMenuController extends Controller
{
    public function dropdownMenu(Request $request){
        $result = dropdownMenuModel::dropdownMenuData($request);
        return $result;
    }
}
