<?php

namespace App\Http\Livewire\Panel\Horarios;

use Exception;
use Livewire\Component;
use App\Models\Horarios;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class HorariosLivewire extends Component
{
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
    public $horarioId;
    public $timer, $type;

    public function render()
    {
        return view('livewire.panel.horarios.horarios-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('horario.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'timer' => 'required',
            'type' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $item = new Horarios();
            $item->timer = $this->timer;
            $item->type = $this->type == 1 ? 'hora(s)' : 'dia(s)';
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
        abort_if(Gate::denies('horario.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->horarioId = $id;
        if (!empty($this->Horarioo)) {
            $this->dispatchBrowserEvent('edit2');
            $this->timer = $this->Horarioo->timer;
            switch ($this->Horarioo->type) {
                case 'hora(s)':
                    $this->type = 1;
                    break;
                case 'dia(s)':
                    $this->type = 2;
                    break;

                default:
                    # code...
                    break;
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('horario.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'timer' => 'required',
            'type' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $this->Horarioo->type = $this->type == 1 ? 'hora(s)' : 'dia(s)';
            $this->Horarioo->timer = $this->timer;
            $this->Horarioo->edit_user_id = Auth::user()->id;
            $this->Horarioo->update();
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
        abort_if(Gate::denies('horario.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->horarioId = $id;
        if ($this->Horarioo != '') {
            if ($this->Horarioo->status == 1) {
                $this->Horarioo->Status = 0;
            } else {
                $this->Horarioo->status = 1;
            }
            $this->Horarioo->edit_user_id = Auth::user()->id;
            $this->Horarioo->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('horario.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->horarioId = $id;
        if (!empty($this->Horarioo)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('horario.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Horarioo)) {
            $this->Horarioo->deleted = 1;
            $this->Horarioo->edit_user_id = Auth::user()->id;
            $this->Horarioo->update();
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function getHorariosProperty()
    {
        return Horarios::where([
            ['timer', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['type', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->paginate(12);
    }

    public function getHorariooProperty()
    {
        return Horarios::where([
            ['id', $this->horarioId], ['deleted', 0]
        ])->first();
    }
}
