<?php

namespace App\Http\Livewire\Panel\Asesores;

use Exception;
use Livewire\Component;
use App\Models\Asesores;
use Livewire\WithPagination;
use App\Exports\AsesorExport;
use App\Models\TiposDocumentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class AsesoresLivewire extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado'];
    public $search = '', $search_status = '';
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'search_status' => ['except' => '', 'as' => 'status'],
        'page' => ['except' => 1, 'as' => 'p'],
    ];
    public $readytoload = false;

    public $typeDocument, $document, $name, $lastName, $email, $phone;
    public $asesorId;
    public $export_to, $export_from;

    public function render()
    {
        return view('livewire.panel.asesores.asesores-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {

        abort_if(Gate::denies('asesor.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'lastName' => 'required|max:255|min:2',
            'email' => 'nullable|email',
            'phone' => 'nullable|phone:CO,AUTO'
        ]);
        DB::beginTransaction();
        try {
            $verificarDocumento = Asesores::where([
                ['type_document', $this->typeDocument], ['document', $this->document]
            ])->exists();

            if (!$verificarDocumento) {
                $item = new Asesores();
                $item->type_document = $this->typeDocument;
                $item->document = $this->document;
                $item->name = $this->name;
                $item->last_name = $this->lastName;
                $item->email = $this->email;
                $item->phone = $this->phone;
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
        abort_if(Gate::denies('asesor.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->asesorId = $id;
        if (!empty($this->Asesor)) {
            $this->dispatchBrowserEvent('edit2');
            $this->typeDocument = $this->Asesor->type_document;
            $this->document = $this->Asesor->document;
            $this->name = $this->Asesor->name;
            $this->lastName = $this->Asesor->last_name;
            $this->email = $this->Asesor->email;
            $this->phone = $this->Asesor->phone;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('asesor.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'lastName' => 'required|max:255|min:2',
            'email' => 'nullable|email',
            'phone' => 'nullable|phone:CO,AUTO'
        ]);
        DB::beginTransaction();
        try {

            $verificarDocumento = Asesores::where([
                ['type_document', $this->typeDocument], ['document', $this->document], ['id', '!=', $this->asesorId]
            ])->exists();

            if (!$verificarDocumento) {
                $this->Asesor->type_document = $this->typeDocument;
                $this->Asesor->document = $this->document;
                $this->Asesor->name = $this->name;
                $this->Asesor->last_name = $this->lastName;
                $this->Asesor->email = $this->email;
                $this->Asesor->phone = $this->phone;
                $this->Asesor->edit_user_id = Auth::user()->id;
                $this->Asesor->update();

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
        abort_if(Gate::denies('asesor.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->asesorId = $id;
        if ($this->Asesor != '') {
            if ($this->Asesor->status == 1) {
                $this->Asesor->Status = 0;
            } else {
                $this->Asesor->status = 1;
            }
            $this->Asesor->edit_user_id = Auth::user()->id;
            $this->Asesor->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('asesor.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->asesorId = $id;
        if (!empty($this->Asesor)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('asesor.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Asesor)) {
            $this->Asesor->deleted = 1;
            $this->Asesor->edit_user_id = Auth::user()->id;
            $this->Asesor->update();
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
            return Excel::download(new AsesorExport($this->export_from, $this->export_to), 'asesor.xlsx');
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function getAsesoresProperty()
    {
        return Asesores::where([
            ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['document', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['email', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['phone', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orderBy('id', 'desc')->paginate(12);
    }

    public function getAsesorProperty()
    {
        return Asesores::where([
            ['id', $this->asesorId], ['deleted', 0]
        ])->first();
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['status', 1], ['deleted', 0]
        ])->get();
    }
}
