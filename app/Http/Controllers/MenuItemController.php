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
        $items =  MenuItem::select("id","name","photo","price","stock","menu_id","type","stock_tracking","order_start_time","order_end_time",'is_stock')
                        ->with('menu:id,name')
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
        $menuItem = MenuItem::select("id","name","photo","price","stock","menu_id","type","stock_tracking","order_start_time","order_end_time","is_stock")
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
                'name'                      =>  $data['name'],
                'photo'                     =>  $filename,
                'price'                     =>  $data['price'],
                'is_stock'                  =>  $data['is_stock'],
                'stock'                     =>  $data['is_stock'] == 1 ? $data['stock'] : null,
                'type'                      =>  $data['is_stock'] == 1 ? $data['type'] : null,
                'menu_id'                   =>  $data['menu_id'],
                'is_stock_tracking'         =>  $data['is_stock_tracking'],
                'stock_tracking_quantity'   =>  $data['is_stock_tracking'] == 1 ? $data['stock_tracking_quantity'] : null,
                'show_qr'                   =>  $data['show_qr'],
                'order_start_time'          =>  $data['order_start_time'],
                'order_end_time'            =>  $data['order_end_time']
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
                        'note' => ($past_stock_count - $data['stock'])." Adet stok miktar çıkışı"
                    ]);
                }
            }else{
                MenuItemStockHistory::where('menu_item_id', $item->id)->delete();
            }

            return $this->successResponse($item, 200);
        } catch (\Exception $e) {
            return $e;
            return $this->errorResponse('Menu item not successfully created!', 400);
        }
    }

    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->stockHistories()->delete();
        $item->delete();

        return $this->successResponse('Menu item deleted',200);
    }

    public function updateStock(Request $request, $id)
    {   
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $item = MenuItem::findOrFail($id);

        $past_stock_count = $item->stock;

        $item->update([
            'stock' => $request->stock
        ]);

        if ($request->stock > $past_stock_count) {
            MenuItemStockHistory::create([
                'menu_item_id' => $item->id,
                'type' => 1,
                'quantity' => $request->stock - $past_stock_count,
                'note' => ($request->stock - $past_stock_count)." Adet stok miktar girişi"
            ]);
        } else if ($request->stock < $past_stock_count) {
            MenuItemStockHistory::create([
                'menu_item_id' => $item->id,
                'type' => 2,
                'quantity' => $past_stock_count - $request->stock,
                'note' => ($past_stock_count - $request->stock)." Adet stok miktar çıkışı"
            ]);
        }

        return $this->successResponse('Menu item stock updated',200);
    }

    public function updatePrice(Request $request, $id)
    {   
        $request->validate([
            'price' => 'required|integer|min:0',
        ]);

        $item = MenuItem::findOrFail($id);
        $item->update([
            'price' => $request->price
        ]);
   
        return $this->successResponse('Menu item price updated',200);
    }

    public function updateType(Request $request, $id)
    {   
        $request->validate([
            'show_qr' => 'required|integer|in:1,2',
        ]);

        $item = MenuItem::findOrFail($id);
        $item->update([
            'show_qr' => $request->show_qr
        ]);
   
        return $this->successResponse('Menu item show qr updated',200);
    }

    public function getStockHistories(string $id)
    {
        $item = MenuItem::findOrFail($id);
        return $this->successResponse($item->stockHistories, 200);
    }

    public function stockProccess(Request $request,string $id)
    {
        try {
            $request->validate([
                'process'   => 'required|integer|in:1,2',
                'quantity'  => 'required|integer|min:0',
            ]);
    
            $item = MenuItem::findOrFail($id);
            $past_stock_count = $item->stock;
            if ($item->is_stock == 2) {
                return $this->errorResponse('Item is not stockable', 404);
            }

            if ($request->process == 1) {
                $item->stock += $request->quantity;

                MenuItemStockHistory::create([
                    'menu_item_id' => $item->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                    'note' => ($request->quantity)." Adet stok miktar girişi"
                ]);

            } else if ($request->process == 2) {
                if ($item->stock < $request->quantity) {
                    return $this->errorResponse('Insufficient stock', 400);
                }
                $item->stock -= $request->quantity;
                MenuItemStockHistory::create([
                    'menu_item_id' => $item->id,
                    'type' => 2,
                    'quantity' =>  $request->quantity,
                    'note' => ($request->quantity)." Adet stok miktar çıkışı"
                ]);
            }

            $item->save();

            return $this->successResponse($item, 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
