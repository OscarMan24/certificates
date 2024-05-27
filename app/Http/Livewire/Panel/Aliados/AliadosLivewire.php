<?php

namespace App\Http\Livewire\Panel\Aliados;

use Exception;
use App\Models\Aliados;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\AliadoExport;
use App\Models\Sectores;
use App\Models\TiposDocumentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AliadosLivewire extends Component
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

    public $typeDocument, $document, $name, $legal_representative, $arl_name, $email, $address, $phone, $celular;
    public $economicSectorId, $typeDocumentLegalRepresentative, $documentLegalRepresentative;
    public $aliado_id;
    public $export_to, $export_from;

    public function render()
    {
        return view('livewire.panel.aliados.aliados-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('aliado.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'legal_representative' => 'required|min:2|max:120',
            'arl_name' => 'required|min:2|max:120',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'phone' => 'nullable|phone:CO,AUTO',
            'celular' => 'nullable|phone:CO,AUTO',

            'economicSectorId' => 'required',
            'typeDocumentLegalRepresentative' => 'required',
            'documentLegalRepresentative' => 'required'
        ], 
        [
            'economicSectorId.required' => 'El sector economico es requerido',
            'typeDocumentLegalRepresentative.required' => 'El tipo de documento del representante legal es requerido',
            'documentLegalRepresentative.required' => 'El documento del representante legal es requerido'
        ]);

        DB::beginTransaction();
        try {

            $verificarDocumento = Aliados::where([
                ['type_document', $this->typeDocument], ['document', $this->document], ['deleted', 0]
            ])->exists();

            if($verificarDocumento){
                $this->dispatchBrowserEvent('errores', ['error' => __('Ya existe un registro con este documento')]);
                return 0;
            }

            $verificarNombre = Aliados::where([
                ['name', $this->name ], ['deleted', 0]
            ])->exists();

            if($verificarNombre){
                $this->dispatchBrowserEvent('errores', ['error' => __('Ya existe un registro con este nombre')]);
                return 0;
            }

            $item = new Aliados();
                $item->type_document = $this->typeDocument;
                $item->document = $this->document;
                $item->name = $this->name;
                $item->legal_representative = $this->legal_representative;
                $item->arl_name = $this->arl_name;
                $item->email = $this->email;
                $item->address = $this->address;
                $item->phone = $this->phone;
                $item->celular = $this->celular;
                $item->created_user_id = Auth::user()->id;

                $item->type_document_legal_representative = $this->typeDocumentLegalRepresentative;
                $item->document_legal_representative = $this->documentLegalRepresentative;
                $item->economic_sector = $this->economicSectorId;

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
        abort_if(Gate::denies('aliado.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->aliado_id = $id;
        if (!empty($this->Aliadoo)) {
            $this->dispatchBrowserEvent('edit2');
            $this->typeDocument = $this->Aliadoo->type_document;
            $this->document = $this->Aliadoo->document;
            $this->name = $this->Aliadoo->name;
            $this->legal_representative = $this->Aliadoo->legal_representative;
            $this->arl_name = $this->Aliadoo->arl_name;
            $this->email = $this->Aliadoo->email;
            $this->address = $this->Aliadoo->address;
            $this->phone = $this->Aliadoo->phone;
            $this->celular = $this->Aliadoo->celular;
            $this->typeDocumentLegalRepresentative = $this->Aliadoo->type_document_legal_representative;
            $this->documentLegalRepresentative = $this->Aliadoo->document_legal_representative;
            $this->economicSectorId = $this->Aliadoo->economic_sector;

        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('aliado.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'legal_representative' => 'required|min:2|max:120',
            'arl_name' => 'required|min:2|max:120',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'phone' => 'nullable|phone:CO,AUTO',
            'celular' => 'nullable|phone:CO,AUTO',
            'economicSectorId' => 'required',
            'typeDocumentLegalRepresentative' => 'required',
            'documentLegalRepresentative' => 'required'
        ],
        [
            'economicSectorId.required' => 'El sector economico es requerido',
            'typeDocumentLegalRepresentative.required' => 'El tipo de documento del representante legal es requerido',
            'documentLegalRepresentative.required' => 'El documento del representante legal es requerido'
        ]);
        DB::beginTransaction();
        try {

            $verificarDocumento = Aliados::where([
                ['type_document', $this->typeDocument], ['document', $this->document],  ['id', '!=', $this->aliado_id], ['deleted', 0]
            ])->exists();

            if($verificarDocumento){
                $this->dispatchBrowserEvent('errores', ['error' => __('Ya existe un registro diferente con este documento')]);
                return 0;
            }

            $verificarNombre = Aliados::where([
                ['name', $this->name ],  ['id', '!=', $this->aliado_id], ['deleted', 0]
            ])->exists();

            if($verificarNombre){
                $this->dispatchBrowserEvent('errores', ['error' => __('Ya existe un registro diferente con este nombre')]);
                return 0;
            }
           
            $this->Aliadoo->type_document = $this->typeDocument;
            $this->Aliadoo->document = $this->document;
            $this->Aliadoo->name = $this->name;
            $this->Aliadoo->legal_representative = $this->legal_representative;
            $this->Aliadoo->arl_name = $this->arl_name;
            $this->Aliadoo->email = $this->email;
            $this->Aliadoo->address = $this->address;
            $this->Aliadoo->phone = $this->phone;
            $this->Aliadoo->celular = $this->celular;
            $this->Aliadoo->edit_user_id = Auth::user()->id;
            $this->Aliadoo->type_document_legal_representative = $this->typeDocumentLegalRepresentative;
            $this->Aliadoo->document_legal_representative = $this->documentLegalRepresentative;
            $this->Aliadoo->economic_sector = $this->economicSectorId;
            $this->Aliadoo->update();

            $this->dispatchBrowserEvent('actualiizar');
            $this->clean();
            DB::commit();
           
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function changestatus($id)
    {
        abort_if(Gate::denies('aliado.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->aliado_id = $id;
        if ($this->Aliadoo != '') {
            if ($this->Aliadoo->status == 1) {
                $this->Aliadoo->Status = 0;
            } else {
                $this->Aliadoo->status = 1;
            }
            $this->Aliadoo->edit_user_id = Auth::user()->id;
            $this->Aliadoo->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('aliado.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->aliado_id = $id;
        if (!empty($this->Aliadoo)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('aliado.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Aliadoo)) {
            $this->Aliadoo->deleted = 1;
            $this->Aliadoo->edit_user_id = Auth::user()->id;
            $this->Aliadoo->update();
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
            return Excel::download(new AliadoExport($this->export_from, $this->export_to), 'aliado.xlsx');
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function getAliadosProperty()
    {
        return Aliados::where([
            ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['document', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['email', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['legal_representative', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['phone', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orderBy('id', 'desc')->paginate(12);
    }

    public function getAliadooProperty()
    {
        return Aliados::where('id', $this->aliado_id)->first();
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('name_document')->get();
    }

    public function getSectoresProperty()
    {
        return Sectores::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('name')->get();
    }
}
