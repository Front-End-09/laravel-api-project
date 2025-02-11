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
       $show   = $request->show;
       $page   = $request->page;
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
            $url          = $request->url ?? '';
            $parentId     = $request->parentId ?? '';

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

    // Update Data Sub Menu
    public static function updateDataSubMenu($request){
        DB::beginTransaction();
        try{
            $id           = $request->sub_menuId ?? '';
            $sub_menuName = $request->sub_menuName ?? '';
            $url          = $request->url ?? '';
            $parentId     = $request->parentId ?? '';

            $result = DB::select("SELECT menu.update_sub_menu(?,?,?,?)",
              [
                 $id,
                 $sub_menuName,
                 $url,
                 $parentId
              ]);
              DB::commit();
              if(!empty($result)){
                return responseController::success([
                    'message' => 'Sub menu update successfully!'
                ]);
              }
              return responseController::error('Update failed or sub menu not found!');
        }catch(Exception $ex){
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }


    // Detail Data Sub Menu
    public static function  detailDataSubMenu($id){
        try{
            $result = DB::select("SELECT s.sub_menuId,
                                         s.sub_menuName,
                                         s.url,s.parentId,
                                         s.is_show,
                                         s.is_close FROM submenu s WHERE is_show = 1 AND s.sub_menuId = $id");
            DB::commit();
            return responseController::success($result);
        }catch(Exception $ex){
          DB::rollBack();
          return responseController::error($ex->getMessage());
        }
    }

    // Delete Data Sub Menu
    public static function deleteDataSubMenu($id){
        DB::beginTransaction(); // Start Transaction
        try{
            $result = DB::select(" SELECT delete_sub_menu(?) ",[$id]);
            DB::commit();
            // Check if a row was deleted
            if (!empty($result)) {
                return responseController::success([
                    'message' => "Sub menu is deleted successfully!"
                ]);
            }
            return responseController::error('Delete failed or sub menu not found');
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }
}
