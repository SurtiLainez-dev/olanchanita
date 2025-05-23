<?php

use Aws\Laravel\AwsServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | AWS SDK Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration options set in this file will be passed directly to the
    | `Aws\Sdk` object, from which all client objects are created. This file
    | is published to the application config directory for modification by the
    | user. The full set of possible options are documented at:
    | http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html
    |
    */
    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID', 'P43MQVMWSHE6CPPXLVN2'),
        'secret' => env('AWS_SECRET_ACCESS_KEY', '8zwISScezwV2NWli8uo7fiHvBAgh3nNU5hgvfsrmhPw'),
    ],
    'region' => env('AWS_REGION', 'nyc3.digitaloceanspaces.com'),
    'version' => 'latest',
    'ua_append' => [
        'L5MOD/' . AwsServiceProvider::VERSION,
    ],
];
