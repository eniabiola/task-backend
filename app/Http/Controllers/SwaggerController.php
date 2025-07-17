<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Task API",
 *     description="This is the API documentation for My Laravel App",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )

 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

class SwaggerController extends Controller
{
    //
}
