<?php

namespace App\Service;

use App\Entity\Term;
use App\Service\GitHubService;
use App\Repository\TermRepository;
use Doctrine\ORM\EntityManagerInterface;

class TermService
{

    /**
     * @var \App\Service\GitHubService
     */
    private $gitHubService;

    /**
     * @var TermRepository
     */
    private $termRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        GitHubService $gitHubService,
        TermRepository $termRepository,
        EntityManagerInterface $em
    ) {
        $this->gitHubService = $gitHubService;
        $this->termRepository = $termRepository;
        $this->em = $em;
    }

    /**
     * @param $term
     * @return array
     */
    public function getScore($term)
    {
        $termData = $this->termRepository->findByNameField($term);

        $responseData = [];
        if (!$termData) {
            $responseData = $this->gitHubService->makeRequest($term);
            if (isset($responseData['total_count'])) {
                $termData = $this->saveTerm($term, $responseData);
            }
        }

        return [
            'term' => $termData['name'],
            'score' => $this->calculateScore($termData)
        ];
    }

    /**
     * @param $term
     * @param $responseData
     * @return array
     */
    private function saveTerm($term, $responseData)
    {
        $termEntity = new Term();
        $termEntity->setName($term)
            ->setTotalCount($responseData['total_count']);
        $this->em->persist($termEntity);
        $this->em->flush();

        return [
            'id' => $termEntity->getId(),
            'name' => $termEntity->getName(),
            'total_count' => $termEntity->getTotalCount(),
        ];
    }


    /**
     * @param $termData
     * @return float
     */
    public function calculateScore($termData)
    {
        $allTotalCount = $this->termRepository->getSumByTotalCount();

        return round($termData['total_count'] / $allTotalCount['all_total_count'] * 10, 1);
    }
}
