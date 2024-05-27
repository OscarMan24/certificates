<?php

namespace App\Http\Livewire\Panel\Cursos;

use Exception;
use App\Models\Cursos;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CursosLivewire extends Component
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
    public $consecutive, $name, $type, $color;
    public $cursoId;

    public function render()
    {
        return view('livewire.panel.cursos.cursos-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('curso.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'consecutive'   => 'required|unique:cursos,consecutive',
            'name'          => 'required|max:255|min:2',
            'type'          => 'required|numeric',
            'color'         => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $item = new Cursos();
            $item->consecutive = Str::upper($this->consecutive);
            $item->name = $this->name;
            $item->type = $this->type;
            $item->created_user_id = Auth::user()->id;
            $item->color = $this->color;
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
        abort_if(Gate::denies('curso.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->cursoId = $id;
        if (!empty($this->Cursoo)) {
            $this->dispatchBrowserEvent('edit2');
            $this->consecutive = $this->Cursoo->consecutive;
            $this->type = $this->Cursoo->type;
            $this->name = $this->Cursoo->name;
            $this->color = $this->Cursoo->color;
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('curso.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'consecutive'   => 'required|unique:cursos,consecutive,' . $this->cursoId . ',id',
            'name'          => 'required|max:255|min:2',
            'type'          => 'required|numeric',
            'color'         => 'nullable'
        ]);
        DB::beginTransaction();
        try {

            $this->Cursoo->type = $this->type;
            $this->Cursoo->consecutive = $this->consecutive;
            $this->Cursoo->name = $this->name;
            $this->Cursoo->edit_user_id = Auth::user()->id;
            $this->Cursoo->color = $this->color;
            $this->Cursoo->update();

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
        abort_if(Gate::denies('curso.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->cursoId = $id;
        if ($this->Cursoo != '') {
            if ($this->Cursoo->status == 1) {
                $this->Cursoo->Status = 0;
            } else {
                $this->Cursoo->status = 1;
            }
            $this->Cursoo->edit_user_id = Auth::user()->id;
            $this->Cursoo->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('curso.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->cursoId = $id;
        if (!empty($this->Cursoo)) {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('curso.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!empty($this->Cursoo)) {
            $this->Cursoo->edit_user_id = Auth::user()->id;
            $this->Cursoo->deleted = 1;
            $this->Cursoo->update();
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'No se ha encontrado un registro, contacta al administrador']);
        }
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function getCursosProperty()
    {
        return Cursos::where([
            ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->orWhere([
            ['consecutive', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
        ])->paginate(12);
    }

    public function getCursooProperty()
    {
        return Cursos::where([
            ['id', $this->cursoId],  ['deleted', 0]
        ])->first();
    }
}
