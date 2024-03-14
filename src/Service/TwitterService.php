<?php

namespace App\Service;

use App\Helper\RequestHelper;
use App\Interface\ExternalRequestInterface;

class TwitterService implements ExternalRequestInterface
{

    /**
     * @var string
     */
    private $url = "";

    /**
     * @param $term
     * @return mixed
     */
    public function makeRequest($term)
    {
    }
}
