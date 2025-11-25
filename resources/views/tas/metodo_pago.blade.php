@extends('layouts.template')

@section('title', 'Método de pago')

@section('content')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .payment-card {
            animation: fadeInUp 0.8s ease-out;
        }

        .payment-icon {
            animation: float 3s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        .payment-icon:hover {
            animation: bounce 0.6s ease;
            color: #003d6b !important;
        }

        .btn-animated {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-animated::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-animated:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-animated:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 91, 150, 0.4) !important;
        }

        .btn-animated:active {
            transform: translateY(-1px);
        }

        .text-animated {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .subtitle-animated {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .button-container {
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        /* Animación para el formulario */
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                max-height: 1000px;
                transform: translateY(0);
            }
        }

        .form-show {
            animation: slideDown 0.5s ease-out forwards;
            display: block !important;
        }

        .form-hide {
            animation: slideDown 0.3s ease-out reverse forwards;
        }

        /* Estilos para los inputs */
        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #005B96;
            box-shadow: 0 0 0 0.2rem rgba(0, 91, 150, 0.25);
        }

        /* Decoración de fondo */
        .bg-decoration {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, #005B96 0%, #003d6b 100%);
            opacity: 0.05;
            animation: pulse 4s ease-in-out infinite;
        }

        .bg-circle-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
        }

        .bg-circle-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation-delay: 1s;
        }
    </style>

    <div class="container d-flex justify-content-center align-items-center position-relative" style="min-height: 75vh;">

        {{-- Círculos decorativos de fondo --}}
        <div class="bg-decoration bg-circle-1"></div>
        <div class="bg-decoration bg-circle-2"></div>

        <div class="card payment-card shadow-lg border-0 p-4 text-center position-relative"
            style="max-width: 500px; border-radius: 20px; overflow: visible;">

            {{-- Icono --}}
            <div class="mb-4">
                <i class="fas fa-credit-card payment-icon" style="font-size: 70px; color: #005B96;"></i>
            </div>

            {{-- Mensaje --}}
            <h4 class="fw-bold mb-2 text-animated">No se ha registrado un método de pago</h4>
            <p class="text-muted mb-4 subtitle-animated">
                Aún no tienes una tarjeta guardada. Agrega una para continuar.
            </p>

            {{-- Botón para añadir método de pago --}}
            <div class="button-container">
                <button id="btnAddPayment" class="btn btn-primary btn-animated px-4 py-2 position-relative"
                    style="background-color: #005B96; border-radius: 10px; border: none; z-index: 1;">
                    <i class="fas fa-plus-circle me-2"></i>
                    <span style="position: relative; z-index: 2;">Añadir método de pago</span>
                </button>
            </div>

            {{-- Formulario de tarjeta (oculto inicialmente) --}}
            <div id="cardForm" class="mt-4" style="display: none; opacity: 0;">
                <hr class="my-4">

                    <div class="mb-3 text-start">
                        <label for="cardNumber" class="form-label fw-semibold">
                            <i class="fas fa-credit-card me-1"></i> Número de tarjeta
                        </label>
                        <input type="text" class="form-control" id="cardNumber" name="card_number"
                            placeholder="1234 5678 9012 3456" maxlength="19" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label for="cardHolder" class="form-label fw-semibold">
                            <i class="fas fa-user me-1"></i> Titular de la tarjeta
                        </label>
                        <input type="text" class="form-control" id="cardHolder" name="cardholder_name"
                            placeholder="JUAN PÉREZ" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 text-start">
                            <label for="expiryDate" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1"></i> Fecha de expiración
                            </label>
                            <input type="text" class="form-control" id="expiryDate" name="expiry_date"
                                placeholder="MM/AA" maxlength="5" required>
                        </div>
                        <div class="col-md-6 text-start">
                            <label for="cvv" class="form-label fw-semibold">
                                <i class="fas fa-lock me-1"></i> CVV
                            </label>
                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123"
                                maxlength="4" required>
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isDefault" name="is_default">
                            <label class="form-check-label" for="isDefault">
                                Establecer como método de pago principal
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1" style="border-radius: 10px;">
                            <i class="fas fa-save me-2"></i> Guardar tarjeta
                        </button>
                        <button type="button" id="btnCancel" class="btn btn-secondary" style="border-radius: 10px;">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
            </div>
        </div>
    </div>

    <script>
        // Referencias a elementos
        const btnAddPayment = document.getElementById('btnAddPayment');
        const btnCancel = document.getElementById('btnCancel');
        const cardForm = document.getElementById('cardForm');
        const cardNumberInput = document.getElementById('cardNumber');
        const expiryDateInput = document.getElementById('expiryDate');
        const cvvInput = document.getElementById('cvv');

        // Mostrar formulario
        btnAddPayment.addEventListener('click', function(e) {
            e.preventDefault();
            cardForm.style.display = 'block';
            cardForm.classList.add('form-show');
            btnAddPayment.style.display = 'none';
        });

        // Ocultar formulario
        btnCancel.addEventListener('click', function() {
            cardForm.classList.remove('form-show');
            cardForm.classList.add('form-hide');
            setTimeout(() => {
                cardForm.style.display = 'none';
                cardForm.classList.remove('form-hide');
                btnAddPayment.style.display = 'inline-block';
            }, 300);
        });

        // Formatear número de tarjeta (espacios cada 4 dígitos)
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Formatear fecha de expiración (MM/AA)
        expiryDateInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                e.target.value = value.slice(0, 2) + '/' + value.slice(2, 4);
            } else {
                e.target.value = value;
            }
        });

        // Solo números en CVV
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Agregar efecto de partículas al pasar el mouse sobre la tarjeta
        document.querySelector('.payment-card').addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.3s ease';
        });

        document.querySelector('.payment-card').addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });

        // Efecto de click en el botón
        btnAddPayment.addEventListener('click', function(e) {
            // Crear efecto de ripple
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s ease-out';
            ripple.style.pointerEvents = 'none';

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });

        // Agregar keyframes para el efecto ripple
        const style = document.createElement('style');
        style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
        document.head.appendChild(style);
    </script>
@endsection
