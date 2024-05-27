<?php

namespace App\Http\Livewire\Panel\Instructores;

use Exception;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Instructores;
use Livewire\WithPagination;
use App\Exports\AsesorExport;
use Livewire\WithFileUploads;
use App\Models\TiposDocumentos;
use Illuminate\Support\Facades\DB;
use App\Exports\InstructoresExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class InstructoresLivewire extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado', 'downloadReportes'];
    public $search = '', $search_status = '';
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'search_status' => ['except' => '', 'as' => 'status'],
        'page' => ['except' => 1, 'as' => 'p'],
    ];
    public $readytoload = false;

    public $typeDocument, $document, $name, $lastName, $email, $phone, $resolucionSo, $signature, $currentSignature, $observations;
    public $instructorId;

    public function render()
    {
        return view('livewire.panel.instructores.instructores-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('instructor.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'lastName' => 'required|max:255|min:2',
            'email' => 'nullable|email',
            'phone' => 'nullable|phone:CO,AUTO',
            'resolucionSo' => 'required',
            'observations' => 'nullable|max:550',
            'signature' => 'required|image|max:5500|mimes:png|dimensions:width=350,height=200'
        ]);
        DB::beginTransaction();
        try {

            $verificarDocumento = Instructores::where([
                ['type_document', $this->typeDocument], ['document', $this->document]
            ])->exists();

            if (!$verificarDocumento) {
                $item = new Instructores();
                $item->type_document = $this->typeDocument;
                $item->document = $this->document;
                $item->name = $this->name;
                $item->last_name = $this->lastName;
                $item->email = $this->email;
                $item->phone = $this->phone;
                $item->resolucion_so = $this->resolucionSo;
                $item->observations = $this->observations;
                if ($this->signature) {
                    $imgname2 = Str::slug(Str::limit($this->name, 10, '')) . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->signature->extension();
                    $this->signature->storeAs('instructores', $imageame2, 'public');
                    $item->signature = $imageame2;
                }
                $item->created_user_id = Auth::user()->id;
                $item->save();

                $this->dispatchBrowserEvent('sstoree');
                $this->clean();
                DB::commit();
            } else {
                $this->dispatchBrowserEvent('errores', ['error' => __('Ya existe un registro con este documento')]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('instructor.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->instructorId = $id;
        if (!empty($this->Instructor)) {
            $this->dispatchBrowserEvent('edit2');
            $this->typeDocument = $this->Instructor->type_document;
            $this->document = $this->Instructor->document;
            $this->name = $this->Instructor->name;
            $this->lastName = $this->Instructor->last_name;
            $this->email = $this->Instructor->email;
            $this->phone = $this->Instructor->phone;
            $this->resolucionSo = $this->Instructor->resolucion_so;
            $this->observations = $this->Instructor->observations;
            $this->currentSignature = $this->Instructor->signature;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('instructor.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'lastName' => 'required|max:255|min:2',
            'email' => 'nullable|email',
            'phone' => 'nullable|phone:CO,AUTO',
            'resolucionSo' => 'required',
            'observations' => 'nullable|max:550',
            'signature' => 'nullable|image|max:5500|mimes:png|dimensions:width=350,height=200'
        ]);
        DB::beginTransaction();
        try {

            $verificarDocumento = Instructores::where([
                ['type_document', $this->typeDocument], ['document', $this->document], ['id', '!=', $this->instructorId]
            ])->exists();

            if (!$verificarDocumento) {
                $this->Instructor->type_document = $this->typeDocument;
                $this->Instructor->document = $this->document;
                $this->Instructor->name = $this->name;
                $this->Instructor->last_name = $this->lastName;
                $this->Instructor->email = $this->email;
                $this->Instructor->phone = $this->phone;
                $this->Instructor->resolucion_so = $this->resolucionSo;
                $this->Instructor->observations = $this->observations;
                if ($this->signature) {
                    $imgname2 = Str::slug(Str::limit($this->name, 10, '')) . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->signature->extension();
                    $this->signature->storeAs('instructores', $imageame2, 'public');
                    $this->Instructor->signature = $imageame2;
                }
                $this->Instructor->edit_user_id = Auth::user()->id;
                $this->Instructor->update();

                $this->dispatchBrowserEvent('actualiizar');
                $this->clean();
                DB::commit();
            } else {
                $this->dispatchBrowserEvent('errores', ['error' => __('Ya existe un registro con este documento')]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function changestatus($id)
    {
        abort_if(Gate::denies('instructor.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->instructorId = $id;
        if ($this->Instructor != '') {
            if ($this->Instructor->status == 1) {
                $this->Instructor->Status = 0;
            } else {
                $this->Instructor->status = 1;
            }
            $this->Instructor->edit_user_id = Auth::user()->id;
            $this->Instructor->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('instructor.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->instructorId = $id;
        if (!empty($this->Instructor)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('instructor.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Instructor)) {
            $this->Instructor->deleted = 1;
            $this->Instructor->edit_user_id = Auth::user()->id;
            $this->Instructor->update();
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function export()
    {
        $this->validate([
            'export_to' => 'nullable|date||after:export_from',
            'export_from' => 'nullable|date'
        ]);

        try {
            $this->dispatchBrowserEvent('cerrarExport');
            return Excel::download(new InstructoresExport($this->export_from, $this->export_to), 'instructor.xlsx');
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function downloadReportes($tipo)
    {
        if ($tipo != '' && $tipo != null && $tipo == 1 || $tipo == 2 || $tipo == 3) {

            try {
                return Excel::download(new InstructoresExport($tipo), 'instructores.xlsx');
            } catch (Exception $e) {
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'Ha ocurrido un error, contacta al administrador']);
        }
    }

    public function getInstructoresProperty()
    {
        return Instructores::where([
            ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['document', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['email', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['phone', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['resolucion_so', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->paginate(12);
    }

    public function getInstructorProperty()
    {
        return Instructores::where([
            ['id', $this->instructorId], ['deleted', 0]
        ])->first();
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['status', 1], ['deleted', 0]
        ])->get();
    }
}
