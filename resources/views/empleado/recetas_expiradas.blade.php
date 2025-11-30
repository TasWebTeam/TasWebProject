@extends('layouts.template')

@section('title', 'Recetas expiradas - Empleado')

@section('content')
<div class="container py-5">
    <h1 class="mb-4" style="color:#003865;">Recetas expiradas</h1>

    <p class="text-muted mb-4">
        Estas recetas excedieron el tiempo límite de recolección (por ejemplo, 72 horas) y fueron marcadas como expiradas.
    </p>

    {{-- Filtro por rango de fechas --}}
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Fecha recolección (hasta)</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de recetas expiradas --}}
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Listado de recetas expiradas</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Paciente</th>
                            <th>Fecha registro</th>
                            <th>Fecha recolección</th>
                            <th>Días de atraso</th>
                            <th>Motivo / Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R-0005</td>
                            <td>Carlos Ruiz</td>
                            <td>2025-10-20</td>
                            <td>2025-10-22</td>
                            <td>5 días</td>
                            <td>No acudió a recoger en el tiempo establecido.</td>
                        </tr>
                        <tr>
                            <td>R-0008</td>
                            <td>Ana Torres</td>
                            <td>2025-10-18</td>
                            <td>2025-10-21</td>
                            <td>7 días</td>
                            <td>Requiere seguimiento para devolución al inventario.</td>
                        </tr>
                        {{-- Luego aquí harás @foreach con datos reales --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
