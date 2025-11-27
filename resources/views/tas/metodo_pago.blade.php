@extends('layouts.template')

@section('title', 'Método de Pago')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/metodo-pago-styles.css') }}">
@endpush

@section('content')

    <div class="container d-flex justify-content-center align-items-center position-relative" style="min-height: 75vh;">

        <div class="bg-decoration bg-circle-1"></div>
        <div class="bg-decoration bg-circle-2"></div>

        <div class="card payment-card shadow-lg border-0 p-4 text-center position-relative"
            style="max-width: 540px; border-radius: 20px; overflow: visible;">

            <h4 class="fw-bold mb-4" style="color: #005B96;">
                <i class="fas fa-wallet me-2"></i>
                Método De Pago Registrado
            </h4>

            <div class="credit-card mx-auto my-4" data-brand="{{ strtolower($tarjeta->getBrand()) }}">

                <div class="card-logo-top brand-top-right">
                    @switch(strtolower($tarjeta->getBrand()))
                        @case('visa')
                            <img src="{{ asset('images/cards/visa.png') }}" class="card-logo-img" alt="Visa">
                        @break

                        @case('mastercard')
                            <img src="{{ asset('images/cards/mastercard.png') }}" class="card-logo-img" alt="Mastercard">
                        @break

                        @case('amex')
                            <img src="{{ asset('images/cards/amex.png') }}" class="card-logo-img" alt="American Express">
                        @break
                    @endswitch
                </div>

                <div class="card-chip-container">
                    <div class="card-chip">
                        <div class="chip-line"></div>
                        <div class="chip-line"></div>
                        <div class="chip-line"></div>
                        <div class="chip-line"></div>
                    </div>
                    <div class="card-wireless">
                        <i class="fas fa-wifi"></i>
                    </div>
                </div>

                <div class="card-number">
                    <span class="number-group">••••</span>
                    <span class="number-group">••••</span>
                    <span class="number-group">••••</span>
                    <span class="number-group">{{ $tarjeta->getLast4() }}</span>
                </div>

                <div class="card-expiry">
                    <div class="card-label">Válido hasta</div>
                    <div class="card-info">{{ $tarjeta->getFechaExp() }}</div>
                </div>
            </div>

            <div class="card-security-info p-3 mt-3">
                <div class="d-flex justify-content-around align-items-center">
                    <div class="security-item">
                        <i class="fas fa-lock security-icon"></i>
                        <span class="security-text">Encriptado</span>
                    </div>
                    <div class="security-divider"></div>
                    <div class="security-item">
                        <i class="fas fa-shield-alt security-icon"></i>
                        <span class="security-text">Protegido</span>
                    </div>
                    <div class="security-divider"></div>
                    <div class="security-item">
                        <i class="fas fa-check-circle security-icon"></i>
                        <span class="security-text">Verificado</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
                <button class="btn btn-outline-primary btn-action" onclick="editCard()">
                    <i class="fas fa-edit me-2"></i>Cambiar
                </button>
            </div>
        </div>
    </div>

    <div id="editCardModal" class="modal-overlay @if ($errors->editarTarjeta->any()) show @endif">
        <div class="modal-container">
            <div class="modal-card">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card me-2"></i>
                    Cambiar Método de Pago
                </h5>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->editarTarjeta->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->editarTarjeta->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="updateCardForm" action="{{ route('tas_actualizarTarjeta') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Número de Tarjeta</label>
                        <div class="position-relative">
                            <input type="text"
                                class="form-control @error('numero_tarjeta', 'editarTarjeta') is-invalid @enderror"
                                id="cardNumber" name="numero_tarjeta" placeholder="1234 5678 9012 3456" maxlength="19"
                                value="{{ old('numero_tarjeta') }}" required>
                            <div id="cardBrandLogo" class="card-brand-logo">
                                <img src="" alt="Card Brand">
                            </div>
                        </div>
                        <small class="text-muted">Ingrese los 16 dígitos de la tarjeta</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiryDate" class="form-label">Fecha de Expiración</label>
                            <input type="text"
                                class="form-control @error('fecha_vencimiento', 'editarTarjeta') is-invalid @enderror"
                                id="expiryDate" name="fecha_vencimiento" placeholder="MM/AA" maxlength="5"
                                value="{{ old('fecha_vencimiento') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control @error('cvv', 'editarTarjeta') is-invalid @enderror"
                                id="cvv" name="cvv" placeholder="123" maxlength="4"
                                value="{{ old('cvv') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cardHolder" class="form-label">Nombre del Titular</label>
                        <input type="text"
                            class="form-control @error('nombre_tarjeta', 'editarTarjeta') is-invalid @enderror"
                            id="cardHolder" name="nombre_tarjeta" placeholder="NOMBRE APELLIDO"
                            style="text-transform: uppercase;" value="{{ old('nombre_tarjeta') }}" required>
                    </div>

                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="cancelEdit()">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/metodo-pago-script.js') }}"></script>
@endpush
