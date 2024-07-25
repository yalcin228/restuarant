<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Traits\ApiResponserTrait;
use App\Http\Requests\CreateAdminRequest;

class RestaurantController extends Controller
{
    use ApiResponserTrait;
    //
    public function index()
    {
        $restuarants = Restaurant::paginate(10);

        return $this->successResponse($restuarants, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:restaurants',
        ]);

        $restaurant = Restaurant::create($request->all());

        return $this->successResponse($restaurant, 201);
    }

    public function show(Restaurant $restaurant)
    {
        return $this->successResponse($restaurant, 200);
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $restaurant->update($request->all());

        return $this->successResponse($restaurant, 200);
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return $this->successResponse(null, 204);
    }

    public function createAdmin(CreateAdminRequest $request)
    {
        $data = $request->validated();
        $data['end_date'] = Carbon::now()->addDays($data['duration']);
        $admin = User::create($data);

        return $this->successResponse($admin, 201);
    }
}
