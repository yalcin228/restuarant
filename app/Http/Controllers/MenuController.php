<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Traits\ApiResponserTrait;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use ApiResponserTrait;
    public function index()
    {
        $menus = Menu::select('id','name','type','print','ordinal_number')
                    ->orderBy('ordinal_number')
                    ->where('restaurant_id', auth()->user()->restaurant_id)
                    ->get();

        return $this->successResponse($menus,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMenuRequest $request)
    {
        $data = $request->validated();
        $data['restaurant_id'] = auth()->user()->restaurant_id;
        $menu = Menu::create($data);

        return $this->successResponse($menu,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu = Menu::select('id','name','type','print','ordinal_number')
                    ->where('restaurant_id', auth()->user()->restaurant_id)
                    ->where('id', $id)
                    ->first();

        return $this->successResponse($menu,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, string $id)
    {
        $data = $request->validated();
        $menu = Menu::where('id', $id)
                    ->where('restaurant_id', auth()->user()->restaurant_id)
                    ->first();
        $menu->update($data);

        return $this->successResponse($menu,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::where('id', $id)
                    ->where('restaurant_id', auth()->user()->restaurant_id)
                    ->first();
        $menu->delete();
        
        return $this->successResponse('Menu deleted',200);
    }
}
