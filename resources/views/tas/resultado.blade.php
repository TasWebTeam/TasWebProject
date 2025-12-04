@extends('layouts.template')

@section('title', 'Resultado de Receta')

@push('styles')
<style>
    body {
        background-color: #ffffff;
        min-height: 100vh;
    }

    .container {
        margin-top: 100px;
        margin-bottom: 50px;
    }

    .success-checkmark {
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }

    .check-icon {
        width: 120px;
        height: 120px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #28a745;
        background-color: #ffffff;
    }

    .check-icon::before {
        top: 3px;
        left: -2px;
        width: 30px;
        transform-origin: 100% 50%;
        border-radius: 100px 0 0 100px;
    }

    .check-icon::after {
        top: 0;
        left: 30px;
        width: 60px;
        transform-origin: 0 50%;
        border-radius: 0 100px 100px 0;
        animation: rotate-circle 4.25s ease-in;
    }

    .icon-line {
        height: 5px;
        background-color: #28a745;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }

    .icon-line.line-tip {
        top: 56px;
        left: 22px;
        width: 25px;
        transform: rotate(45deg);
        animation: icon-line-tip 0.75s;
    }

    .icon-line.line-long {
        top: 48px;
        right: 16px;
        width: 47px;
        transform: rotate(-45deg);
        animation: icon-line-long 0.75s;
    }

    .icon-circle {
        top: -4px;
        left: -4px;
        z-index: 10;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        position: absolute;
        box-sizing: content-box;
        border: 4px solid rgba(40, 167, 69, .5);
    }

    .icon-fix {
        top: 12px;
        width: 10px;
        left: 34px;
        z-index: 1;
        height: 95px;
        position: absolute;
        transform: rotate(-45deg);
        background-color: #fff;
    }

    @keyframes rotate-circle {
        0% {
            transform: rotate(-45deg);
        }
        5% {
            transform: rotate(-45deg);
        }
        12% {
            transform: rotate(-405deg);
        }
        100% {
            transform: rotate(-405deg);
        }
    }

    @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        54% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }
        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }
        100% {
            width: 25px;
            left: 14px;
            top: 45px;
        }
    }

    @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        84% {
            width: 55px;
            right: 0px;
            top: 35px;
        }
        100% {
            width: 47px;
            right: 8px;
            top: 38px;
        }
    }

    .error-icon {
        animation: shake 0.5s;
    }

    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }
        10%, 30%, 50%, 70%, 90% {
            transform: translateX(-10px);
        }
        20%, 40%, 60%, 80% {
            transform: translateX(10px);
        }
    }

    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($exito)
                <div class="card border-0 shadow-lg">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="success-checkmark">
                                <div class="check-icon">
                                    <span class="icon-line line-tip"></span>
                                    <span class="icon-line line-long"></span>
                                    <div class="icon-circle"></div>
                                    <div class="icon-fix"></div>
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="text-success mb-3">¡Receta Procesada Exitosamente!</h2>
                        <p class="text-muted mb-4">Su receta ha sido enviada y está siendo preparada</p>
                        
                        <div class="alert alert-info mx-auto" style="max-width: 500px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Número de Pedido:</strong> #{{ $numeroPedido ?? 'N/A' }}
                        </div>

                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-prescription me-2"></i>
                                    Detalles de la Receta
                                </h5>
                            </div>
                            <div class="card-body text-start">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Cédula Profesional:</strong></p>
                                        <p class="text-muted">{{ $cedulaProfesional }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Farmacia:</strong></p>
                                        <p class="text-muted">{{ $farmacia }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4 border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <i class="fas fa-clock me-2"></i>
                                    ¿Qué sigue?
                                </h5>
                                <ul class="text-start list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Su receta será revisada por nuestro personal farmacéutico
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Recibirá una notificación cuando esté lista para recoger
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Fecha estimada de recolección: <strong>{{ $fechaRecoleccion ?? 'Por confirmar' }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex gap-3 justify-content-center mt-4 flex-wrap">
                            <a href="{{ route('tas_inicioView') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-home me-2"></i>
                                Ir al Inicio
                            </a>
                            <a href="{{ route('tas_subirRecetaView') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Nueva Receta
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-lg">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="error-icon">
                                <i class="fas fa-exclamation-circle text-danger" style="font-size: 100px;"></i>
                            </div>
                        </div>
                        
                        <h2 class="text-danger mb-3">No se pudo procesar la receta</h2>
                        <p class="text-muted mb-4">Ha ocurrido un problema al intentar procesar su receta</p>
                        
                        <div class="alert alert-danger mx-auto" style="max-width: 600px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Motivo:</strong> {{ $mensaje }}
                        </div>

                        <div class="card mt-4 border-info">
                            <div class="card-body">
                                <h5 class="card-title text-info">
                                    <i class="fas fa-phone-alt me-2"></i>
                                    ¿Necesita ayuda?
                                </h5>
                                <p class="mb-0">
                                    Puede comunicarse con nosotros al teléfono 
                                    <strong class="text-primary">800-123-4567</strong>
                                    o enviar un correo a 
                                    <strong class="text-primary">ayuda@farmacia.com</strong>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 justify-content-center mt-4 flex-wrap">
                            <a href="{{ route('tas_subirRecetaView') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver a Intentar
                            </a>
                            <a href="{{ route('tas_inicioView') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2"></i>
                                Ir al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection