<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    private function rules(?Empleado $empleado = null, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';
        return [
            'identidad' => [$required, 'required', 'digits:13', Rule::unique('empleados', 'identidad')->ignore($empleado?->id)],
            'nombre' => [$required, 'required', 'string', 'max:255'],
            'apellido' => [$required, 'required', 'string', 'max:255'],
            'cargo' => [$required, 'required', 'string', 'max:255'],
            'salario' => [$required, 'required', 'numeric', 'min:0'],
            'codigo_empleado' => [$required, 'required', 'digits:13', Rule::unique('empleados', 'codigo_empleado')->ignore($empleado?->id)],
            'fecha_ingreso' => [$required, 'required', 'date'],
        ];
    }

    public function index(): JsonResponse
    {
        $items = Empleado::orderByDesc('id')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => EmpleadoResource::collection($items)->resolve(),
            'pagination' => [
                'total' => $items->total(), 'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(), 'last_page' => $items->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());
        $item = Empleado::create($data);
        return response()->json(['status' => 'success', 'message' => 'Empleado creado.', 'data' => new EmpleadoResource($item)], 201);
    }

    public function show(Empleado $empleado): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => new EmpleadoResource($empleado)]);
    }

    public function update(Request $request, Empleado $empleado): JsonResponse
    {
        $data = $request->validate($this->rules($empleado, $request->isMethod('patch')));
        $empleado->update($data);
        return response()->json(['status' => 'success', 'message' => 'Empleado actualizado.', 'data' => new EmpleadoResource($empleado->refresh())]);
    }

    public function destroy(Empleado $empleado): JsonResponse
    {
        $empleado->delete();
        return response()->json(['status' => 'success', 'message' => 'Empleado eliminado.']);
    }
}
