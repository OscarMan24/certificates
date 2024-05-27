<?php

namespace App\Http\Livewire\Panel\Certificados;

use App\Exports\CertificadosExport;
use Exception;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Cursos;
use App\Models\Aliados;
use Livewire\Component;
use App\Models\Asesores;
use App\Models\Clientes;
use App\Models\Horarios;
use Illuminate\Support\Str;
use App\Models\Certificados;
use App\Models\Instructores;
use Livewire\WithPagination;
use App\Models\TiposDocumentos;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RepresentanteLegal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;
use Symfony\Component\HttpFoundation\Response;

class CertificadosLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado', 'crearCertificado'];
    public $search = '', $search_status = '', $search_curso = '', $search_instructor = '', $search_cliente = '', $search_aliado = '';
    protected $queryString = [
        'page' => ['except' => 1, 'as' => 'p'],
        'search' => ['except' => '', 'as' => 's'],
        'search_status' => ['except' => '', 'as' => 'status'],
        'search_curso' => ['except' => '', 'as' => 'curso'],
        'search_instructor' => ['except' => '', 'as' => 'instructor'],
        'search_cliente' => ['except' => '', 'as' => 'cliente'],
        'search_aliado' => ['except' => '', 'as' => 'aliado'],
    ];
    public $readytoload = false;

    public $tipoDocumentoCliente, $buscarClienteDocumento, $clienteEncontrado = false, $nombreCliente, $apellidoCliente, $correoCliente, $telefonoCliente;
    public $aliadoCertificado, $asesorCertificado, $fechaInicialCertificado, $fechaFinalCertificado;

    public $cursosId, $instructoresId, $horariosId, $clienteId;

    public $certificadoId = 0, $consecutivo = '', $nombreCurso = '';

    public $export_from, $export_to, $export_curso = 'Todos';

    private $saltarValidacion = false;

    public function render()
    {
        return view('livewire.panel.certificados.certificados-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('certificado.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');

        $this->validate([
            'tipoDocumentoCliente' => 'required',
            'buscarClienteDocumento' => 'required|numeric',
            'cursosId' => 'required|numeric',
            'instructoresId' => 'required|numeric',
            'horariosId' => 'required|numeric',
            'fechaInicialCertificado' => 'required|date',
            'fechaFinalCertificado' => 'required|date|after_or_equal:fechaInicialCertificado'
        ]);

        DB::beginTransaction();
        try {

            if ($this->clienteEncontrado) {
                $item = Clientes::where([
                    ['type_document', $this->tipoDocumentoCliente], ['document', $this->buscarClienteDocumento], ['status', 1], ['deleted', 0]
                ])->first();

                if ($item) {

                    $curso = Cursos::where([
                        ['id', $this->cursosId], ['deleted', 0], ['status', 1]
                    ])->first();

                    if ($this->saltarValidacion) {
                        $validarClienteCertificado = false;
                    } else {
                        $validarClienteCertificado = $this->validateClienteCertificado($item->id, $curso->id);
                    }

                    if ($validarClienteCertificado) {
                        $this->dispatchBrowserEvent('existenteCertificado');
                    } else {
                        $ultimoCertificado = Certificados::where([
                            ['curso_id', $this->cursosId],  ['deleted', 0]
                        ])->orderBy('id', 'desc')->first();

                        $horario = Horarios::where([
                            ['deleted', 0], ['id', $this->horariosId]
                        ])->first();

                        $cliente = Clientes::findorfail($this->clienteId);

                        if ($ultimoCertificado) {
                            $prevConsecutivo = Str::of($ultimoCertificado->consecutive)->afterLast('-')->toInteger();
                        } else {
                            $prevConsecutivo = 0;
                        }

                        //dd($ultimoCertificado, $prevConsecutivo, $this->configConsecutive($prevConsecutivo, $curso->consecutive));

                        $certificado = new Certificados();
                        $certificado->consecutive = $this->configConsecutive($prevConsecutivo, $curso->consecutive);
                        $certificado->user_id = Auth::user()->id;
                        $certificado->cliente_id = $this->clienteId;
                        $certificado->instructor_id = $this->instructoresId;
                        $certificado->aliado_id = $this->aliadoCertificado;
                        $certificado->horario_id = $horario->id;
                        $certificado->curso_id = $curso->id;
                        $certificado->course_name = $curso->name;
                        $certificado->representante_legal_id = $this->Representantee->id;
                        $certificado->hours = $horario->timer . ' ' . $horario->type;
                        $certificado->asesor_id = $cliente->asesor_id;
                        $certificado->initial_date = $this->fechaInicialCertificado;
                        $certificado->final_date = $this->fechaFinalCertificado;
                        $certificado->expiration_date = Carbon::create($this->fechaFinalCertificado)->addYear();
                        $certificado->save();

                        $this->dispatchBrowserEvent('sstoree');
                        $this->clean();
                        DB::commit();
                        $this->certificadoId = $certificado->id;
                        $this->nombreCurso = $certificado->course_name;
                        $this->consecutivo = $certificado->consecutive;
                    }
                } else {
                    $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error a la hora de buscar el cliente, vuelvelo a intentar')]);
                    DB::rollBack();
                }
            } else {
                DB::rollBack();
                $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error a la hora de buscar el cliente, vuelvelo a intentar')]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('certificado.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->certificadoId = $id;
        if ($this->Certificadoo) {
            $this->dispatchBrowserEvent('edit2');
            $this->tipoDocumentoCliente = $this->Certificadoo->cliente->type_document;
            $this->buscarClienteDocumento = $this->Certificadoo->cliente->document;
            $this->clienteEncontrado = true;
            $this->nombreCliente = $this->Certificadoo->cliente->name;
            $this->apellidoCliente = $this->Certificadoo->cliente->last_name;
            $this->telefonoCliente = $this->Certificadoo->cliente->phone;
            $this->asesorCertificado = $this->Certificadoo->cliente->asesor_id;
            $this->aliadoCertificado = $this->Certificadoo->aliado_id;
            $this->correoCliente = $this->Certificadoo->cliente->email;
            $this->horariosId = $this->Certificadoo->horario_id;
            $this->cursosId = $this->Certificadoo->curso_id;
            $this->instructoresId = $this->Certificadoo->instructor_id;
            $this->fechaInicialCertificado = $this->Certificadoo->initial_date;
            $this->fechaFinalCertificado = $this->Certificadoo->final_date;
            $this->clienteId = $this->Certificadoo->cliente_id;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('certificado.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'tipoDocumentoCliente' => 'required',
            'buscarClienteDocumento' => 'required|numeric',

            'cursosId' => 'required|numeric',
            'instructoresId' => 'required|numeric',
            'horariosId' => 'required|numeric',
            'fechaInicialCertificado' => 'required|date',
            'fechaFinalCertificado' => 'required|date|after_or_equal:fechaInicialCertificado'
        ]);

        try {
            $horario = Horarios::where([
                ['deleted', 0], ['id', $this->horariosId]
            ])->first();

            $cliente = Clientes::findorfail($this->clienteId);

            $this->Certificadoo->cliente_id = $this->clienteId;
            $this->Certificadoo->instructor_id = $this->instructoresId;
            $this->Certificadoo->aliado_id = $this->aliadoCertificado;
            $this->Certificadoo->horario_id = $horario->id;
            $this->Certificadoo->hours = $horario->timer . ' ' . $horario->type;
            $this->Certificadoo->asesor_id = $cliente->asesor_id;

            $this->Certificadoo->initial_date = $this->fechaInicialCertificado;
            $this->Certificadoo->final_date = $this->fechaFinalCertificado;
            $this->Certificadoo->expiration_date = Carbon::create($this->fechaFinalCertificado)->addYear();
            $this->Certificadoo->edit_user_id = Auth::user()->id;
            $this->Certificadoo->update();

            $id = $this->certificadoId;

            $this->dispatchBrowserEvent('actualiizar');
            $this->dispatchBrowserEvent('openShare');
            $this->clean();
            DB::commit();
            $this->certificadoId = $id;
            $this->nombreCurso = $this->Certificadoo->course_name;
            $this->consecutivo = $this->Certificadoo->consecutive;
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function share($id)
    {
        abort_if(Gate::denies('certificado.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->certificadoId = $id;
        if ($this->Certificadoo) {
            $this->dispatchBrowserEvent('openShare');
            $this->consecutivo = $this->Certificadoo->consecutive;
            $this->nombreCurso = $this->Certificadoo->course_name;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function changestatus($id)
    {
        abort_if(Gate::denies('certificado.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->certificadoId = $id;
        if ($this->Certificadoo != '') {
            if ($this->Certificadoo->status == 1) {
                $this->Certificadoo->Status = 0;
            } else {
                $this->Certificadoo->status = 1;
            }
            $this->Certificadoo->edit_user_id = Auth::user()->id;
            $this->Certificadoo->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('certificado.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->certificadoId = $id;
        if (!empty($this->Certificadoo)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('certificado.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Certificadoo)) {
            $this->Certificadoo->edit_user_id = Auth::user()->id;
            $this->Certificadoo->deleted = 1;
            $this->Certificadoo->update();
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function descargarCertificado()
    {
        return redirect()->route('exportar.pdfs', base64_encode($this->certificadoId));
    }

    private function configConsecutive(int $prevConsecutivo, string $consecutive)
    {
        $consecutivo_new = (int)$prevConsecutivo + 1;
        $cadena_conse = Str::padLeft($consecutivo_new, 4, '0');
        return $consecutive . '-' . $cadena_conse;
    }

    public function buscarCliente()
    {
        $this->validate([
            'tipoDocumentoCliente' => 'required',
            'buscarClienteDocumento' => 'required|numeric'
        ]);

        $this->reset(['clienteId', 'clienteEncontrado', 'nombreCliente', 'apellidoCliente', 'aliadoCertificado', 'asesorCertificado', 'correoCliente', 'telefonoCliente']);

        $item = Clientes::where([
            ['type_document', $this->tipoDocumentoCliente], ['document', $this->buscarClienteDocumento], ['status', 1], ['deleted', 0]
        ])->first();

        if ($item) {
            $this->clienteEncontrado = true;
            $this->nombreCliente = $item->name;
            $this->apellidoCliente = $item->last_name;
            $this->correoCliente = $item->email;
            $this->telefonoCliente = $item->phone;
            $this->aliadoCertificado = $item->aliado_id;
            $this->asesorCertificado = $item->asesor_id;
            $this->clienteId = $item->id;

            $this->dispatchBrowserEvent('searched');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => '¡No se ha encontrado un resultado!']);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload', 'search_status', 'search_curso', 'search_instructor', 'search_cliente', 'search_aliado']);
    }

    public function limpiarfiltros()
    {
        $this->resetExcept();
    }

    public function export()
    {
        $this->validate([
            'export_to' => 'nullable|date||after_or_equal:export_from',
            'export_from' => 'nullable|date',
            'export_curso' => 'required'
        ]);

        try {
            $this->dispatchBrowserEvent('cerrarExport');
            return Excel::download(new CertificadosExport($this->export_from, $this->export_to, $this->export_curso), 'certificados.xlsx');
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    private function validateClienteCertificado($clienteId, $cursoId)
    {
        return Certificados::where([
            ['cliente_id', $clienteId], ['curso_id', $cursoId], ['deleted', 0], ['active', 1], ['status', 1]
        ])->exists();
    }

    public function crearCertificado()
    {
        $this->saltarValidacion = true;
        $this->store();
    }

    public function getCertificadosProperty()
    {
        $clientesIds = [];
        if (!empty($this->Clientes)) {
            $clientesIds = $this->Clientes->pluck('id')->toArray();
        }
        return Certificados::where([
            ['consecutive', 'LIKE', '%' . $this->search . '%'],
            ['instructor_id', 'LIKE', '%' . $this->search_instructor],
            ['aliado_id', 'LIKE', '%' . $this->search_aliado],
            ['curso_id', 'LIKE', '%' . $this->search_curso],
            ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->when($clientesIds, function ($query, $clientesIds) {
            return $query->whereIn('cliente_id', $clientesIds);
        })->orderBy('id', 'desc')
            ->paginate(12);
    }

    public function getCertificadooProperty()
    {
        return Certificados::where([
            ['deleted', 0], ['id', $this->certificadoId]
        ])->first();
    }

    public function getCursosProperty()
    {
        return Cursos::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('consecutive')->get();
    }

    public function getAliadosProperty()
    {
        return Aliados::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('name', 'asc')->get();
    }

    public function getAsesoresProperty()
    {
        return Asesores::where([
            ['status', 1], ['deleted', 0]
        ])->get();
    }

    public function getClientesProperty()
    {
        if ($this->search_cliente != '') {
            return DB::table('clientes')
                ->select(['id', 'deleted', 'status'])
                ->where(function ($query) {
                    $query->whereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $this->search_cliente . '%'])
                        ->orWhereRaw("CONCAT(type_document, ' ', document) LIKE ?", ['%' . $this->search_cliente . '%']);
                })
                ->where('deleted', 0)
                ->where('status', 1)
                ->get();
        }

        return [];
    }

    public function getRepresentanteeProperty()
    {
        return RepresentanteLegal::where([
            ['deleted', 0], ['status', 1], ['default', 1]
        ])->first();
    }

    public function getInstructoresProperty()
    {
        return Instructores::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('name', 'asc')->get();
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['status', 1], ['deleted', 0]
        ])->get();
    }

    public function getHorariosProperty()
    {
        return Horarios::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('timer')->get();
    }
}
