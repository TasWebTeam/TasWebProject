@extends('layouts.template')
@section('title', 'Servicio al Cliente')

@section('content')
<div class="container py-5">

    <h1 class="text-center fw-bold mb-5" style="color:#003865;">Servicio al Cliente</h1>

    <section id="faq" class="mb-5 servicio-section">
        <h2 class="mb-4"><i class="fas fa-question-circle me-2 text-primary"></i>Preguntas Frecuentes</h2>
        <div class="faq-card p-4">
            <p><strong>1. ¿Puedo surtir una receta desde cualquier farmacia?</strong> <br>
                Sí, TAS busca la disponibilidad de los medicamenttos en las sucursales hasta satisfacer tu receta.</p>
            <p><strong>2. ¿Cuánto tarda en confirmarse mi pedido?</strong> <br>
                Depende de la farmacia seleccionada; Pero es un lapso de tiempo corto.</p>
            <p><strong>3. ¿Puedo subir una foto de receta?</strong> <br>
                No, por el momento solo puedes seleccionar los  medicamentos de tu receta y nosotros nos encargaremos de surtirla.</p>
            <p><strong>4. ¿Tienen costo sus servicios?</strong> <br>
                Si. Tas cobra un 15% del porcentaje total de tu receta.</p>
            <p><strong>5. ¿Qué pasa si una farmacia no tiene stock?</strong> <br>
                La plataforma buscara las sucursales cercanas para lograar cumplir con tu receta.</p>
        </div>
    </section>

    <section id="contacto" class="mb-5 servicio-section">
        <h2 class="mb-4"><i class="fas fa-envelope me-2 text-primary"></i>Contacto</h2>
        <div class="contact-card p-4">
            <p>Si necesitas ayuda, soporte o tienes alguna duda, puedes comunicarte con nosotros a través de:</p>
            <ul class="list-unstyled contacto-list">
                <li>📩 <strong>Correo:</strong> soporte@tas.com</li>
                <li>📞 <strong>Teléfono:</strong> 800-741-123</li>
                <li>💬 <strong>Chat en línea:</strong> Por el momento no esta disponible.</li>
                <li>📍 <strong>Horario:</strong> Servicio de atencion de lunes a viernes. 9:00 Am - 11:00 Pm.</li>
            </ul>
        </div>
    </section>

    <section id="retiro" class="mb-5 servicio-section">
        <h2 class="mb-4"><i class="fas fa-store me-2 text-primary"></i>Retiro en Sucursal</h2>
        <div class="retiro-card p-4">
            <ul>
                <li>Selecciona la farmacia más cercana</li>
                <li>Completa tu receta</li>
                <li>Acude a la sucursal con tu identificación y tu receta de la app</li>
                <li>Recoge tus medicamentos sin hacer filas innecesarias</li>
            </ul>
            <p class="mt-3 text-muted">Este proceso permite ahorrar tiempo y asegurar que el medicamento esté listo al llegar.</p>
        </div>
    </section>

</div>

@include('layouts.footer') 

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/serviciofooter.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

@endpush
