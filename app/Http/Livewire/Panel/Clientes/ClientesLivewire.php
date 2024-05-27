<?php

namespace App\Http\Livewire\Panel\Clientes;

use Exception;
use Carbon\Carbon;
use App\Models\Aliados;
use Livewire\Component;
use App\Models\Asesores;
use App\Models\Clientes;
use Livewire\WithPagination;
use App\Exports\ClienteExport;
use App\Models\TiposDocumentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ClientesLivewire extends Component
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

    public $typeDocument, $document, $name, $lastName, $email, $phone, $birthdate;
    public $asesorId, $aliadoId, $clienteId;
    public $export_to, $export_from;
    public $gender, $countryOfBirth, $educationLevel, $workArea, $actualCharge, $secondName, $secondLastName;

    public function render()
    {
        return view('livewire.panel.clientes.clientes-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function create()
    {
        $this->dispatchBrowserEvent('create');
        $this->clean();
    }

    public function store()
    {
        abort_if(Gate::denies('cliente.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'secondName' => 'nullable|max:120',            
            'lastName' => 'required|max:255|min:2',
            'secondLastName' => 'nullable|max:120',
            'email' => 'nullable|email',
            'phone' => 'nullable|phone:CO,AUTO',
            'asesorId' => 'required|numeric',
            'aliadoId' => 'required|numeric',
            'birthdate' => 'required|date|before:today',
            'gender'            => 'required',
            'countryOfBirth'    => 'nullable',
            'educationLevel'    => 'nullable',
            'workArea'          => 'nullable',
            'actualCharge'      => 'nullable'
        ]);
        DB::beginTransaction();
        try {

            $verificarDocumento = Clientes::where([
                ['type_document', $this->typeDocument], ['document', $this->document]
            ])->exists();

            if (!$verificarDocumento) {
                $item = new Clientes();
                $item->type_document = $this->typeDocument;
                $item->document = $this->document;
                $item->name = $this->name;
                $item->second_name = $this->secondName;
                $item->last_name = $this->lastName;
                $item->second_last_name = $this->secondLastName;
                $item->email = $this->email;
                $item->phone = $this->phone;
                $item->aliado_id = $this->aliadoId;
                $item->asesor_id = $this->asesorId;
                $item->birthdate = $this->birthdate;
                $item->gender    = $this->gender;
                $item->country_of_birth = $this->countryOfBirth;
                $item->education_level = $this->educationLevel;
                $item->work_area = $this->workArea;
                $item->actual_charge = $this->actualCharge;
                $item->created_user_id = Auth::user()->id;
                $item->save();

                $this->dispatchBrowserEvent('sstoree');
                $this->clean();
                DB::commit();
                $this->subirDocumentos($item->id);
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
        abort_if(Gate::denies('cliente.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->clienteId = $id;
        if (!empty($this->Clientee)) {
            $this->dispatchBrowserEvent('edit2');
            $this->typeDocument = $this->Clientee->type_document;
            $this->document = $this->Clientee->document;
            $this->name = $this->Clientee->name;
            $this->secondName = $this->Clientee->second_name;
            $this->lastName = $this->Clientee->last_name;
            $this->secondLastName = $this->Clientee->second_last_name;
            $this->email = $this->Clientee->email;
            $this->phone = $this->Clientee->phone;
            $this->birthdate = $this->Clientee->birthdate;
            $this->gender = $this->Clientee->gender;
            $this->countryOfBirth = $this->Clientee->country_of_birth;
            $this->educationLevel = $this->Clientee->education_level;
            $this->workArea = $this->Clientee->work_area;
            $this->actualCharge = $this->Clientee->actual_charge;
            $this->aliadoId = $this->Clientee->aliado_id;
            $this->asesorId = $this->Clientee->asesor_id;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('cliente.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|max:255|min:2',
            'secondName' => 'nullable|max:120',
            'lastName' => 'required|max:255|min:2',
            'secondLastName' => 'nullable|max:120',
            'email' => 'nullable|email',
            'phone' => 'nullable|phone:CO,AUTO',
            'asesorId' => 'required|numeric',
            'aliadoId' => 'required|numeric',
            'birthdate' => 'required|date|before:today',
            'gender'            => 'required',
            'countryOfBirth'    => 'nullable',
            'educationLevel'    => 'nullable',
            'workArea'          => 'nullable',
            'actualCharge'      => 'nullable'
        ]);
        DB::beginTransaction();
        try {

            $verificarDocumento = Asesores::where([
                ['type_document', $this->typeDocument], ['document', $this->document], ['id', '!=', $this->clienteId]
            ])->exists();

            if (!$verificarDocumento) {
                $this->Clientee->type_document = $this->typeDocument;
                $this->Clientee->document = $this->document;
                $this->Clientee->name = $this->name;
                $this->Clientee->second_name = $this->secondName;
                $this->Clientee->last_name = $this->lastName;
                $this->Clientee->second_last_name = $this->secondLastName;
                $this->Clientee->email = $this->email;
                $this->Clientee->phone = $this->phone;
                $this->Clientee->asesor_id = $this->asesorId;
                $this->Clientee->aliado_id = $this->aliadoId;
                $this->Clientee->birthdate = $this->birthdate;
                $this->Clientee->gender    = $this->gender;
                $this->Clientee->country_of_birth = $this->countryOfBirth;
                $this->Clientee->education_level = $this->educationLevel;
                $this->Clientee->work_area = $this->workArea;
                $this->Clientee->actual_charge = $this->actualCharge;
                $this->Clientee->edit_user_id = Auth::user()->id;
                $this->Clientee->update();

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
        abort_if(Gate::denies('cliente.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clienteId = $id;
        if ($this->Clientee != '') {
            if ($this->Clientee->status == 1) {
                $this->Clientee->Status = 0;
            } else {
                $this->Clientee->status = 1;
            }
            $this->Clientee->edit_user_id = Auth::user()->id;
            $this->Clientee->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('cliente.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->clienteId = $id;
        if (!empty($this->Clientee)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('cliente.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Clientee)) {
            $this->Clientee->deleted = 1;
            $this->Clientee->edit_user_id = Auth::user()->id;
            $this->Clientee->update();
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function export()
    {
        $this->validate([
            'export_to' => 'nullable|date||after:export_from',
            'export_from' => 'nullable|date'
        ]);

        try {
            $this->dispatchBrowserEvent('cerrarExport');
            return Excel::download(new ClienteExport($this->export_from, $this->export_to), 'clientes.xlsx');
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function subirDocumentos($id)
    {
        $this->clean();
        $this->clienteId = $id;
        if (!empty($this->Clientee)) {
            $data = [
                'id' => $id,
                'name' => $this->Clientee->name . ' ' . $this->Clientee->last_name
            ];
            $this->emit('getClienteId', $data);
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function getClientesProperty()
    {
        return Clientes::where([
            ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['last_name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['document', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['email', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['phone', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orderBy('id', 'desc')->paginate(12);
    }

    public function getClienteeProperty()
    {
        return Clientes::where([
            ['id', $this->clienteId], ['deleted', 0]
        ])->first();
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['status', 1], ['deleted', 0]
        ])->get();
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
}
