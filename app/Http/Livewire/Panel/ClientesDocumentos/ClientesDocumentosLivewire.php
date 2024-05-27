<?php

namespace App\Http\Livewire\Panel\ClientesDocumentos;

use Exception;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\ClienteDocumentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ClientesDocumentosLivewire extends Component
{
    use WithFileUploads;
    protected $listeners = ['getClienteId'];
    /*public $evaluacion, $evaluacionActual, $documentoIdentidad, $documentoIdentidadActual, $certificadoMedico, $certificadoMedicoActual,
        $certificadoAnterior, $certificadoAnteriorActual, $seguridadSocial, $seguridadSocialActual, $certificadoVigente, $certificadoVigenteActual,
        $certificadoLaboral, $certificadoLaboralActual;*/

    public $clienteId, $name;
    public $documentosPersonales, $documentosPersonalesActual, $documentosEntrenamiento, $documentosEntrenamientosActual;


    public function render()
    {
        return view('livewire.panel.clientes-documentos.clientes-documentos-livewire');
    }

    public function subirInformacion2()
    {
        abort_if(Gate::denies('clienteDocumento.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'documentosPersonales'  => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
            'documentosEntrenamiento'  => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
        ]);
        DB::beginTransaction();
        try {
            if ($this->documentosPersonales) {
                $documento = $this->checkAvailable('Documentos Personales');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->documentosPersonales->extension();

                if ($documento) {
                    $this->documentosPersonales->storeAs('Documentos/DocumentosPersonales', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->edit_user_id = Auth::user()->id;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Documentos Personales';
                    $this->documentosPersonales->storeAs('Documentos/DocumentosPersonales', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->created_user_id = Auth::user()->id;
                    $documento->save();
                }
            }

            if ($this->documentosEntrenamiento) {
                $documento = $this->checkAvailable('Documentos Entrenamiento');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->documentosEntrenamiento->extension();

                if ($documento) {
                    $this->documentosEntrenamiento->storeAs('Documentos/DocumentosEntrenamiento', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->edit_user_id = Auth::user()->id;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Documentos Entrenamiento';
                    $this->documentosEntrenamiento->storeAs('Documentos/DocumentosEntrenamiento', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->created_user_id = Auth::user()->id;
                    $documento->save();
                }
            }
            $this->closeModal();
            $this->dispatchBrowserEvent('subirDocumentossSuccess');
            $this->clean();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function getClienteId(array $data)
    {
        abort_if(Gate::denies('clienteDocumento.index'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->clean();
        $this->clienteId = $data['id'];
        $this->name = $data['name'];
        $this->dispatchBrowserEvent('subirDocumentoss');
        if (!empty($this->Documentoss)) {
            foreach ($this->Documentoss as $item) {
                if ($item->status == 1) {

                    switch ($item->name) {
                        case 'Documentos Personales':
                            $this->documentosPersonalesActual = $item->document;
                            break;
                        case 'Documentos Entrenamiento':
                            $this->documentosEntrenamientosActual = $item->document;
                            break;

                            /*case 'Evaluacion':
                            $this->evaluacionActual = $item->document;
                            break;
                        case 'Documento Identidad':
                            $this->documentoIdentidadActual = $item->document;
                            break;
                        case 'Certificado Medico':
                            $this->certificadoMedicoActual = $item->document;
                            break;
                        case 'Certificado Anterior':
                            $this->certificadoAnteriorActual = $item->document;
                            break;
                        case 'Seguridad Social':
                            $this->seguridadSocialActual = $item->document;
                            break;
                        case 'Certificado Vigente':
                            $this->certificadoVigenteActual = $item->document;
                            break;
                        case 'Certificado Laboral':
                            $this->certificadoLaboralActual = $item->document;
                            break;*/

                        default:
                            # code...
                            break;
                    }
                }
            }
        }
    }

    /* public function subirInformacion()
    {
        abort_if(Gate::denies('clienteDocumento.store'), Response::HTTP_FORBIDDEN, '403 Forbidden - No tiene permiso para realizar esta accion');
        $this->validate([
            'evaluacion'          => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
            'documentoIdentidad'  => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
            'certificadoMedico'   => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
            'certificadoAnterior' => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
            'seguridadSocial'     => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
            'certificadoVigente'  => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
            'certificadoLaboral'  => 'nullable|file|max:5500|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png',
        ]);

        DB::beginTransaction();
        try {
            if ($this->evaluacion) {
                $documento = $this->checkAvailable('Evaluacion');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->evaluacion->extension();

                if ($documento) {
                    $this->evaluacion->storeAs('Documentos/Evaluacion', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Evaluacion';
                    $this->evaluacion->storeAs('Documentos/Evaluacion', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->save();
                }
            }

            if ($this->documentoIdentidad) {
                $documento = $this->checkAvailable('Documento Identidad');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->evaluacion->extension();

                if ($documento) {
                    $this->evaluacion->storeAs('Documentos/DocumentoIdentidad', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Documento Identidad';
                    $this->documentoIdentidad->storeAs('Documentos/DocumentoIdentidad', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->save();
                }
            }

            if ($this->certificadoMedico) {
                $documento = $this->checkAvailable('Certificado Medico');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->evaluacion->extension();

                if ($documento) {
                    $this->evaluacion->storeAs('Documentos/CertificadoMedico', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Certificado Medico';

                    $this->certificadoMedico->storeAs('Documentos/CertificadoMedico', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->save();
                }
            }

            if ($this->certificadoAnterior) {
                $documento = $this->checkAvailable('Certificado Anterior');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->evaluacion->extension();

                if ($documento) {
                    $this->evaluacion->storeAs('Documentos/CertificadoAnterior', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Certificado Anterior';
                    $this->certificadoAnterior->storeAs('Documentos/CertificadoAnterior', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->save();
                }
            }

            if ($this->seguridadSocial) {
                $documento = $this->checkAvailable('Seguridad Social');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->seguridadSocial->extension();

                if ($documento) {
                    $this->evaluacion->storeAs('Documentos/SeguridadSocial', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Seguridad Social';
                    $this->seguridadSocial->storeAs('Documentos/SeguridadSocial', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->save();
                }
            }

            if ($this->certificadoVigente) {
                $documento = $this->checkAvailable('Certificado Vigente');

                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->certificadoVigente->extension();

                if ($documento) {
                    $this->evaluacion->storeAs('Documentos/CertificadoVigente', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Certificado Vigente';
                    $this->certificadoVigente->storeAs('Documentos/CertificadoVigente', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->save();
                }
            }

            if ($this->certificadoLaboral) {
                $documento = $this->checkAvailable('Certificado Laboral');


                $documentoname = Str::slug($this->name . '-' . Carbon::now()->format('Y-m-d H:i:s'));
                $documenFile = $documentoname . '.' . $this->certificadoLaboral->extension();

                if ($documento) {
                    $this->evaluacion->storeAs('Documentos/CertificadoLaboral', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->update();
                } else {
                    $documento = new ClienteDocumentos();
                    $documento->cliente_id = $this->clienteId;
                    $documento->name = 'Certificado Laboral';
                    $this->certificadoLaboral->storeAs('Documentos/CertificadoLaboral', $documenFile, 'public');
                    $documento->document = $documenFile;
                    $documento->status = 1;
                    $documento->save();
                }
            }

            $this->closeModal();
            $this->dispatchBrowserEvent('subirDocumentossSuccess');
            $this->clean();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }*/

    public function checkAvailable($name)
    {
        foreach ($this->Documentoss as $item) {
            if ($item->status == 1 && $item->name == $name) {
                return $item;
            }
        }
        return false;
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('closeModal');
    }

    public function clean()
    {
        $this->resetExcept(['page', 'search', 'readytoload']);
    }

    public function getDocumentossProperty()
    {
        return ClienteDocumentos::where('cliente_id', $this->clienteId)->get();
    }
}
