<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Traits\ApiResponserTrait;
use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
    use ApiResponserTrait;

    public function index()
    {
        $staffs = User::where('role', 'staff')->get();

        return $this->successResponse($staffs, 200);
    }

    public function store(CreateStaffRequest $request)
    {
        try {
            $data = $request->validated();

            $staff = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'password'      => bcrypt($data['password']),
                'role'          => $data['role'],
                'restaurant_id' => auth()->user()->restaurant_id,
                'end_date'      => auth()->user()->end_date,
                'permissions'   => $data['permissions'],
            ]);

            return $this->successResponse($staff, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Staff not found!', 404);
        }
    }

    public function show(string $id)
    {
        try {
            $staff = User::where('id', $id)->where('role', 'staff')->firstOrFail();
            return $this->successResponse($staff, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Staff not found!', 404);
        }
    }

    public function update(UpdateStaffRequest $request, string $id)
    {
        try {
            $data = $request->validated();
    
            $staff = User::where('id', $id)
                ->where('role', 'staff')
                ->where('restaurant_id', auth()->user()->restaurant_id)
                ->firstOrFail();

            if ($request->has('password')) {
                $staff->update([
                    'name'          => $data['name'],
                    'email'         => $data['email'],
                    'password'      => bcrypt($request->password),
                    'permissions'   => $data['permissions'] ?? $staff->permissions,
                    'role'          => $data['role']
                ]);
            } else {
                $staff->update([
                    'name'          => $data['name'],
                    'email'         => $data['email'],
                    'permissions'   => $data['permissions'] ?? $staff->permissions,
                    'role'          => $data['role']
                ]);
            }
    
            return $this->successResponse($staff, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Staff not successfully updated!', 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $staff = User::findOrFail($id);
            $staff->delete();
    
            return $this->successResponse('Staff deleted', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Staff not successfully deleted!', 400);
        }
    }
}
