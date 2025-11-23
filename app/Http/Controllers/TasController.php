<?php

namespace App\Http\Controllers;

use App\Services\TasService;
use App\Services\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TasController extends Controller
{
    private $tasService;

    public function __construct(TasService $tasService)
    {
        $this->tasService = $tasService;
    }

    public function tas_inicioView()
    {
        return view('tas.inicio');
    }

    public function tas_loginView()
    {
        return view('tas.access');
    }

    public function tas_registroView()
    {
        return view('tas.access');
    }

    public function tas_inicioSesion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => ['required', 'string', 'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/'],
            'nip' => ['required', 'string'],
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'correo.regex' => 'El correo no tiene un formato válido.',
            'nip.required' => 'El nip es obligatorio.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'login')->withInput();
        }

        $usuario = $this->tasService->iniciarSesion($request->correo, $request->nip);

        if (! $usuario) {
            return back()->with('error', 'Ha ocurrido un error');
        }

        if (! $usuario instanceof Usuario) {
            return back()->with('error', $usuario);
        }

        session([
            'usuario' => [
                'id' => $usuario->getId(),
                'correo' => $usuario->getCorreo(),
                'nombre' => $usuario->getNombre(),
            ],
        ]);

        return redirect()->route('tas_inicioView')->with('success', 'Bienvenido '.$usuario->getNombre());
    }

    private function validarDatosCliente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => ['required', 'string', 'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/'],
            'nip' => ['required', 'string', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/'],
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'apellido' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'correo.regex' => 'El correo no tiene un formato válido.',
            'nip.required' => 'La contraseña es obligatoria.',
            'nip.regex' => 'Debe contener mayúsculas, minúsculas, número y un carácter especial.',
            'nombre.required' => 'El nombre es requerido.',
            'nombre.regex' => 'El nombre solo puede contener letras.',
            'apellido.required' => 'El apellido es requerido.',
            'apellido.regex' => 'El apellido solo puede contener letras.',
        ]);

        return $validator->fails() ? $validator->errors() : true;
    }

    public function validarPasoCliente(Request $request)
    {
        $validacion = $this->validarDatosCliente($request);
        if ($validacion === true) return response()->json(['ok' => true]);

        return response()->json(['ok' => false, 'errores' => $validacion->all()]);
    }

    private function agregarReglaLuhn()
    {
        Validator::extend('luhn', function ($attribute, $value) {
            $number = preg_replace('/\D/', '', $value);
            $sum = 0;
            $alt = false;

            for ($i = strlen($number) - 1; $i >= 0; $i--) {
                $n = intval($number[$i]);
                if ($alt) {
                    $n *= 2;
                    if ($n > 9) $n -= 9;
                }
                $sum += $n;
                $alt = ! $alt;
            }

            return ($sum % 10) === 0;
        });
    }

    private function validarDatosTarjeta(Request $request)
    {
        $this->agregarReglaLuhn();

        $validator = Validator::make($request->all(), [
            'numero_tarjeta' => ['required', 'regex:/^\d{4}\s\d{4}\s\d{4}\s\d{4}$/', 'luhn'],
            'nombre_tarjeta' => ['required', 'string'],
            'fecha_vencimiento' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => ['required', 'digits_between:3,4'],
        ], [
            'numero_tarjeta.required' => 'El número de tarjeta es obligatorio.',
            'numero_tarjeta.regex' => 'Debe ser XXXX XXXX XXXX XXXX.',
            'numero_tarjeta.luhn' => 'El número de tarjeta no es válido.',
            'nombre_tarjeta.required' => 'El nombre en la tarjeta es obligatorio.',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
            'fecha_vencimiento.regex' => 'Formato inválido (MM/AA).',
            'cvv.required' => 'El CVV es obligatorio.',
            'cvv.digits_between' => 'El CVV debe tener 3 o 4 dígitos.',
        ]);

        return $validator->fails() ? $validator->errors() : true;
    }

    public function tas_crearCuenta(Request $request)
{
    try {

        \Log::info('Inicio creación de usuario', ['request' => $request->all()]);

        // VALIDACIÓN DE DATOS PERSONALES
        $validacionCliente = $this->validarDatosCliente($request);

        if ($validacionCliente !== true) {
            \Log::warning('Error en datos personales', ['errores' => $validacionCliente]);
            return back()->withErrors($validacionCliente, 'registro')->withInput();
        }

        // VALIDACIÓN TARJETA (si no se omite)
        if ($request->input('omitir_pago') != '1') {

            $validacionTarjeta = $this->validarDatosTarjeta($request);

            if ($validacionTarjeta !== true) {
                \Log::warning('Error en datos de tarjeta', ['errores' => $validacionTarjeta]);
                return back()->withErrors($validacionTarjeta, 'tarjeta')->withInput();
            }
        }

        // INTENTO DE CREACIÓN EN BASE DE DATOS
        \Log::info('Intentando crear usuario en BD...');

        $resultado = $this->tasService->crearUsuario(
            $request->correo,
            $request->nip,
            $request->nombre,
            $request->apellido
        );

        // SI LA BD RESPONDE ERROR
        if ($resultado != 1) {
            \Log::error('Error al crear usuario en BD', ['resultado' => $resultado]);
            return back()->with('error', $resultado)->withInput();
        }

        \Log::info('Usuario creado correctamente');

        return redirect()
            ->route('tas_loginView')
            ->with('success', 'Usuario creado correctamente');

    } catch (\Exception $e) {

        \Log::error('EXCEPCIÓN FATAL CREANDO USUARIO', [
            'mensaje' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Error interno al crear usuario: '.$e->getMessage())->withInput();
    }
}

    public function logout()
    {
        $usuario = session('usuario');
        if ($usuario) {
            $this->tasService->cerrarSesion($usuario['correo']);
        }
        session()->flush();
        return redirect()->route('tas_loginView');
    }
}
