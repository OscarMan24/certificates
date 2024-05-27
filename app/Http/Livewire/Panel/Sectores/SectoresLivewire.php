<?php

namespace App\Http\Livewire\Panel\Sectores;

use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Sectores;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class SectoresLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteSector'];
    public $search = '', $searchStatus = '';
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'page' => ['except' => 1, 'as' => 'p'],
        'searchStatus' => ['except' => '', 'as' => 'status'],
    ];
    public $readytoload = false;
    public $sectorId, $sectorName;

    public function render()
    {
        return view('livewire.panel.sectores.sectores-livewire');
    }

    public function loadDatos()
    {
        $this->readytoload = true;
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function store()
    {
        abort_if(Gate::denies('sector.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate(['sectorName' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = Sectores::where('name', $value)
                        ->where(function ($query) {
                            $query->where('deleted', '!=', 1);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('El nombre del sector ya está en uso.');
                    }
                },
                'max:255',
                'min:2'
            ]
        ]);
        DB::beginTransaction();
        try {
            $sector = new Sectores();
            $sector->name = $this->sectorName;
            $sector->save();
            
            $this->dispatchBrowserEvent('storeSector');
            $this->clean();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('sector.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->sectorId = $id;
        if (!empty($this->DataSector)) {
            $this->sectorName = $this->DataSector->name;
            $this->dispatchBrowserEvent('openEdit');
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('sector.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'sectorName' => 'required|unique:sectores,name,' . $this->sectorId . ',id,deleted,0',
        ]);
        DB::beginTransaction();
        try {
            $this->DataSector->name = $this->sectorName;
            $this->DataSector->update();
            
            $this->dispatchBrowserEvent('updateSector');
            $this->clean();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('sector.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->sectorId = $id;
        $this->dispatchBrowserEvent('openBorrar');
        
    }

    public function deleteSector()
    {
        abort_if(Gate::denies('sector.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        DB::beginTransaction();
        try {
            $this->DataSector->deleted = 1;
            $this->DataSector->update();
            $this->clean();       
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
       
    }

    public function changestatus($id)
    {
        abort_if(Gate::denies('sector.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->sectorId = $id;
        if (!empty($this->DataSector)) {
            if ($this->DataSector->status == 1) {
                $this->DataSector->Status = 0;
            } else {
                $this->DataSector->status = 1;
            }
            $this->DataSector->update();
            $this->dispatchBrowserEvent('statusChanged');
        }
    }

    public function getSectoresProperty()
    {
        $searchStatus = $this->searchStatus;
        return Sectores::where('name', 'LIKE', '%' . $this->search . '%')
            ->when($this->searchStatus != '', function ($query) use($searchStatus) {
                return $query->where('status', $searchStatus);
            })
            ->where('deleted', 0)
            ->paginate(12);
    }

    public function getDataSectorProperty()
    {
        return Sectores::findorfail($this->sectorId);
    }
}
