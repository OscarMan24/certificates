<?php

namespace App\Http\Livewire\Panel;

use App\Exports\UsuariosExport;
use Exception;
use App\Models\User;
use App\Models\Setting;
use App\Models\TiposDocumentos;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class UsuariosLivewire extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado', 'downloadReportes'];
    public $search = '', $search_status = '', $search_rol = '';
    public $typeDocument = "CC", $document, $nameUser, $address, $name, $lastName, $jobTitle,
        $email, $phone,  $image, $image_current, $password,  $roles_user = [];
    public $user_id;
    public $readytoload = false;
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'search_status' => ['except' => '', 'as' => 'status'],
        'search_rol' => ['except' => '', 'as' => 'rol'],
        'page' => ['except' => 1, 'as' => 'p'],

    ];

    public function render()
    {
        return view('livewire.panel.usuarios.usuarios-livewire');
    }

    public function loadData()
    {
        $this->readytoload = true;
    }

    public function store()
    {
        abort_if(Gate::denies('users.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|min:2|max:120',
            'lastName' => 'required|min:2|max:120',
            'jobTitle' => 'nullable|max:120',
            'address' => 'nullable|max:120',
            'nameUser' => 'required|min:3|max:120|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'image' => 'nullable|image|max:5048|dimensions:width=1080,height=1080',
            'password' =>  ['required', Password::min(8)->numbers()],
            'roles_user' => 'required|array|min:1',
            'phone' => 'required|phone:CO,AUTO'
        ]);

        DB::beginTransaction();
        try {
            $user = new User();
            $user->type_document = $this->typeDocument;
            $user->document = $this->document;
            $user->name = $this->name;
            $user->last_name = $this->lastName;
            $user->cargo = $this->jobTitle;
            $user->address = $this->address;
            $user->username = $this->nameUser;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->password = Hash::make($this->password);
            if ($this->image) {
                $imgname2 = Str::slug(Str::limit($this->nameUser, 6, '')) . '-' . Str::random(4);
                $imageame2 = $imgname2 . '.' . $this->image->extension();
                $this->image->storeAs('users', $imageame2, 'public');
                $user->image = $imageame2;
            } else {
                $user->image = 'defaultuser.png';
            }
            $user->status = 1;
            $user->deleted = 0;
            $user->save();
            $user->assignRole($this->roles_user);

            DB::commit();

            $this->dispatchBrowserEvent('storeuser');
            $this->clean();
            $this->reset('roles_user');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('users.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->user_id = $id;
        if ($this->Userr != '') {
            $this->reset('roles_user');
            $this->typeDocument = $this->Userr->type_document;
            $this->document = $this->Userr->document;
            $this->nameUser = $this->Userr->username;
            $this->address = $this->Userr->address;
            $this->name = $this->Userr->name;
            $this->lastName = $this->Userr->last_name;
            $this->jobTitle = $this->Userr->cargo;
            $this->email = $this->Userr->email;
            $this->phone = $this->Userr->phone;
            $this->image_current = $this->Userr->image;
            foreach ($this->Userr->getRoleNames() as $r) {
                $this->addrol($r);
            }
            $this->dispatchBrowserEvent('openEdit');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, póngase en contacto con soporte')]);
        }
    }

    public function editUser()
    {
        abort_if(Gate::denies('users.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'typeDocument' => 'required',
            'document' => 'required|numeric',
            'name' => 'required|min:2|max:120',
            'lastName' => 'required|min:2|max:120',
            'jobTitle' => 'nullable|max:120',
            'address' => 'nullable|max:120',
            'nameUser' => ['required', 'min:3', 'max:120', Rule::unique('users', 'username')->ignore($this->user_id)],
            'email' =>  ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user_id)],
            'image' => 'nullable|image|max:5048|dimensions:width=1080,height=1080',
            'password' =>  ['nullable', Password::min(8)->numbers()],
            'roles_user' => 'required|array|min:1',
            'phone' => 'required|phone:CO,AUTO'
        ], [
            'phone.phone' => 'El formato del telefono no contiene un numero valido'
        ]);

        DB::beginTransaction();
        try {
            $this->Userr->type_document = $this->typeDocument;
            $this->Userr->document = $this->document;
            $this->Userr->name = $this->name;
            $this->Userr->last_name = $this->lastName;
            $this->Userr->cargo = $this->jobTitle;
            $this->Userr->address = $this->address;
            $this->Userr->username = $this->nameUser;
            $this->Userr->email = $this->email;
            $this->Userr->phone = $this->phone;
            if ($this->password != '') {
                $this->Userr->password = Hash::make($this->password);
            }
            if ($this->image) {
                $imgname2 = Str::slug(Str::limit($this->nameUser, 6, '')) . '-' . Str::random(4);
                $imageName2 = $imgname2 . '.' . $this->image->extension();
                $this->image->storeAs('users', $imageName2, 'public');
                $this->Userr->image = $imageName2;
            }
            $this->Userr->update();
            $this->Userr->syncRoles($this->roles_user);


            DB::commit();
            $this->dispatchBrowserEvent('updateusser');
            $this->clean();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function addrol($id)
    {
        /* Validates if the role exists in the array, if it exists it eliminates it otherwise it adds it */
        $r = array_search($id, array_column($this->roles_user, 'id'));

        if ($r !== false) {
            unset($this->roles_user[$r]);
        } else {
            $temporal = array(
                'id' => $id
            );
            array_push($this->roles_user, $temporal);
        }
    }

    public function changestatus($id)
    {
        abort_if(Gate::denies('users.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->user_id = $id;
        if ($this->Userr != '') {
            if ($this->Userr->status == 1) {
                $this->Userr->Status = 0;
            } else {
                $this->Userr->status = 1;
            }
            $this->Userr->update();
            $this->dispatchBrowserEvent('statuschanged');
        }
    }

    public function borrar($id)
    {
        abort_if(Gate::denies('users.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->user_id = $id;
        $this->dispatchBrowserEvent('borrar');
    }

    public function borrado()
    {
        abort_if(Gate::denies('users.delete'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        if ($this->Userr != '') {
            $this->Userr->email = base64_encode($this->Userr->email);
            $this->Userr->status = 2;
            $this->Userr->deleted = 1;
            $this->Userr->update();
        }
    }

    public function clean()
    {
        $this->resetExcept(['search', 'search_status', 'search_rol', 'readytoload']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchStatus()
    {
        $this->resetPage();
    }

    public function updatedSearchRol()
    {
        $this->resetPage();
    }

    public function getUsersProperty()
    {

        if ($this->search_rol != '') {
            $user = User::role($this->search_rol)
                ->where([
                    ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
                ])->paginate(12);
        } else {
            $user = User::where([
                ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
            ])->orWhere([
                ['email', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
            ])->orWhere([
                ['last_name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
            ])->orWhere([
                ['username', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
            ])->orWhere([
                ['phone', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_status], ['deleted', 0]
            ])->paginate(12);
        }
        return $user;
    }

    public function getUserrProperty()
    {
        return User::where('id', $this->user_id)->first();
    }

    public function getRolesProperty()
    {
        return Role::all()->pluck('name');
    }

    public function downloadReportes($tipo)
    {
        if ($tipo != '' && $tipo != null && $tipo == 1 || $tipo == 2 || $tipo == 3) {

            try {
                return Excel::download(new UsuariosExport($tipo), 'users.xlsx');
            } catch (Exception $e) {
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => 'Ha ocurrido un error, contacta al administrador']);
        }
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['deleted', 0], ['status', 1]
        ])->get();
    }
}
