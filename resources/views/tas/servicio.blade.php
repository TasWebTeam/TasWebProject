@extends('layouts.template')
@section('title', 'Servicio al Cliente')

@section('content')
<div class="container py-5">

    <h1 class="text-center fw-bold mb-5" style="color:#003865;">Servicio al Cliente</h1>

    <!-- Preguntas frecuentes -->
    <section id="faq" class="mb-5 servicio-section">
        <h2 class="mb-4"><i class="fas fa-question-circle me-2 text-primary"></i>Preguntas Frecuentes</h2>
        <div class="faq-card p-4">
            <p><strong>1. ¿Puedo surtir una receta desde cualquier farmacia?</strong> <br>
                Sí, TAS busca disponibilidad en todas las farmacias afiliadas y te muestra las opciones más cercanas.</p>
            <p><strong>2. ¿Cuánto tarda en confirmarse mi pedido?</strong> <br>
                Depende de la farmacia seleccionada; normalmente entre 5 y 15 minutos.</p>
            <p><strong>3. ¿Puedo subir una foto de receta?</strong> <br>
                Sí, aceptamos fotografías claras y legibles de la receta.</p>
            <p><strong>4. ¿Tienen costo sus servicios?</strong> <br>
                No, TAS es completamente gratuito para los usuarios.</p>
            <p><strong>5. ¿Qué pasa si una farmacia no tiene stock?</strong> <br>
                La plataforma te ofrecerá otras sucursales compatibles o alternativas cercanas.</p>
        </div>
    </section>

    <!-- Contacto -->
    <section id="contacto" class="mb-5 servicio-section">
        <h2 class="mb-4"><i class="fas fa-envelope me-2 text-primary"></i>Contacto</h2>
        <div class="contact-card p-4">
            <p>Si necesitas ayuda, soporte o tienes alguna duda, puedes comunicarte con nosotros a través de:</p>
            <ul class="list-unstyled contacto-list">
                <li>📩 <strong>Correo:</strong> soporte@tas.com</li>
                <li>📞 <strong>Teléfono:</strong> 800-123-4567</li>
                <li>💬 <strong>Chat en línea:</strong> Disponible dentro de la app</li>
                <li>📍 <strong>Horario:</strong> Lunes a sábado de 9 AM a 8 PM</li>
            </ul>
        </div>
    </section>

    <!-- Retiro en sucursal -->
    <section id="retiro" class="mb-5 servicio-section">
        <h2 class="mb-4"><i class="fas fa-store me-2 text-primary"></i>Retiro en Sucursal</h2>
        <div class="retiro-card p-4">
            <ul>
                <li>Selecciona la farmacia más cercana</li>
                <li>Sube tu receta (si aplica)</li>
                <li>Espera la confirmación de disponibilidad</li>
                <li>Acude a la sucursal con tu identificación y número de folio</li>
                <li>Recoge tus medicamentos sin hacer filas innecesarias</li>
            </ul>
            <p class="mt-3 text-muted">Este proceso permite ahorrar tiempo y asegurar que el medicamento esté listo al llegar.</p>
        </div>
    </section>

</div>

{{-- Footer al final del contenido --}}
@include('layouts.footer')  {{-- si tu carpeta se llama "lyouts", cámbialo a @include('lyouts.footer') --}}

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/serviciofooter.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

@endpush
