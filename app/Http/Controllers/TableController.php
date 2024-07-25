<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Http\Traits\ApiResponserTrait;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{
    use ApiResponserTrait;
    public function index()
    {
        return Table::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $table = Table::create($request->all());

        return response()->json($table, 201);
    }

    public function show(Table $table)
    {
        return $table;
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
        ]);

        $table->update($request->all());

        return response()->json($table, 200);
    }

    public function destroy(Table $table)
    {
        $table->delete();

        return response()->json(null, 204);
    }

    public function getCategories()
    {
        $categories = Category::select('id', 'name')
                    ->where('restaurant_id', auth()->user()->restaurant_id)
                    ->get();

        return $this->successResponse($categories);
    }

    public function getTables(Request $request)
    {
        $tables = Table::select('id','name', 'category_id', 'ordinal_number')
                    ->when(isset($request->group), function ($query) use ($request) {
                        $query->whereHas('category', function($query) use ($request) {
                            $query->where('name', $request->group);
                        });
                    })
                    ->where('restaurant_id', auth()->user()->restaurant_id)
                    ->orderBy('ordinal_number')
                    ->get();

        return $this->successResponse($tables);
    }

    public function createTable(CreateTableRequest $request)
    {
        $data = $request->validated();
        $restaurant_id = auth()->user()->restaurant_id;

        if (isset($data['category_name'])) {
            $category = Category::updateOrCreate([
                'name' => $data['category_name'],
            ],[
                'restaurant_id' => $restaurant_id
            ]);
        }

        $table = Table::create([
            'name' => $data['name'],
            'category_id' => $category->id ?? null,
            'restaurant_id' => $restaurant_id,
            'ordinal_number' => $data['ordinal_number']
        ]);

        return $this->successResponse($table, 201);
    }

    public function updateTable(UpdateTableRequest $request, $id)
    {
        try {
            $table = Table::findOrFail($id);
            $data = $request->validated();
            $restaurant_id = auth()->user()->restaurant_id;

            if (isset($data['category_name'])) {
                $category = Category::updateOrCreate([
                    'name' => $data['category_name'],
                ],[
                    'restaurant_id' => $restaurant_id
                ]);
            }
            $table->update([
                'name' => $data['name'],
                'category_id' => $category->id ?? null,
                'restaurant_id' => $restaurant_id,
                'ordinal_number' => $data['ordinal_number']
            ]);

            return $this->successResponse($table, 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Table not found', 404);
        }
    }

    public function deleteTable($id)
    {
        try {
            $table = Table::findOrFail($id);
            $category = $table->category;
            if ($category && $category->tables()->count() === 0) {
                $category->delete();
            }
            $table->delete();

            return $this->successResponse(null, 200);
        } catch (\Exception $e) {
            return $e;
            return $this->errorResponse('Table not found', 404);
        }
    }
}
