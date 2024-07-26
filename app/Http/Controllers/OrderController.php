<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Traits\ApiResponserTrait;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Carbon;

use function PHPSTORM_META\map;

class OrderController extends Controller
{
    use ApiResponserTrait;
    public function index()
    {
        return Order::with('menuItems')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'status' => 'required|in:pending,completed,cancelled',
            'menu_items' => 'required|array',
            'menu_items.*.id' => 'required|exists:menu_items,id',
            'menu_items.*.quantity' => 'required|integer|min:1',
            'menu_items.*.price' => 'required|numeric',
        ]);

        $order = Order::create($request->only('table_id', 'restaurant_id', 'status'));

        foreach ($request->menu_items as $menuItem) {
            $order->menuItems()->attach($menuItem['id'], [
                'quantity' => $menuItem['quantity'],
                'price' => $menuItem['price'],
            ]);
        }

        return response()->json($order->load('menuItems'), 201);
    }

    public function show(Order $order)
    {
        return $order->load('menuItems');
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'table_id' => 'sometimes|exists:tables,id',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
            'status' => 'sometimes|in:pending,completed,cancelled',
            'menu_items' => 'sometimes|array',
            'menu_items.*.id' => 'sometimes|exists:menu_items,id',
            'menu_items.*.quantity' => 'sometimes|integer|min:1',
            'menu_items.*.price' => 'sometimes|numeric',
        ]);

        $order->update($request->only('table_id', 'restaurant_id', 'status'));

        if ($request->has('menu_items')) {
            $order->menuItems()->detach();
            foreach ($request->menu_items as $menuItem) {
                $order->menuItems()->attach($menuItem['id'], [
                    'quantity' => $menuItem['quantity'],
                    'price' => $menuItem['price'],
                ]);
            }
        }

        return response()->json($order->load('menuItems'), 200);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }

    public function getOnlineOrders()
    {
        $orders =  Order::select('id','order_type_id','customer_id','total_price','status','created_at')
                        ->with('customer:id,name,phone,address,note')
                        ->where([
                            'restaurant_id' => auth()->user()->restaurant_id,
                            'status'        => 'pending',
                        ])
                        ->get()
                        ->map(function ($order) {
                            $order->time = Carbon::parse($order->created_at)->format('H:i');
                            $order->time_since_order = $order->time_since_order;
                            return $order;
                        });

        return $this->successResponse($orders, 200);
    }

    public function createOrder(CreateOrderRequest $request)
    {

    }
}
