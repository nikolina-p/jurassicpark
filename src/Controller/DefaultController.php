<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/lucky")
     */
    public function showHomePage()
    {
        return new Response(
            '<html><body>Halo World !!!</body></html>'
        );
    }
}
