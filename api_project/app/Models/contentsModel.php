<?php

namespace App\Models;

use App\Http\Controllers\responseController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\paginationModel;
use Dotenv\Validator;
use Exception;
use GuzzleHttp\Psr7\Request;

class contentsModel extends Model
{
    use HasFactory;
    // List Menu
    public static function indexDataMenu($request)
    {
        $condition = null;
        $show      = $request->show;
        $page      = $request->page;

        // search
        $search = "";
        if (!empty($request->search))
        {
            $search = " AND (m.date::VARCHAR ILIKE ('%$request->search%')
                        OR   m.menuName         ILIKE ('%$request->search%')
                        OR   m.url   ILIKE ('%$request->search%')) ";
        }
        else
        {
            $search = "";
        }
        $group = null;

        // query
        $query = "";
        $query = "  SELECT * FROM  menu m WHERE is_show = 1 ";
        $sort  = "";
        $sort  = " ORDER BY m.menuId DESC ";

        DB::commit();
        return paginationModel::pagination($query, $show, $condition, $group, $sort, $page, $search);
    }

    // Store Data Menu
    public static function storeDataMenu($request){
        DB::beginTransaction();
        try {
            $menuName = $request->menuName ?? '';
            $url      = $request->url ?? '';

            // Call the function without schema if it's in default
            $insertMenu = DB::select("SELECT insert_menu(?, ?)", [$menuName, $url] );

            DB::commit();
            if (!empty($insertMenu)) {
                return responseController::success([
                    'message' => 'insert_success',
                ]);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }

    // Delete Menu
    public static function deleteMenu($id){
        DB::beginTransaction(); // Start Transaction
        try{
            $result = DB::select(" SELECT delete_menu(?)",[$id]);
            DB::commit();
            // Check if a row was deleted
            if (!empty($result)) {
                return responseController::success([
                    'message' => "Menu is deleted successfully!"
                ]);
            }
            return responseController::error('Delete failed or menu not found');
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }

    // Update Data Menu
    public static function updateDataMenu($request){
        DB::beginTransaction();
        try{
            $id = $request->menuId ?? '';
            $menuName = $request->menuName ?? '';
            $url      = $request->url ?? '';

            $result = DB::select("SELECT menu.update_menu(?,?,?)", [
                $id,
                $menuName,
                $url
            ]);
              DB::commit();
              if (!empty($result)) {
                    return responseController::success([
                        'message' => "Menu is update successfully!"
                    ]);
                }
            return responseController::error('Update failed or menu not found');
        }catch(Exception $ex)
        {
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }

   // Detail Menu
   public static function  detailDataMenu($id){
      try{
        $result = DB::select("SELECT m.menuId,m.menuName, m.url FROM menu m WHERE m.is_show = 1 AND m.menuId = $id");
        DB::commit();
        return responseController::success($result);
      }catch(Exception $e){
        DB::rollBack();
        return responseController::error($e->getMessage());
      }
   }


}
