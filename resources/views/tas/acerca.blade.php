@extends('layouts.template')
@section('title', 'Acerca de Nosotros')

@section('content')
<div class="container py-5">

    <h1 class="text-center fw-bold mb-5" style="color:#003865;">Acerca de Nosotros</h1>

    <!-- Quiénes somos -->
    <section id="quienes" class="mb-5 acerca-section">
        <h2 class="mb-4"><i class="fas fa-users me-2 text-primary"></i>Quiénes somos</h2>
        <div class="acerca-card p-4">
            <p>
                Te Acerco Salud (TAS) es una plataforma tecnológica diseñada para facilitar el acceso a medicamentos en la ciudad de Culiacán, Sinaloa, México. Nuestro objetivo es conectar a los pacientes con múltiples farmacias de forma rápida, práctica y confiable, asegurando que sus tratamientos estén disponibles cuando más los necesitan.
            </p>
            <p>
                Creemos en un sistema de salud más accesible y eficiente, donde la tecnología sea la herramienta principal para mejorar la experiencia del paciente.
            </p>
            <p>
                Nos enfocamos en ofrecer una solución integral que permita consultar disponibilidad, comparar sucursales, enviar recetas y obtener medicamentos sin complicaciones.
            </p>
        </div>
    </section>

    <!-- Aviso de privacidad -->
    <section id="privacidad" class="mb-5 acerca-section">
        <h2 class="mb-4"><i class="fas fa-shield-alt me-2 text-primary"></i>Aviso de Privacidad</h2>
        <div class="acerca-card p-4">
            <p>En TAS - Te Acerco Salud, la protección de tus datos personales es una prioridad. Recopilamos únicamente la información necesaria para brindarte un servicio seguro y confiable, cumpliendo con las leyes de protección de datos aplicables en México.</p>
            <p>Tus datos podrán ser utilizados para:</p>
            <ul>
                <li>Verificar recetas médicas</li>
                <li>Gestionar pedidos y envíos</li>
                <li>Comunicar estatus de compra</li>
                <li>Mejorar la experiencia del usuario</li>
                <li>Fines estadísticos internos</li>
            </ul>
            <p>Nunca vendemos ni compartimos tu información con terceros no autorizados. Puedes solicitar acceso, modificación o eliminación de tus datos en cualquier momento.</p>
        </div>
    </section>

    <!-- Términos y condiciones -->
    <section id="terminos" class="mb-5 acerca-section">
        <h2 class="mb-4"><i class="fas fa-file-contract me-2 text-primary"></i>Términos y Condiciones</h2>
        <div class="acerca-card p-4">
            <ul>
                <li>La información médica proporcionada debe ser verídica.</li>
                <li>El usuario es responsable del correcto uso de la plataforma.</li>
                <li>TAS funge como intermediario entre pacientes y farmacias; no sustituimos a un profesional de salud.</li>
                <li>Los tiempos de surtido y disponibilidad pueden variar según la sucursal.</li>
                <li>Nos reservamos el derecho de actualizar funciones, precios o políticas sin previo aviso.</li>
                <li>El uso de la plataforma implica la aceptación de estos términos.</li>
            </ul>
        </div>
    </section>

    <!-- Blog -->
    <section id="blog" class="mb-5 acerca-section">
        <h2 class="mb-4"><i class="fas fa-blog me-2 text-primary"></i>Blog</h2>
        <div class="acerca-card p-4">
            <ul>
                <li>Consejos de salud y bienestar</li>
                <li>Hábitos para mejorar tu tratamiento</li>
                <li>Información sobre medicamentos</li>
                <li>Noticias del sector farmacéutico</li>
                <li>Actualizaciones de TAS</li>
            </ul>
            <p class="mt-2">Nuestro propósito es informarte con contenido claro, confiable y fácil de entender.</p>
        </div>
    </section>

</div>

{{-- Footer al final del contenido --}}
@include('layouts.footer') 

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/acerca.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

@push('scripts')
<script>
    // botón del footer para subir
    const btnScrollTop = document.getElementById('btnScrollTop');
    if (btnScrollTop) {
        btnScrollTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
</script>
@endpush
