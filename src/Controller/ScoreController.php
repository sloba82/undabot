<?php

namespace App\Controller;

use Exception;
use App\Service\TermService;
use App\Service\GitHubService;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints as Assert;

class ScoreController extends AbstractController
{

    /**
     * @var GitHubService
     */
    private $gitHubService;

    /**
     * @var TermService
     */
    private $termService;

    public function __construct(
        GitHubService $gitHubService,
        TermService $termService
    ) {
        $this->gitHubService = $gitHubService;
        $this->termService = $termService;
    }

    /**
     * @param Request $request
     * @param RateLimiterFactory $anonymousApiLimiter
     * @return JsonResponse
     */
    #[Route('/score', name: 'app_score', methods: ['GET', 'HEAD'])]
    public function index(
        Request $request,
        RateLimiterFactory $anonymousApiLimiter
    ): JsonResponse {
        try {
            $limiter = $anonymousApiLimiter->create($request->getClientIp());
            $limit = $limiter->consume();
            $headers = [
                'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
                'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp() - time(),
                'X-RateLimit-Limit' => $limit->getLimit(),
            ];

            if (false === $limiter->consume(1)->isAccepted()) {
                return $this->json(
                    [],
                    429,
                    $headers
                );
            }

            if ($this->validateRequestParam($request->query->all())) {
                return $this->json(
                    [],
                    400,
                    $headers
                );
            }

            $data = $this->termService->getScore($request->get('term'));
        } catch (Exception $exception) {
            return $this->json([], 400);
        }

        return $this->json(
            $data,
            201,
            $headers
        );
    }


    /**
     * @param $input
     * @return int
     */
    private function validateRequestParam($input): int
    {
        $constraints = new Collection([
            'term' => [new Optional(new Assert\Length([
                'min' => 1,
                'max' => 255,
            ]))],
        ]);

        $validator = Validation::createValidator();
        $errors = $validator->validate($input, $constraints);

        return count($errors);
    }
}
