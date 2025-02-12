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
                         s.btn_name_kh,
                         s.is_deleted
                         FROM slide s WHERE is_deleted = 0
                         ";
        $sort = "";
        $sort = "ORDER BY s.id DESC";
        DB::commit();
        return paginationModel::pagination($query, $show, $condition, $group, $sort, $page, $search);
    }




    public static function storeDataSlide($request){
        DB::beginTransaction();
        try {
            $menuId        = $request->web_menu_id ?? ''; // ✅ Fixed
            $uploadFilesId = $request->web_uploaded_files_id ?? ''; // ✅ Fixed
            $title         = $request->title ?? '';
            $url           = $request->url ?? ''; 
            $sequence      = $request->sequence ?? '';
            $createBy      = $request->create_by ?? 1;
            $status        = ($request->status ?? true) ? 1 : 0;  // ✅ Convert to 1 or 0
            $titleKh       = $request->title_kh ?? '';
            $descriptionEn = $request->description ?? ''; // ✅ Fixed
            $descriptionKh = $request->description_kh ?? '';
            $subTitleEn    = $request->sub_title_en ?? '';
            $subTitleKh    = $request->sub_title_kh ?? '';
            $btnNameEn     = $request->btn_name_en ?? '';
            $btnNameKh     = $request->btn_name_kh ?? '';

            // ✅ Add `status` to match database function
            $insertSlide = DB::select("SELECT insert_slide(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [
                $menuId, $uploadFilesId, $title, $url, $sequence, $createBy,
                $titleKh, $descriptionEn, $descriptionKh, 
                $subTitleEn, $subTitleKh, $btnNameEn, $btnNameKh,
                $status
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

}
