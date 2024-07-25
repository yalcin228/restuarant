<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuItemRequest;
use App\Http\Traits\ApiResponserTrait;
use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    use ApiResponserTrait;
    public function index()
    {
        $items =  MenuItem::select()
                        ->orderBy('ordinal_number')
                        ->get();

        return $this->successResponse($items,200);
    }

    public function store(CreateMenuItemRequest $request)
    {
        $data = $request->validated();

        $menuItem = MenuItem::create();

        return response()->json($menuItem, 201);
    }

    public function show(MenuItem $menuItem)
    {
        return $menuItem;
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
        ]);

        $menuItem->update($request->all());

        return response()->json($menuItem, 200);
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return response()->json(null, 204);
    }
}
