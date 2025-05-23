<?php

namespace App\Http\Controllers;

use App\Models\DatosEmpresa;
//use Aws\S3\S3Client as aws;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class S3 extends Controller
{
    public static function cargarFileS3($file, $directorio, $estado){
        $FILE = $file;
        $nombreFile = time().'-'.round(10000,999999).'-.pdf';
        Storage::disk('s3')->putFileAs(self::Empresa()->nombre_corto.'/'.$directorio, $file, $nombreFile, $estado);
        return self::Empresa()->nombre_corto.'/'.$directorio.$nombreFile;
    }

    public static function conexion(){
        return  new aws([
            'version' => 'latest',
            'region'  => 'nyc3',
            'endpoint' => ('https://nyc3.digitaloceanspaces.com'),
            'credentials' => [
                'key'    => 'DO00XTF7VYZKMV4QCKEH',
                'secret' => 'hsosbPRTmcLM33Obqh9UkUa7JOFVURU2MElSh4I171U',
            ],
        ]);
    }

    private static function Empresa(){
        return DatosEmpresa::first();
    }
}
