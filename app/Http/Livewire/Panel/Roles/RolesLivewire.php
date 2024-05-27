<?php

namespace App\Http\Livewire\Panel\Roles;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class RolesLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado'];
    public $search = '';
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'page' => ['except' => 1, 'as' => 'p'],
    ];
    public $readytoload = false;
    public $rol_id, $name_rol, $permission_rol = [];


    public function render()
    {
        return view('livewire.panel.roles.roles-livewire');
    }

    public function store()
    {
        abort_if(Gate::denies('roles.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'name_rol' => 'required|unique:roles,name|max:255|min:2'
        ]);
        DB::beginTransaction();
        try {
            $rol = new Role();
            $rol->name = $this->name_rol;
            $rol->save();
            if ($this->permission_rol != null) {
                $per =  Permission::whereIn('id', $this->permission_rol)->get();
                $rol->syncPermissions($per);
            } else {
                $rol->syncPermissions([]);
            }
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
        abort_if(Gate::denies('roles.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->rol_id = $id;
        if (!empty($this->Rol)) {
            $this->name_rol = $this->Rol->name;
            foreach ($this->Rol->permissions as $per) {
                $this->permission_rol[] = $per->id;
            }
            $this->dispatchBrowserEvent('edit2');
        }
    }

    public function actualizar()
    {
        abort_if(Gate::denies('roles.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'name_rol' => 'required|unique:roles,name,' . $this->rol_id . ',id'
        ]);
        if ($this->permission_rol != null) {
            $per =  Permission::whereIn('id', $this->permission_rol)->get();
            $this->Rol->syncPermissions($per);
        } else {
            $this->Rol->syncPermissions([]);
        }
        $this->dispatchBrowserEvent('actualiizar');
        $this->clean();
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function loadDatos()
    {
        $this->readytoload = true;
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('roles.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->rol_id = $id;
        if (!$this->Rol->name == "Superadmin") {
            $this->dispatchBrowserEvent('borrar');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'Este rol no puede ser borrado']);
        }
    }

    public function borrado()
    {
        abort_if(Gate::denies('roles.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if (!$this->Rol->name == "Superadmin") {
            $this->Rol->delete();
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'Este rol no puede ser borrado']);
        }
    }

    public function getRolProperty()
    {
        return Role::findorfail($this->rol_id);
    }

    public function getRolesProperty()
    {
        return Role::where('name', 'LIKE', '%' . $this->search . '%')->paginate(12);
    }

    public function getPermisosProperty()
    {
        return Permission::all();
    }
}
