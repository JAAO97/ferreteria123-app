<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => ProductoResource::collection($productos),
            'pagination' => [
                'total' => $productos->total(),
                'per_page' => $productos->perPage(),
                'current_page' => $productos->currentPage(),
                'last_page' => $productos->lastPage(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validados =$request->validate([
            'codigo_barrra' => 'required|string|unique:productos,codigo_barra|max:30',
            'nombre'      => 'required|string|max:150',
            'precio_venta'      => 'required|numeric|min:0.01',
            'stock'       => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $producto = Producto::create($validados);

        return redirect()->route('productos.index')
                         ->with('success', '¡Producto registrado exitosamente en el inventario!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return response()->json([
            'status' => 'created',
            'message' => 'Producto creado exitosamente',
            'data' => new ProductoResource($producto)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
         
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'      => 'required|max:255',
            'descripcion' => 'nullable',
            'precio'      => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
                         ->with('success', '¡Producto actualizado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
                         ->with('success', '¡Producto eliminado del inventario!');
    }
}
