<?php

namespace App\Models;

use App\Http\Controllers\responseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\paginationModel;
use Illuminate\Support\Facades\Validator;
class slideModel extends Model
{
    use HasFactory;

    // Index Data Slide
    public static function indexDataSlide($request){
        $condition = null;
        $show = $request->show ?? '';
        $page = $request->page ?? '';
        $search = "";
        if(!empty($request->search)){
            $search = "AND (s.date::VARCHAR ILIKE ('%$request->search%'))
                OR s.title ILIKE ('%$request->search%')
            ";
        }else{
            $search = "";
        }
        $group = null;
        $query = "";
        $query = "SELECT s.id,
                         s.menu_id,
                         s.upload_files_id,
                         s.title,
                         s.title_kh,
                         s.description_en,
                         s.description_kh,
                         s.sub_title_en,
                         s.sub_title_kh,
                         s.btn_name_en,
                         s.btn_name_kh
                           FROM slide s WHERE is_deleted = 0
                         ";
        $sort = "";
        $sort = "ORDER BY s.id DESC";
        DB::commit();
        return paginationModel::pagination($query, $show, $condition, $group, $sort, $page, $search);
    }

    // Store Data Slide
    public static function storeDataSlide($request){
        DB::beginTransaction();
        try {
            $menuId        = $request->web_menu_id ?? '';
            $uploadFilesId = $request->web_uploaded_files_id ?? '';
            $title         = $request->title ?? '';
            $url           = $request->url ?? ''; 
            $sequence      = $request->sequence ?? '';
            $createBy      = $request->create_by ?? 1;
            $titleKh       = $request->title_kh ?? '';
            $descriptionEn = $request->description ?? '';
            $descriptionKh = $request->description_kh ?? '';
            $subTitleEn    = $request->sub_title_en ?? '';
            $subTitleKh    = $request->sub_title_kh ?? '';
            $btnNameEn     = $request->btn_name_en ?? '';
            $btnNameKh     = $request->btn_name_kh ?? '';
            $insertSlide = DB::select("SELECT insert_slide(?,?,?,?,?,?,?,?,?,?,?,?,?)", [
                $menuId,
                $uploadFilesId,
                $title,
                $url,
                $sequence,
                $createBy,
                $titleKh,
                $descriptionEn,
                $descriptionKh,
                $subTitleEn,
                $subTitleKh,
                $btnNameEn,
                $btnNameKh,
            ]);

            DB::commit();

            if (!empty($insertSlide)) {
                return responseController::success([
                    'message' => 'Insert Successfully!'
                ]);
            }
        } catch(Exception $ex) {
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }

    // Detail Data Slide
    public static function detailDataSlide($id){
        try{
            $result = DB::select("SELECT s.id,
                                         s.upload_files_id,
                                         s.title,
                                         s.title_kh,
                                         s.sub_title_en,
                                         s.sub_title_kh,
                                         s.url,
                                         s.sequence,
                                         s.create_by,
                                         s.description_en,
                                         s.description_kh
                                    FROM  slide s WHERE is_deleted = 0 AND s.id = $id
                                ");
                DB::commit();
                return responseController::success($result);
        }catch(Exception $ex){
            DB::commit();
            return responseController::error($ex->getMessage());
        }
    }
    // Update Data Slide
    public static function updateDataSlide($request)
    {
        DB::beginTransaction();
        try {
               // Extract values from request
                $id            = $request->id ?? '';
                $menuId        = $request->menu_id ?? '';
                $uploadFilesId = $request->upload_files_id ?? '';
                $title         = $request->title ?? '';
                $url           = $request->url ?? '';
                $sequence      = $request->sequence ?? '';
                $createBy      = $request->create_by ?? 1;
                $titleKh       = $request->title_kh ?? '';
                $descriptionEn = $request->description ?? '';
                $descriptionKh = $request->description_kh ?? '';
                $subTitleEn    = $request->sub_title_en ?? '';
                $subTitleKh    = $request->sub_title_kh ?? '';
                $btnNameEn     = $request->btn_name_en ?? '';
                $btnNameKh     = $request->btn_name_kh ?? '';
                // Execute the stored procedure
             $result = DB::select("SELECT menu.update_slide(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $id,
                $menuId,
                $uploadFilesId,
                $title,
                $url,
                $sequence,
                $createBy,
                $titleKh,
                $descriptionEn,
                $descriptionKh,
                $subTitleEn,
                $subTitleKh,
                $btnNameEn,
                $btnNameKh
            ]);
            DB::commit();
            if(!empty($result)){
              return responseController::success([
                  'message' => 'Slide update successfully!'
              ]);
            }
            return responseController::error('Update failed or sub menu not found!');

        } catch (Exception $ex) {
            DB::rollBack();
            return responseController::error($ex->getMessage());
        }
    }
}
