<?php

namespace App\Http\Livewire\Panel\Configuracion;

use App\Imports\ImportarTablas;
use Exception;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionLivewire extends Component
{
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $readytoload = false;

    public $archivoImport;

    public $titleWeb, $descriptionWeb, $iconWeb, $iconCurrentWeb, $iconDarkWeb, $iconCurrentDarkWeb, $logoWeb, $logoCurrentWeb, $logoDarkWeb, $logoCurrentDarkWeb;

    public $mostrar = [
        1 => true,
        2 => false,
        3 => false
    ];

    public function render()
    {
        return view('livewire.panel.configuracion.configuracion-livewire');
    }

    public function subirImport()
    {
        if (Auth::user()->id == 2) {
            $this->resetValidation();
            DB::beginTransaction();
            try {

                Excel::import(new ImportarTablas(11), $this->archivoImport);
                $this->dispatchBrowserEvent('actualiizar');
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                dd($e);
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        }
    }

    public function actualizar4()
    {
        if (Auth::user()->id == 2) {
            DB::beginTransaction();
            try {

                /*$certificados = Certificados::where('deleted', 0)->get();

                foreach ($certificados as $key => $valor) {
                    $aliadoviejo = DB::table('aliadosviejo')->find($valor->aliado_id);

                    $aliadonew = Aliados::where(
                        [
                            ['document', $aliadoviejo->documento],
                            ['type_document', $aliadoviejo->tipo_documento]
                        ]
                    )->first();

                    $valor->aliado_id = $aliadonew->id;
                    $valor->update();
                }*/


                /*$certificados = Certificados::where([
                    ['deleted', 0], ['curso_id', 7]
                ])->get();

                foreach ($certificados as $key => $valor) {
                    if ($valor->course_name != 'ARMADOR DE ANDAMIOS') {
                        $cursos = Cursos::where('name', $valor->course_name)->first();
                        $valor->curso_id = $cursos->id;
                        $valor->update();
                    }
                }*/

                $this->dispatchBrowserEvent('actualiizar');
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                dd($e);
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        }
    }

    public function loadData()
    {
        $this->readytoload = true;
        $this->titleWeb = $this->Setting->title;
        $this->descriptionWeb = $this->Setting->description;
        $this->iconCurrentWeb = $this->Setting->icon;
        $this->iconCurrentDarkWeb = $this->Setting->icon_dark;
        $this->logoCurrentWeb = $this->Setting->logo;
        $this->logoCurrentDarkWeb = $this->Setting->logo_dark;
    }

    public function cambiarVariable($numero)
    {
        $this->mostrar[$numero] = !$this->mostrar[$numero];
    }

    public function actualizarSetting($type)
    {
        abort_if(Gate::denies('configuracion.edit'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');

        DB::beginTransaction();
        try {
            if ($type == 1) {
                $this->validate([
                    'titleWeb' => 'required|max:120',
                    'descriptionWeb' => 'required|max:550',
                ]);
                $this->Setting->title = $this->titleWeb;
                $this->Setting->description = $this->descriptionWeb;
            } elseif ($type == 2) {
                $this->validate([
                    'iconWeb' => 'nullable|image|max:5500|mimes:jpg,bmp,png,jpeg,webp|dimensions:width=150,height=150',
                    'iconDarkWeb' => 'nullable|image|max:5500|mimes:jpg,bmp,png,jpeg,webp|dimensions:width=150,height=150',
                ]);
                if ($this->iconWeb) {
                    $imgname2 = 'icon-claro' . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->iconWeb->extension();
                    $this->iconWeb->storeAs('app', $imageame2, 'public');
                    $this->Setting->icon = $imageame2;
                }
                if ($this->iconDarkWeb) {
                    $imgname2 = 'icon-dark' . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->iconDarkWeb->extension();
                    $this->iconDarkWeb->storeAs('app', $imageame2, 'public');
                    $this->Setting->icon_dark = $imageame2;
                }
            } elseif ($type == 3) {
                $this->validate([
                    'logoWeb' => 'nullable|image|max:5048|mimes:jpg,bmp,png,jpeg,webp|dimensions:width=256,height=256',
                    'logoDarkWeb' => 'nullable|image|max:5048|mimes:jpg,bmp,png,jpeg,webp|dimensions:width=256,height=256',
                ]);
                if ($this->logoWeb) {
                    $imgname2 =  'logo' . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->logoWeb->extension();
                    $this->logoWeb->storeAs('app', $imageame2, 'public');
                    $this->Setting->logo = $imageame2;
                }
                if ($this->logoDarkWeb) {
                    $imgname2 = 'logoDark' . '-' . Str::random(4);
                    $imageame2 = $imgname2 . '.' . $this->logoDarkWeb->extension();
                    $this->logoDarkWeb->storeAs('app', $imageame2, 'public');
                    $this->Setting->logo_dark = $imageame2;
                }
            }
            $this->Setting->edit_user_id = Auth::user()->id;
            $this->Setting->update();
            DB::commit();

            $this->dispatchBrowserEvent('actualiizar');
            $this->loadData();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function getSettingProperty()
    {
        return Setting::where('id', 1)->first();
    }
}
