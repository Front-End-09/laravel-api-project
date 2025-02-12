<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class dropdownMenuModel extends Model
{
    use HasFactory;

    public static function dropdownMenuData($request){
        $show  = $request->show ?? 10;
        $page  = $request->page ?? 1;
        $search = "";

        if (!empty($request->search)) {
            $search = " AND (m.date::VARCHAR ILIKE ('%$request->search%')
                        OR m.menuName ILIKE ('%$request->search%')
                        OR m.url ILIKE ('%$request->search%')) ";
        }

        $query = "SELECT * FROM menu m WHERE is_show = 1 $search ORDER BY m.menuId DESC";
        $menus = DB::select($query);

        $total = count($menus);
        $result = [];

        foreach ($menus as $menu) {
            // Fetch submenus for each menu
            $subQuery = "SELECT * FROM submenu s WHERE is_show = 1 AND s.parentId = ? ORDER BY s.sub_menuId DESC";
            $subMenus = DB::select($subQuery, [$menu->menuId]);

            $result[] = [
                "menuId"      => $menu->menuId,
                "menuName"    => $menu->menuName,
                "url"         => $menu->url,
                "is_show"     => $menu->is_show,
                "is_close"    => $menu->is_close,
                "create_date" => $menu->create_date,
                "subMenu"     => array_map(function ($sub) {
                    return [
                        "sub_menuId"   => $sub->sub_menuId,
                        "sub_menuName" => $sub->sub_menuName,
                        "url"          => $sub->url,
                        "parentId"     => $sub->parentId,
                        "is_show"      => $sub->is_show,
                        "is_close"     => $sub->is_close,
                    ];
                }, $subMenus)
            ];
        }

        return response()->json([
            "status" => 200,
            "total"  => $total,
            "result" => $result
        ]);
    }

    }

