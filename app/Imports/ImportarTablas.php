<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Cursos;
use App\Models\Aliados;
use App\Models\Asesores;
use App\Models\Clientes;
use App\Models\Horarios;
use Illuminate\Support\Str;
use App\Models\Certificados;
use App\Models\Instructores;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportarTablas implements ToCollection
{
    public $condicional;

    public function __construct($condicional)
    {
        $this->condicional = $condicional;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        //'Clientes es igual a 1' 
        // CERTIFICADO ARMADOR DE ANDAMIOS ES 2
        // certificado CORTADOR LADRILLO 3
        // CERTIFICADO MANEJO PLUMA GRUA 4
        //certificado primeros auxilios es 5
        // CERTIFICADO RESCANTE EN ALTURAS ES 6
        // CERTIFICADO TRABAJADOR AUTORIZADO ES 7
        // CERTIFICADO COORDINADOR ES 8
        // CERTIFICADO ACTUALIZACION COORDINADOR ES 9
        // CERTIFICADO JEFE DE AREA ES 10
        // CERTIFICADO TRABAJO SEGURO EN ALTURAS REENTRENAMIENTO ES 11
        switch ($this->condicional) {
            case 1:
                foreach ($rows as $key => $valor) {
                    $asesor = Asesores::where('name', 'LIKE', '%' . $valor[7] . '%')->first();
                    $aliado = Aliados::where('name', 'LIKE', '%' . $valor[8] . '%')->first();

                    $cliente = new Clientes();
                    $cliente->aliado_id = $aliado->id ?? 1;
                    $cliente->asesor_id = $asesor->id ?? 4;
                    $cliente->type_document = $valor[3];
                    $cliente->document = $valor[4];
                    $cliente->name = $valor[1];
                    $cliente->last_name = $valor[2];
                    $cliente->email = $valor[5];
                    $cliente->phone = $valor[6];
                    $cliente->status = 1;
                    $cliente->save();
                }
                break;

            case 2:
                $curso = Cursos::findorfail(7);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    /*$certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();*/
                    //Finalizacion de actualizacion de fechas

                    $cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[11];
                    $certificado->final_date = $valor[16];
                    $certificado->save();
                }

                break;
            case 3:
                $curso = Cursos::findorfail(10);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    /*$certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();*/
                    //Finalizacion de actualizacion de fechas


                    //dd($valor);
                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[11];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 4:
                $curso = Cursos::findorfail(9);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas



                    //dd($valor);
                   /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[11];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 5:
                $curso = Cursos::findorfail(6);
                foreach ($rows as $key => $valor) {

                     // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas

                    //dd($valor);
                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[11];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 6:
                $curso = Cursos::findorfail(8);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas

                    //dd($valor);
                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[11];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 7:
                $curso = Cursos::findorfail(1);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas

                    //dd($valor);
                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    $aliadoviejo = DB::table('aliadosviejo')->find($valor[11]);

                    $aliado = Aliados::where(
                        [
                            ['document', $aliadoviejo->documento],
                            ['type_document', $aliadoviejo->tipo_documento]
                        ]
                    )->first();

                    if ($aliado == null) {
                        $aliado = $this->createAliado($aliadoviejo);
                    }

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id ?? 1;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = $aliado->id ?? 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id ?? 1;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[12];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 8:
                $curso = Cursos::findorfail(3);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas

                    //dd($valor);
                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                     $aliadoviejo = DB::table('aliadosviejo')->find($valor[12]);

                    $aliado = Aliados::where(
                        [
                            ['document', $aliadoviejo->documento],
                            ['type_document', $aliadoviejo->tipo_documento]
                        ]
                    )->first();

                    if ($aliado == null) {
                        $aliado = $this->createAliado($aliadoviejo);
                    }

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id ?? 1;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = $aliado->id ?? 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id ?? 1;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[12];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 9:
                $curso = Cursos::findorfail(4);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas

                    //dd($valor);
                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    $aliado = DB::table('aliadosviejo')->find($valor[11]);

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id ?? 1;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = $aliado->id ?? 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id ?? 1;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[12];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 10:
                $curso = Cursos::findorfail(5);
                foreach ($rows as $key => $valor) {

                     // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas

                    //dd($valor);
                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    $aliado = DB::table('aliadosviejo')->find($valor[11]);

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id ?? 1;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = $aliado->id ?? 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id ?? 1;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[12];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            case 11:
                $curso = Cursos::findorfail(2);
                foreach ($rows as $key => $valor) {

                    // Actualizacion de fechas
                    $certificado = Certificados::where([['consecutive', $valor[4], ['curso_id', $curso->id] ]])->first();
                    $certificado->initial_date = $valor[1];
                    $certificado->final_date = $valor[2];
                    $certificado->created_at = $valor[3];
                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[2]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->update();
                    //Finalizacion de actualizacion de fechas


                    /*$cliente = Clientes::where('document', $valor[4])->first();

                    $soInstructor = Str::after($valor[7], '- ');
                    $instructor = Instructores::where('resolucion_so', 'LIKE', '%' . $soInstructor)->first();

                    $horario = Horarios::where('timer', Str::before($valor[6], 'HORAS'))->first();

                    $fecha = Carbon::createFromFormat('Y-m-d', $valor[16]);
                    $fechaConAnio = $fecha->addYear()->format('Y-m-d');

                    $aliadoviejo = DB::table('aliadosviejo')->find($valor[12]);

                    $aliado = Aliados::where(
                        [
                            ['document', $aliadoviejo->documento],
                            ['type_document', $aliadoviejo->tipo_documento]
                        ]
                    )->first();

                     if ($aliado == null) {
                        $aliado = $this->createAliado($aliadoviejo);
                    }

                    //dd($valor, $cliente, $soInstructor, $instructor, $horario, $fecha, $fechaConAnio);

                    $certificado = new Certificados();
                    $certificado->consecutive = $valor[1];
                    $certificado->user_id = Auth::user()->id;
                    $certificado->cliente_id = $cliente->id ?? 1;
                    $certificado->instructor_id = $instructor->id ?? 1;
                    $certificado->aliado_id = $aliado->id ?? 1;
                    $certificado->representante_legal_id = 3;
                    $certificado->curso_id = $curso->id;
                    $certificado->asesor_id = $cliente->asesor_id ?? 1;
                    $certificado->course_name = $curso->name;
                    $certificado->hours = $horario->timer . ' ' . $horario->type;
                    $certificado->horario_id = $horario->id;
                    $certificado->expiration_date = $fechaConAnio;
                    $certificado->active = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->status = $valor[14] == 'HABILITADO' ? 1 : 0;
                    $certificado->deleted = 0;
                    $certificado->initial_date = $valor[12];
                    $certificado->final_date = $valor[16];
                    $certificado->save();*/
                }

                break;
            default:
                # code...
                break;
        }
    }


    public function createAliado($aliadoviejo)
    {
        $item = new Aliados();
        $item->type_document = $aliadoviejo->tipo_documento;
        $item->document = $aliadoviejo->documento;
        $item->name = $aliadoviejo->nombre_aliado;
        $item->legal_representative = $aliadoviejo->representante_legal;
        $item->arl_name = $aliadoviejo->arl;
        $item->email = $aliadoviejo->correo;
        $item->address = $aliadoviejo->direccion;
        $item->phone = $aliadoviejo->contacto;
        $item->save();

        return $item;
    }
}
