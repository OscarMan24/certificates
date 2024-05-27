<?php

namespace App\Http\Livewire\Panel\DocumentosIdentidad;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TiposDocumentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DocumentosIdentidadLivewire extends Component
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

    public $name, $abbreviation, $document_id;


    public function render()
    {
        return view('livewire.panel.documentos-identidad.documentos-identidad-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('tipo.documento.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'name' => 'required|unique:tipos_documentos,name_document|max:255|min:2',
            'abbreviation' => 'required|unique:tipos_documentos,abbreviation|max:10|min:2',
        ]);
        DB::beginTransaction();
        try {
            $item = new TiposDocumentos();
            $item->abbreviation = $this->abbreviation;
            $item->name_document = $this->name;
            $item->status = 1;
            $item->deleted = 0;
            $item->created_user_id = Auth::user()->id;
            $item->save();

            $this->dispatchBrowserEvent('sstoree');
            $this->clean();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('tipo.documento.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->document_id = $id;
        if (!empty($this->Documentoo)) {
            $this->dispatchBrowserEvent('edit2');
            $this->abbreviation = $this->Documentoo->abbreviation;
            $this->name = $this->Documentoo->name_document;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('tipo.documento.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'name' => 'required|unique:tipos_documentos,name_document,' .  $this->document_id . ',id|max:255|min:2',
            'abbreviation' => 'required|unique:tipos_documentos,abbreviation,' .  $this->document_id . ',id|max:10|min:2',
        ]);
        DB::beginTransaction();
        try {
            $this->Documentoo->abbreviation = $this->abbreviation;
            $this->Documentoo->name_document = $this->name;
            $this->Documentoo->edit_user_id = Auth::user()->id;
            $this->Documentoo->update();

            $this->dispatchBrowserEvent('actualiizar');
            $this->clean();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('tipo.documento.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->document_id = $id;

        if (!empty($this->Documentoo)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('tipo.documento.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Documentoo)) {
            $this->Documentoo->edit_user_id = Auth::user()->id;
            $this->Documentoo->deleted = 1;
            $this->Documentoo->update();
            $this->clean();
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function changestatus($id)
    {
        abort_if(Gate::denies('tipo.documento.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->document_id = $id;
        if ($this->Documentoo != '') {
            if ($this->Documentoo->status == 1) {
                $this->Documentoo->Status = 0;
            } else {
                $this->Documentoo->status = 1;
            }
            $this->Documentoo->edit_user_id = Auth::user()->id;
            $this->Documentoo->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['name_document', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['abbreviation', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->paginate(12);
    }

    public function getDocumentooProperty()
    {
        return TiposDocumentos::where('id', $this->document_id)->first();
    }
}
