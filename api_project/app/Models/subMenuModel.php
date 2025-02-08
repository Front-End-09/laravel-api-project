<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\responseController;
use Illuminate\Support\Facades\DB;
use App\Models\paginationModel;
use Dotenv\Validator;
use Exception;
use GuzzleHttp\Psr7\Request;

class subMenuModel extends Model
{
    use HasFactory;

    // Index Sub Menu
    public static function indexDataSubMenu($request){
       $condition = null;
       $show = $request->show;
       $page = $request->page;
       $search = "";
       if(!empty($request->search)){
            $search = " AND (s.date::VARCHAR ILIKE ('%$request->search%')
            OR   s.sub_menuName         ILIKE ('%$request->search%')
            OR   s.url   ILIKE ('%$request->search%')) ";
       }else{
          $search = "";
       }
       $group = null;
        // query
        $query = "";
        $query = " SELECT * FROM submenu s WHERE is_show = 1 ";
        $sort  = "";
        $sort  = " ORDER BY s.sub_menuId DESC ";
        DB::commit();
        return paginationModel::pagination($query, $show, $condition, $group, $sort, $page, $search);
    }

    // Store Data Sub Menu
    public static function storeDataSubMenu($request){
        DB::beginTransaction();
        try {
            $sub_menuName = $request->sub_menuName ?? '';
            $url      = $request->url ?? '';
            $parentId = $request->parentId ?? '';

            // Call the function without schema if it's in default
            $insertSubMenu = DB::select("SELECT insert_sub_menu(?,?,?)", [$sub_menuName, $url, $parentId] );

            DB::commit();
            if (!empty($insertSubMenu)) {
                return responseController::success([
                    'message' => 'Insert successfully!',
                ]);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }
}
