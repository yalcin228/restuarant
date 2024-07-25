<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;

class StaffController extends Controller
{
    //

    public function index()
    {
        return Staff::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff',
            'password' => 'required|string|min:8',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $staff = Staff::create($request->all());

        return response()->json($staff, 201);
    }

    public function show(Staff $staff)
    {
        return $staff;
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:staff,email,'.$staff->id,
            'password' => 'sometimes|string|min:8',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
        ]);

        if ($request->has('password')) {
            $staff->password = $request->password;
        }

        $staff->update($request->except('password'));

        return response()->json($staff, 200);
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return response()->json(null, 204);
    }
}
