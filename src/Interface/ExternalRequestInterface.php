<?php

namespace App\Interface;

interface ExternalRequestInterface
{

    /**
     * @param $term
     * @return mixed
     */
    public function makeRequest($term);
}
