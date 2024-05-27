<?php

namespace App\Http\Livewire\Invitado;

use Carbon\Carbon;
use App\Models\Cursos;
use Livewire\Component;
use App\Models\Clientes;
use Illuminate\Support\Str;
use App\Models\Certificados;
use App\Models\TiposDocumentos;

class SearchLivewire extends Component
{
    public $typeDocument, $documento, $consecutivo, $curso;

    public $certificados, $encontrado = false, $nombreCliente;

    public function render()
    {
        return view('livewire.invitado.search-livewire');
    }

    public function searchCertificado()
    {
        $this->validate([
            'typeDocument' => 'required|exists:tipos_documentos,abbreviation',
            'curso' => 'required|exists:cursos,id',
            'consecutivo' => 'required|string|max:20',
            'documento' => 'required|numeric|max:999999999999'
        ], [
            'typeDocument.required' => 'El campo tipo de documento es obligatorio',
            'typeDocument.exists' => 'El campo tipo de documento seleccionado no existe',
        ]);

        $cliente = Clientes::where([
            ['document', $this->documento], ['type_document', $this->typeDocument], ['deleted', 0]
        ])->first();

        if ($cliente) {
            $certificado = Certificados::where([
                ['consecutive', $this->consecutivo], ['cliente_id', $cliente->id], ['curso_id', $this->curso], ['deleted', 0], ['status', 1]
            ])->first();

            if ($certificado) {
                $hoy = Carbon::now();
                $expiration_date = $certificado->expiration_date;
                $fecha_expiracion = Str::title(Carbon::create($expiration_date)->locale('es')->isoFormat('DD MMMM YYYY'));
                $url_visualizar = route('show.pdfs', ['id' => base64_encode($certificado->id), 'name' => $certificado->consecutive . ' - ' . $certificado->course_name . '.pdf']);

                if ($hoy > $expiration_date) {
                    if ($certificado->active == 1) {
                        $certificado->active = 0;
                        $certificado->update();
                    }
                    $nombre_cliente = $cliente->name . ' ' . $cliente->last_name;
                    $this->dispatchBrowserEvent('vencido', ['name' => Str::title($nombre_cliente), 'expiration_date' => $fecha_expiracion, 'name_curso' => Str::title($certificado->course_name)]);
                } else {
                    $nombre_cliente = $cliente->name . ' ' . $cliente->last_name;
                    $this->dispatchBrowserEvent('vigente', ['name' => Str::title($nombre_cliente), 'visualizar' => $url_visualizar, 'expiration_date' => $fecha_expiracion, 'name_curso' => Str::title($certificado->course_name)]);
                }
                $this->reset(['typeDocument', 'curso', 'consecutivo', 'documento']);
            } else {
                $this->dispatchBrowserEvent('errores', ['error' => __('El certificado no ha sido encontrado, verifica la información')]);
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('No se ha encontrado cliente con ese numero de identidad, verifica la información')]);
        }
    }

     public function searchCertificados()
    {
        $this->validate([
            'typeDocument' => 'required|exists:tipos_documentos,abbreviation',
            //'curso' => 'required|exists:cursos,id',
            'documento' => 'required|numeric|max:999999999999'
        ], [
            'typeDocument.required' => 'El campo tipo de documento es obligatorio',
            'typeDocument.exists' => 'El campo tipo de documento seleccionado no existe',
        ]);
         
        $this->reset(['certificados', 'encontrado']);

        $cliente = Clientes::where([
            ['document', $this->documento], ['type_document', $this->typeDocument], ['deleted', 0]
        ])->first();

        if ($cliente) {
            $dataCertificado = Certificados::where([
                ['cliente_id', $cliente->id], 
                //['curso_id', $this->curso], 
                ['deleted', 0], ['status', 1]
            ])->get();

            if(count($dataCertificado) > 0){
               
                $this->encontrado = true;
                $this->nombreCliente = $cliente->name . ' ' . $cliente->last_name;

                foreach($dataCertificado as $certi){
                    $certi->fecha_expiracion = Str::title(Carbon::create($certi->expiration_date)->locale('es')->isoFormat('DD MMMM YYYY'));
                    $certi->url = route('show.pdfs', ['id' => base64_encode($certi->id), 'name' => $certi->consecutive . ' - ' . $certi->course_name . '.pdf']);
                }
                $this->certificados = $dataCertificado;
                $this->dispatchBrowserEvent('encontrado');
                $this->reset(['typeDocument', 'curso', 'consecutivo', 'documento']);
            } else {
                $this->dispatchBrowserEvent('errores', ['error' => __('No ha sido encontrado certificados, verifica la información')]);
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('No se ha encontrado cliente con ese numero de identidad, verifica la información')]);
        }
    }

    public function clean(){
        $this->resetExcept('');
    }

    public function getDocumentosProperty()
    {
        return TiposDocumentos::where([
            ['status', 1], ['deleted', 0]
        ])->get()->makeHidden(['deleted', 'created_at', 'updated_at']);
    }

    /*public function getCursosProperty()
    {
        return Cursos::where([
            ['status', 1], ['deleted', 0]
        ])->orderBy('name')->get()->makeHidden(['deleted', 'created_at', 'updated_at', 'type', 'consecutive']);
    }*/
   
}
