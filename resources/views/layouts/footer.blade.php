<footer class="text-white pt-5 pb-4 footer-fullwidth"> 
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4 text-center text-md-start">
                <img src="{{ asset('images/logo.png') }}" alt="TAS Logo" class="img-fluid mb-2" style="max-height: 60px;">
                <h5 class="fw-bold" style="color: #00a1e0;">TAS - Te Acerco Salud</h5>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold">Acerca de nosotros</h6>
                <ul class="list-unstyled">
                    <li><a href="{{route('acerca')}}#quienes"class="text-white text-decoration-none">Quiénes somos</a></li>
                    <li><a href="{{route('acerca')}}#privacidad"class="text-white text-decoration-none">Aviso de privacidad</a></li>
                    <li><a href="{{route('acerca')}}#terminos" class="text-white text-decoration-none">Términos y condiciones</a></li>
                    <li><a href="{{route('acerca')}}#blog" class="text-white text-decoration-none">Blog</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold">Servicio al cliente</h6>
                <ul class="list-unstyled">
                    <li><a href="{{route('servicio')}}#faq" class="text-white text-decoration-none">Preguntas frecuentes</a></li>
                    <li><a href="{{route('servicio')}}#contacto" class="text-white text-decoration-none">Contacto</a></li>
                    <li><a href="{{route('servicio')}}#retiro" class="text-white text-decoration-none">Retiro en sucursal</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-4 text-center text-md-start">
                <h6 class="fw-bold">Síguenos</h6>
                <div class="d-flex gap-2 mb-3 justify-content-center justify-content-md-start">
                    <a href="#" class="text-white fs-5"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white fs-5"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="text-white fs-5"><i class="fab fa-youtube"></i></a>
                </div>
                <h6 class="fw-bold">Métodos de pago</h6>
                @php
                    $paymentCards = [
                        ['name' =>'Visa', 'file' => 'visa.png'],
                        ['name' =>'MasterCard', 'file' => 'mastercard.png'],
                        ['name' =>'Amex', 'file' => 'amex.png'],
                    ];
                @endphp
                <div class="d-flex align-items-center gap-3 mt-2 justify-content-center justify-content-md-start">
                        @foreach ($paymentCards as $card)
                            <img src="{{ asset('images/cards/' . $card['file']) }}"
                                alt="{{ $card['name'] }}"
                                class="payment-logo">
                        @endforeach 
                </div>
            </div>
        </div>

        <hr class="border-light">

        <div class="text-center small">
            &copy; {{ date('Y') }} TAS - Te Acerco Salud. Todos los derechos reservados.
        </div>
    </div>
    <!--<a href="#" id="btnScrollTop" class="btn position-fixed"
       style="bottom: 20px; right: 20px; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; z-index: 1000;">
        <i class="fas fa-arrow-up"></i>
    </a> -->
</footer>