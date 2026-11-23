<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $productos = Producto::orderByDesc('id')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => ProductoResource::collection($productos)->resolve(),
            'pagination' => [
                'total' => $productos->total(),
                'per_page' => $productos->perPage(),
                'current_page' => $productos->currentPage(),
                'last_page' => $productos->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
        $producto = Producto::create($datos);
        return response()->json(['status' => 'success', 'message' => 'Producto creado.', 'data' => new ProductoResource($producto)], 201);
    }

    public function show(Producto $producto): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => new ProductoResource($producto)]);
    }

    public function update(Request $request, Producto $producto): JsonResponse
    {
        $datos = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'precio' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);
        if ($request->isMethod('put')) {
            $request->validate([
                'nombre' => ['required'], 'precio' => ['required'], 'stock' => ['required'],
            ]);
        }
        $producto->update($datos);
        return response()->json(['status' => 'success', 'message' => 'Producto actualizado.', 'data' => new ProductoResource($producto->refresh())]);
    }

    public function destroy(Producto $producto): JsonResponse
    {
        $producto->delete();
        return response()->json(['status' => 'success', 'message' => 'Producto eliminado.']);
    }
}
