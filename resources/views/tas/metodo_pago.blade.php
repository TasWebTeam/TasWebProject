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
                Método de pago registrado
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

            <div class="card-brand-watermark">
                @switch(strtolower($tarjeta->getBrand()))
                    @case('visa')
                        <svg viewBox="0 0 48 16" fill="currentColor">
                            <path
                                d="M15.6 2.8l-5.9 10.4h-3L4.4 4.7c-.2-.4-.3-.6-.6-.7C3.3 3.8 2.5 3.6 1.8 3.4L1.9 3h5c.6 0 1.2.4 1.3 1.1l1.2 6.5 3-7.8h3.2zm12.5 7c0-2.7-3.8-2.9-3.7-4.1 0-.4.4-.8 1.2-.9.4-.1 1.5-.1 2.8.5l.5-2.3c-.7-.2-1.6-.5-2.7-.5-2.9 0-4.9 1.5-4.9 3.7 0 1.6 1.4 2.5 2.5 3 1.1.6 1.5.9 1.5 1.4 0 .7-.9 1.1-1.7 1.1-1.4 0-2.2-.2-3.4-.7l-.6 2.7c.8.3 2.2.6 3.6.6 3.1.1 5.1-1.4 5.1-3.6zm7.4 3.4h2.8l-2.5-10.4h-2.6c-.6 0-1 .3-1.3.8l-4.4 9.6h3l.6-1.7h3.7l.7 1.7zm-3.3-4l1.5-4.2.9 4.2h-2.4zm-13.1-6.4l-2.4 10.4h-2.9l2.4-10.4h2.9z" />
                        </svg>
                    @break

                    @case('mastercard')
                        <svg viewBox="0 0 48 32" fill="none">
                            <circle cx="19" cy="16" r="12" fill="#EB001B" opacity="0.3" />
                            <circle cx="29" cy="16" r="12" fill="#F79E1B" opacity="0.3" />
                        </svg>
                    @break

                    @case('amex')
                        <div class="amex-logo">AMEX</div>
                    @break
                @endswitch
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
                    <i class="fas fa-edit me-2"></i>
                    Editar
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/metodo-pago-script.js') }}"></script>
@endpush
