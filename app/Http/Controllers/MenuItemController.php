<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Http\Traits\ApiResponserTrait;
use App\Http\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuItemStockHistory;

class MenuItemController extends Controller
{
    use ApiResponserTrait,ImageUpload;
    public function index()
    {
        $items =  MenuItem::select("id","name","photo","price","stock","menu_id","type","stock_tracking","ordinal_number","order_start_time","order_end_time",'is_stock')
                        ->with('menu:id,name')
                        ->orderBy('ordinal_number')
                        ->get();

        return $this->successResponse(MenuItemResource::collection($items),200);
    }

    public function store(CreateMenuItemRequest $request)
    {
        try {
            $data = $request->validated();
            $filename = "";
    
            if ($request->hasFile('photo')) {
                $filename = $this->ImageUpload($request->file('photo'), 'products', "", "");
                $data['photo'] = $filename;
            }
    
            $menuItem = MenuItem::create($data);
            if ($data['is_stock'] == 1) {
                MenuItemStockHistory::create([
                    'menu_item_id' => $menuItem->id,
                    'type' => 1,
                    'quantity' => $data['stock'],
                    'note' => $data['stock']." Adet stok miktar girişi"
                ]);
            }
    
            return $this->successResponse($menuItem, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Menu item not successfully created!', 400);
        }
    }

    public function show(string $id)
    {
        $menuItem = MenuItem::select("id","name","photo","price","stock","menu_id","type","stock_tracking","ordinal_number","order_start_time","order_end_time","is_stock")
                        ->where('id', $id)
                        ->first();

        return $this->successResponse(new MenuItemResource($menuItem),200);
    }

    public function updateItem(CreateMenuItemRequest $request, $id)
    {
        try {
            $item = MenuItem::findOrFail($id);
            $data = $request->validated();
            $past_stock_count = $item->stock;
            $filename = $request->has('photo') ? $this->ImageUpload($request->file('photo'), 'products/', "", "") : $item->photo;

            $item->update([
                'name'              =>  $data['name'],
                'photo'             => $filename,
                'price'             =>  $data['price'],
                'is_stock'          =>  $data['is_stock'],
                'stock'             =>  $data['is_stock'] == 1 ? $data['stock'] : null,
                'menu_id'           =>  $data['menu_id'],
                'type'              =>  $data['type'],
                'stock_tracking'    =>  $data['stock_tracking'],
                'ordinal_number'    =>  $data['ordinal_number'],
                'order_start_time'  =>  $data['order_start_time'],
                'order_end_time'    =>  $data['order_end_time']
            ]);
            if ($data['is_stock'] == 1) {
                if ($data['stock'] > $past_stock_count) {
                    MenuItemStockHistory::create([
                        'menu_item_id' => $item->id,
                        'type' => 1,
                        'quantity' => $data['stock'] - $past_stock_count,
                        'note' => ($data['stock'] - $past_stock_count)." Adet stok miktar girişi"
                    ]);
                } else if ($data['stock'] < $past_stock_count) {
                    MenuItemStockHistory::create([
                        'menu_item_id' => $item->id,
                        'type' => 2,
                        'quantity' => $past_stock_count - $data['stock'],
                        'note' => ($past_stock_count - $data['stock'])." Adet stok miktar cikisi"
                    ]);
                }
            }else{
                MenuItemStockHistory::where('menu_item_id', $item->id)->delete();
            }

            return $this->successResponse($item, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Menu item not successfully created!', 400);
        }
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return response()->json(null, 204);
    }
}
