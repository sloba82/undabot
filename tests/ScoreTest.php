<?php

namespace App\Tests;

use App\Service\TermService;
use App\Service\GitHubService;
use PHPUnit\Framework\TestCase;
use App\Repository\TermRepository;
use Doctrine\ORM\EntityManagerInterface;

class ScoreTest extends TestCase
{

    public function test_CalculateScore(): void
    {

        $getSumByTotalCount = 6;
        $termData['total_count'] = 4;
        $expectedResult = 6.7;

        $gitHubService = $this->createMock(GitHubService::class);
        $entityManagerInterface = $this->createMock(EntityManagerInterface::class);

        $allTotalCount['all_total_count'] = 5555;
        $termRepository = $this->createMock(TermRepository::class);
        $termRepository->expects(self::once())
            ->method('getSumByTotalCount')
            ->willReturn([
                'all_total_count' => $getSumByTotalCount,
            ]);

        $termService = new TermService($gitHubService, $termRepository, $entityManagerInterface);

        $calculateScoreResult = $termService->calculateScore($termData);

        $this->assertEquals(
            $expectedResult,
            $calculateScoreResult,
            'Score is not valid'
        );
    }
}
