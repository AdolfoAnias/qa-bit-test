<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", title: "API REST - QA-BIT", description: "Documentación de la API REST de prueba técnica de QA BIT")]
#[OA\Server(url: "http://127.0.0.1", description: "Servidor Local")]
abstract class Controller
{
    //
}
