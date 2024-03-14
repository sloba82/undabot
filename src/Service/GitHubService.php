<?php

namespace App\Service;

use App\Helper\RequestHelper;
use App\Interface\ExternalRequestInterface;

class GitHubService implements ExternalRequestInterface
{

    /**
     * @var string
     */
    private $url = "https://api.github.com/search/issues";

    /**
     * @var RequestHelper
     */
    private $requestHelper;

    /**
     * @var
     */
    protected $userAgent;

    /**
     * @param RequestHelper $requestHelper
     * @param $userAgent
     */
    public function __construct(RequestHelper $requestHelper, $userAgent)
    {
        $this->requestHelper = $requestHelper;
        $this->userAgent = $userAgent;
    }

    /**
     * @param $term
     * @return mixed
     */
    public function makeRequest($term)
    {
        $data = [
            'q' => $term
        ];

        $headers = [
            "Accept: application/vnd.github+json",
            "User-Agent: {$this->userAgent}",
        ];

        $response = $this->requestHelper->callAPI('GET', $this->url, $data, $headers);

        return json_decode($response, true);
    }
}
