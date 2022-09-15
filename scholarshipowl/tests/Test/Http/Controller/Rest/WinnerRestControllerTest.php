<?php

namespace Test\Http\Controller\Rest;

use App\Testing\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WinnerRestControllerTest extends TestCase
{
    public function setUp(): void
    {
        static::$truncate[] = 'winner';
        parent::setUp();
    }

    public function testShowWinner()
    {
        $winner = $this->generateWinner();
        $resp = $this->get(route('rest-mobile::v1.winner.show', ['winnerId' => $winner->getId()]));
        $expected = [
            'status' => 200,
            'data' => [
                "id" => $winner->getId(),
                "scholarshipTitle" => $winner->getScholarshipTitle(),
                // 'wonAt' => $winner->getWonAt(), // problems with comparison of DateTime objects
                'amountWon' => $winner->getAmountWon(),
                'winnerName' => $winner->getWinnerName(),
                'winnerPhoto' => $winner->getWinnerPhoto(),
                'testimonialText' => $winner->getTestimonialText(),
                'testimonialVideo' => $winner->getTestimonialVideo()
            ]
        ];
        $this->seeJsonSubset($resp, $expected);
    }

    public function testIndex()
    {
        $winner1 = $this->generateWinner();
        $winner2 = $this->generateWinner();
        $winner3 = $this->generateWinner();
        $winner3->setPublished(false);
        \EntityManager::flush($winner3);

        $resp = $this->get(route('rest-mobile::v1.winner.index'));
        $resp = $this->decodeResponseJson($resp);
        $this->assertTrue(count($resp['data']) === 2);

        $resp = $this->get(route('rest-mobile::v1.winner.index', ['perPage' => 1, 'page' => 2]));
        $resp = $this->decodeResponseJson($resp);

        $this->assertTrue(count($resp['data']) === 1);
        $this->assertTrue($resp['meta']['count'] === 2);
        $this->assertTrue($resp['meta']['start'] === 1);
        $this->assertTrue($resp['meta']['limit'] === 1);
    }
}
