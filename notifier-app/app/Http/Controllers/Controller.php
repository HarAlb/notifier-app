<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OAT;

#[
    OAT\Info(version: '1.0', title: 'Outbox Pattern documentation'),
    OAT\Server(url: L5_SWAGGER_CONST_HOST, description: 'API Server')
]
abstract class Controller
{
    //
}
