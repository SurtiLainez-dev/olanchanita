<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(\App\Http\Controllers\Crons\AuthDocumentosCron::DeleteAuthDocumentos());
})->purpose('Display an inspiring quote');

//Artisan::command('authdocumentos', function (){
//    $this->comment(\App\Http\Controllers\Crons\AuthDocumentosCron::DeleteAuthDocumentos());
//})->comment('Elimina todos las autenticaciones de usuario para ver los documentos');
