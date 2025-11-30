@extends('layouts.template')

@section('title', 'Recetas - Empleado')

@section('content')
<div class="container py-5">
    <h1 class="mb-4" style="color:#003865;">Recetas pendientes por surtir</h1>

    <p class="text-muted mb-4">
        Aquí puedes ver las recetas que han sido enviadas por los pacientes y están pendientes de surtido.
    </p>

    {{-- Filtros simples (solo frontend por ahora) --}}
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select">
                        <option value="">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="EN_PROCESO">En proceso</option>
                        <option value="LISTA">Lista para recoger</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha registro (desde)</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha registro (hasta)</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de recetas (dummy por ahora) --}}
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Listado de recetas</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Paciente</th>
                            <th>Fecha registro</th>
                            <th>Fecha recolección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ejemplos estáticos por ahora --}}
                        <tr>
                            <td>R-0001</td>
                            <td>Juan Pérez</td>
                            <td>2025-11-01</td>
                            <td>2025-11-03</td>
                            <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Ver detalles</button>
                                <button class="btn btn-sm btn-success ms-1">Marcar como surtida</button>
                            </td>
                        </tr>
                        <tr>
                            <td>R-0002</td>
                            <td>María López</td>
                            <td>2025-11-02</td>
                            <td>2025-11-04</td>
                            <td><span class="badge bg-info text-dark">En proceso</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Ver detalles</button>
                                <button class="btn btn-sm btn-success ms-1">Marcar como lista</button>
                            </td>
                        </tr>
                        {{-- Aquí luego tu backend llenará con @foreach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
