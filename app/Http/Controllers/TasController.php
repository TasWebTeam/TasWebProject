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

    public function tas_subirRecetaView()
    {
        return view('tas.subir_receta');
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
            return back()->withErrors($validator)->withInput();
        }
        $correo = $request->input('correo');
        $nip = $request->input('nip');

        $usuario = $this->tasService->iniciarSesion($correo, $nip);
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
    return redirect()->route('tas_inicioView') ->with('success', 'Bienvenido ' . $usuario->getNombre());   
 }

    public function tas_crearCuenta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => ['required', 'string', 'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/'],
            'nip' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/'],
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'apellido' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'correo.regex' => 'El correo no tiene un formato válido.',
            'nip.required' => 'La contraseña es obligatoria.',
            'nip.regex' => 'La contraseña debe contener al menos una minúscula, una mayúscula, un número y un carácter especial.',
            'nombre.required' => 'El nombre es requerido.',
            'nombre.regex' => 'El nombre solo puede contener letras.',
            'apellido.required' => 'El apellido es requerido.',
            'apellido.regex' => 'El apellido solo puede contener letras.',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $correo = $request->input('correo');
        $nombre = $request->input('nombre');
        $apellido = $request->input('apellido');
        $nip = $request->input('nip');

        $resultado = $this->tasService->crearUsuario($correo, $nip, $nombre, $apellido);

        if ($resultado != 1) {
            return back()->with('error', $resultado)->withInput();
        }

        return redirect()->route('tas_loginView')->with('success', 'Usuario creado correctamente');
    }

    public function logout()
    {
        $usuarioSession = session('usuario');
        $correo = $usuarioSession['correo'] ?? null;

        if ($correo) {
            $this->tasService->cerrarSesion($correo);
        }

        session()->flush();

        return redirect()->route('tas_loginView');
    }
}
