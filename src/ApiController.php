<?php

namespace Tokimikichika\Find;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController
{
    private TextAnalyzer $analyzer;

    public function __construct(TextAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
    }


}


