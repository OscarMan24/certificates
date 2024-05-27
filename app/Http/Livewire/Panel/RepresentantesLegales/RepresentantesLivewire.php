<?php

namespace App\Http\Livewire\Panel\RepresentantesLegales;

use Exception;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\TiposDocumentos;
use App\Models\RepresentanteLegal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RepresentantesLivewire extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado', 'changeDefault'];
    public $search = '', $search_status = '';
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'search_status' => ['except' => '', 'as' => 'status'],
        'page' => ['except' => 1, 'as' => 'p'],
    ];
    public $readytoload = false;
    public $representanteId, $defectoId;

    public $name, $lastName, $typeDocument, $document, $email, $phone, $signature, $signatureCurrent, $default = false;

    public function render()
    {
        return view('livewire.panel.representantes-legales.representantes-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('representante.legal.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->resetValidation();
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|min:2|max:120',
            'lastName' => 'required|min:2|max:120',
            'email' => 'nullable|email|unique:representante_legals,email',
            'signature' => 'required|image|max:5500|mimes:png|dimensions:width=350,height=200',
            'phone' => 'nullable|phone:CO,AUTO'
        ]);

        DB::beginTransaction();
        try {

            $verificarDocumento = RepresentanteLegal::where([
                ['tipo_documento_id', $this->typeDocument], ['document', $this->document]
            ])->exists();

            if (!$verificarDocumento) {
                $item = new RepresentanteLegal();
                $item->tipo_documento_id = $this->typeDocument;
                $item->document = $this->document;
                $item->name = $this->name;
                $item->last_name = $this->lastName;
                $item->email = $this->email;
                $item->phone = $this->phone;
                if ($this->signature) {
                    $imgname2 = Str::slug(Str::limit($this->name, 6, '')) . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->signature->extension();
                    $this->signature->storeAs('representantes', $imageame2, 'public');
                    $item->signature = $imageame2;
                }
                $item->default = 0;
                $item->save();

                DB::commit();
                $this->dispatchBrowserEvent('sstoree');
                $this->clean();
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
        abort_if(Gate::denies('representante.legal.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->representanteId = $id;
        if (!empty($this->Representantee)) {
            $this->dispatchBrowserEvent('edit2');
            $this->name = $this->Representantee->name;
            $this->lastName = $this->Representantee->last_name;
            $this->typeDocument = $this->Representantee->tipo_documento_id;
            $this->document = $this->Representantee->document;
            $this->phone = $this->Representantee->phone;
            $this->email = $this->Representantee->email;
            $this->signatureCurrent = $this->Representantee->signature;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('representante.legal.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->resetValidation();
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|min:2|max:120',
            'lastName' => 'required|min:2|max:120',
            'email' => 'nullable|email|unique:representante_legals,email,' . $this->representanteId . ',id',
            'signature' => 'nullable|image|max:5500|mimes:png|dimensions:width=350,height=200',
            'phone' => 'nullable|phone:CO,AUTO'
        ]);
        DB::beginTransaction();
        try {

            $verificarDocumento = RepresentanteLegal::where([
                ['tipo_documento_id', $this->typeDocument], ['document', $this->document], ['id', '!=', $this->representanteId]
            ])->exists();

            if (!$verificarDocumento) {
                $this->Representantee->name = $this->name;
                $this->Representantee->last_name = $this->lastName;
                $this->Representantee->tipo_documento_id = $this->typeDocument;
                $this->Representantee->document = $this->document;
                $this->Representantee->email = $this->email;
                $this->Representantee->phone = $this->phone;
                if ($this->signature) {
                    $imgname2 = Str::slug(Str::limit($this->name, 6, '')) . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->signature->extension();
                    $this->signature->storeAs('representantes', $imageame2, 'public');
                    $this->Representantee->signature = $imageame2;
                }

                $this->Representantee->update();
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
        abort_if(Gate::denies('representante.legal.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->representanteId = $id;
        if ($this->Representantee != '') {

            if ($this->Representantee->default == 1) {
                $this->dispatchBrowserEvent('errores', ['error' => 'El representante que ha seleccionado se encuentra por defecto, cambie el representante por defecto antes']);
            } else {
                if ($this->Representantee->status == 1) {
                    $this->Representantee->Status = 0;
                } else {
                    $this->Representantee->status = 1;
                }
                $this->Representantee->update();
                $this->dispatchBrowserEvent('statuschanged');
            }
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('representante.legal.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->representanteId = $id;
        if (!empty($this->Representantee)) {
            if ($this->Representantee->default == 1) {
                $this->dispatchBrowserEvent('errores', ['error' => 'El representante que ha seleccionado se encuentra por defecto, cambie el representante por defecto antes']);
            } else {
                $this->dispatchBrowserEvent('borrar');
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('representante.legal.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Representantee)) {
            if ($this->Representantee->default == 1) {
                $this->dispatchBrowserEvent('errores', ['error' => 'El representante que ha seleccionado se encuentra por defecto, cambie el representante por defecto antes']);
            } else {
                $this->Representantee->deleted = 1;
                $this->Representantee->update();
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
        $this->resetValidation();
    }

    public function default($id)
    {
        abort_if(Gate::denies('representante.legal.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->defectoId = $id;
        $item = RepresentanteLegal::where([
            ['default', 1], ['deleted', 0]
        ])->first();

        $item2 = RepresentanteLegal::where([
            ['id', $id], ['deleted', 0]
        ])->first();

        if ($item) {
            if ($item2->status == 1) {
                $this->dispatchBrowserEvent('changeDefaul', ['name' => '"' . $item->name . ' ' . $item->last_name . '"']);
            } else {
                $this->dispatchBrowserEvent('errores', ['error' => 'El representante que ha seleccionado se encuentra actualmente desactivado, cambie el estado antes']);
            }
        } else {
            $this->changeDefault();
        }
    }

    public function changeDefault()
    {
        abort_if(Gate::denies('representante.legal.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');

        DB::beginTransaction();
        try {
            $item2 = RepresentanteLegal::where([
                ['id', $this->defectoId], ['deleted', 0]
            ])->first();

            if ($item2->status != 1) {
                $this->dispatchBrowserEvent('errores', ['error' => 'El representante que ha seleccionado se encuentra actualmente desactivado, cambie el estado antes']);
                DB::rollBack();
            } else {
                $item = RepresentanteLegal::where([
                    ['default', 1], ['deleted', 0]
                ])->first();

                $item->default = 0;
                $item->update();

                $item2->default = 1;
                $item2->update();
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function getRepresentantesProperty()
    {
        return RepresentanteLegal::where([
            ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['last_name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['document', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['email', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['phone', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->paginate(12);
    }

    public function getRepresentanteeProperty()
    {
        return RepresentanteLegal::where([
            ['id', $this->representanteId], ['deleted', 0]
        ])->first();
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['status', 1], ['deleted', 0]
        ])->get();
    }
}
