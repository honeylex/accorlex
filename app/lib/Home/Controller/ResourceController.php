<?php

namespace Accorlex\Home\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ResourceController
{
    public function read(Request $request, Application $app)
    {
        return 'Hello';
    }
}
