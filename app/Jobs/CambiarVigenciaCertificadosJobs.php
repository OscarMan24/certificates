<?php

namespace App\Jobs;

use Exception;
use App\Models\Certificados;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CambiarVigenciaCertificadosJobs implements ShouldQueue
{
    public $tries = 5;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $hoy = Carbon::now();
            $certificados = Certificados::where([
                ['deleted', 0], ['active', 1]
            ])->whereDate('expiration_date', '<', $hoy)->get();

            foreach ($certificados as $item) {
                $item->active = 0;
                $item->update();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
