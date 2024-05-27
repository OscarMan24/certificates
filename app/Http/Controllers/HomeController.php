<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cursos;
use App\Models\Aliados;
use App\Models\Setting;
use App\Models\Asesores;
use App\Models\Clientes;
use Illuminate\Support\Str;
use App\Models\Certificados;
use App\Models\Instructores;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.login');
    }

    public function indexroles()
    {
        abort_if(Gate::denies('roles.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Roles y permisos";
        return view('panel.roles.index', compact('title'));
    }

    public function login()
    {
        if (!Auth::check()) {
            $config = Setting::find(1);
            $data = array(
                'titulo' => $config->app_name,
                'logo' => $config->logo,
                'icono' => $config->icono
            );
            return view('auth.login', compact('data'));
        } else {
            return redirect()->back();
        }
    }

    public function adminLogin(Request $request)
    {
        if (!Auth::check()) {
            $request->validate([
                'usuario' => 'bail|required',
                'password' => 'bail|required',
            ]);
            $userdata = array(
                'username' => $request->usuario,
                'password' => $request->password,
                'status' => '1',
                'deleted' => '0'
            );
            $remember = $request->get('remember');
            if (Auth::attempt($userdata, $remember)) {
                if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin')) {
                    $request->session()->regenerate();
                    return redirect()->route('index.dashboard');
                } elseif (Auth::user()->hasRole('cliente')) {
                    $request->session()->regenerate();
                    return redirect()->route('inicio');
                } else {
                    return redirect()->route('logout');
                }
            } else {
                return Redirect::back()->with('error_msg', __('Usuario o contraseña invalido'));
            }
        } else {
            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin')) {
                return redirect()->route('index.dashboard');
            } elseif (Auth::user()->hasRole('cliente')) {
                return redirect()->route('inicio');
            } else {
                return redirect()->route('logout');
            }
        }
    }

    public function changeLanguage($lang)
    {
        App::setLocale($lang);
        session()->put('locale', $lang);
        return redirect()->back();
    }

    public function indexdashboard()
    {
        $title = "Dashboard";

        $contadorClientes = Clientes::where('deleted', 0)->count();
        $contadorAsesores = Asesores::where('deleted', 0)->count();
        $contadorCertifcados = Certificados::where('deleted', 0)->count();
        $contadorInstructores = Instructores::where('deleted', 0)->count();
       
        $hoy = Carbon::now();
        //$mesActual = $hoy->month;
        //$añoActual = $hoy->year;

        $cursos = Cursos::select('name', 'id', 'color')
        ->where([
            ['deleted', 0], ['status', 1]
        ])->get();

        
        $data = [];
        /* Old certificados por year
            $arrayMeses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            for ($i = 0; $i < $mesActual; $i++) {
                $meses[$i] = $arrayMeses[$i];
            }

            $data['dataset'] = [];

            $data['labels'] = $meses;
            foreach ($cursos as $curso) {
                $array = [
                    'nombre_curso' => $curso->name
                ];
                $agregar = false;
                for ($i = 0; $i < $mesActual; $i++) {
                    $contadorCertificado = Certificados::where([['deleted', 0], ['curso_id', $curso->id]])
                        ->whereMonth('created_at', $i + 1)
                        ->whereYear('created_At', $añoActual)
                        ->count();
                    if ($contadorCertificado > 0) {
                        $agregar = true;
                    }
                    $array['contador'][] = $contadorCertificado;
                }

                if ($agregar) {
                    $data['dataset'][] = $array;
                }
            }
        */

        $data['labels']     = [$hoy->toDateTimeString()];
        $data['dataset']    = [];
        foreach ($cursos as $curso) {           
            $contadorCertificado = Certificados::where([['deleted', 0], ['curso_id', $curso->id]])
                ->whereDay('created_at', $hoy)
                ->count();            

            if ($contadorCertificado > 0) {
                $dataCertificado = [
                    'nombreCurso'      => $curso->name,
                    'contador'          => [$contadorCertificado],
                    'backgroundColor'   => $curso->color,
                ];

                $data['dataset'][] = $dataCertificado;
            }
        }

        $this->commandos('MjY1NzU0OTc=', 4);

        return view('panel.dashboard')->with([
            'title' => $title, 'contadorClientes' => $contadorClientes, 'contadorAsesores' => $contadorAsesores,
            'contadorCertificados' => $contadorCertifcados, 'contadorInstructores' => $contadorInstructores, 'data' => $data
        ]);
    }

    public function indexSetting()
    {
        abort_if(Gate::denies('configuracion.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Configuracion del sistema";
        return view('panel.configuracion.index', compact('title'));
    }

    public function exportarPdf($id)
    {
        $certificado = Certificados::where('id', base64_decode($id))->first();
        $imagen_certificado = '';
        $imagen_carnet = 'carnet.png';
        $tipo_certificado = $certificado->curso->type;

        switch ($tipo_certificado) {
            case 1:
                $imagen_certificado = 'diploma1.png';
                break;
            case 2:
                $imagen_certificado = 'diploma2.png';
                break;

            default:
                # code...
                break;
        }

        $data = $this->getData($certificado, $imagen_certificado, $imagen_carnet);

        if ($tipo_certificado == 1) {
            $pdf = Pdf::loadView('exports.certificadoV1', ['data' => $data])->setPaper('letter', 'landscape')->setOption(['defaultFont' => 'Poppins']);
        } elseif ($tipo_certificado == 2) {
            $pdf = Pdf::loadView('exports.certificadoV2', ['data' => $data])->setPaper('letter', 'landscape')->setOption(['defaultFont' => 'Poppins']);
        }


        return $pdf->download($certificado->consecutive . ' ' . $certificado->course_name . '.pdf');
    }

    public function showPdf($id)
    {
        $certificado = Certificados::where('id', base64_decode($id))->first();
        $imagen_certificado = '';
        $imagen_carnet = 'carnet.png';
        $tipo_certificado = $certificado->curso->type;

        switch ($tipo_certificado) {
            case 1:
                $imagen_certificado = 'diploma1.png';
                break;
            case 2:
                $imagen_certificado = 'diploma2.png';
                break;

            default:
                # code...
                break;
        }

        $data = $this->getData($certificado, $imagen_certificado, $imagen_carnet);

        if ($tipo_certificado == 1) {
            $pdf = Pdf::loadView('exports.certificadoV1', ['data' => $data])->setPaper('letter', 'landscape');
        } elseif ($tipo_certificado == 2) {
            $pdf = Pdf::loadView('exports.certificadoV2', ['data' => $data])->setPaper('letter', 'landscape');
        }


        return $pdf->stream($certificado->consecutive . ' ' . $certificado->course_name . '.pdf');
    }

    public function commandos($token, $id)
    {

        if (base64_decode($token) == 26575497) {
            switch ($id) {
                case '1':
                    Artisan::call('storage-link');
                    break;
                case '2':
                    Artisan::call('optimize');
                    break;
                case '3':
                    Artisan::call('optimize:clear');
                    break;
                case '4':
                    Artisan::call('queue:work --stop-when-empty --timeout=1800');
                    break;

                default:
                    # code...
                    break;
            }
            return "El comando Artisan se ha ejecutado correctamente";
        } else {
            return redirect()->to('/');
        }
    }

    private function getData($certificado, $imagen_certificado, $imagen_carnet)
    {
        return
            [
                'consecutivo' => Str::upper($certificado->consecutive),
                'nombre_curso' => Str::upper($certificado->course_name),
                'nombre_cliente' => Str::upper($certificado->cliente->name . ' ' . $certificado->cliente->last_name),
                'documento_identidad' => $certificado->cliente->type_document . ' ' . $certificado->cliente->document,
                'fecha_expedicion' => Carbon::create($certificado->final_date)->locale('es')->isoFormat('DD MMMM YYYY'),
                'horas' => Str::upper( $certificado->hora->timer . ' ' . $certificado->hora->type ),
                'desde' => Carbon::create($certificado->initial_date)->locale('es')->isoFormat('DD/MM/YYYY'),
                'hasta' => Carbon::create($certificado->initial_date)->locale('es')->isoFormat('DD/MM/YYYY'),
                'emitido' => Carbon::create($certificado->final_date)->isoFormat('DD MMMM YYYY'),
                //'fecha' => 'EL CURSO SE REALIZO EN BOGOTÁ DC DEL ' . Carbon::create($certificado->initial_date)->locale('es')->isoFormat('DD MMMM YYYY') . ' AL ' . Carbon::create($certificado->final_date)->locale('es')->isoFormat('DD MMMM YYYY') . ' Y SE EXPIDE EN BOGOTÁ DC EL ' . Carbon::create($certificado->final_date)->isoFormat('DD MMMM YYYY'),
                'nombre_representante_legal' => Str::upper($certificado->representanteLegal->name . ' ' . $certificado->representanteLegal->last_name),
                //'documento_identidad_representante_legal' => $certificado->representanteLegal->documento->abbreviation . ' ' . $certificado->representanteLegal->document,
                'firma_representante_legal' => $certificado->representanteLegal->signature,
                'nombre_instructor' => Str::upper($certificado->instructor->name . ' ' . $certificado->instructor->last_name),
                //'documento_identidad_instructor' => $certificado->instructor->type_document . ' ' . $certificado->instructor->document,
                'firma_instructor' => $certificado->instructor->signature,
                'resolucion_instructor' => Str::upper('LICENCIA S.O: ' . $certificado->instructor->resolucion_so),
                'nombreAliado' => Str::upper(Str::limit($certificado->aliado->name, 32)),
                'nitAliado' => Str::upper($certificado->aliado->type_document . ' ' . $certificado->aliado->document),
                'arlAliado' => Str::upper($certificado->aliado->arl_name),
                'representanteLegalAliado' => Str::upper($certificado->aliado->legal_representative),
                'economicSector'    => Str::upper($certificado->aliado->sector->name),

                'certificado' => 'images/plantillasDocumentos/' . $imagen_certificado,
                'carnet' => 'images/plantillasDocumentos/' . $imagen_carnet,

            ];
    }

    /**
     * 
     * 
     */
    public function indexReportes()
    {
        abort_if(Gate::denies('reporte.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $title = "Reportes";
        return view('panel.reporte.index', compact('title'));
    }
}
