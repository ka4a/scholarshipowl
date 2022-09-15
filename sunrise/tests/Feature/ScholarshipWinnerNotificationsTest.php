<?php namespace Tests\Feature;

use App\Entities\Application;
use App\Console\Commands\ScholarshipWinnerNotification;
use App\Entities\ApplicationWinner;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ScholarshipWinnerNotificationsTest extends TestCase
{

    public function test_pause_winner_notification()
    {
        /**
         * Generate winner.
         */
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $application1 = $this->generateApplication($scholarship, 'test@test1.com');
        $application2 = $this->generateApplication($scholarship, 'test@test2.com');
        $application3 = $this->generateApplication($scholarship, 'test@test3.com');
        $this->sm()->expire($scholarship);

        /** @var ApplicationWinner[] $winners */
        $winners = $this->em()->getRepository(ApplicationWinner::class)
            ->createQueryBuilder('aw')
            ->join('aw.application', 'a')
            ->join('a.scholarship', 's', 'WITH', 's.id = :scholarship')
            ->where('aw.disqualifiedAt IS NULL')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getResult();

        $this->assertCount(1, $winners);
        $this->assertThat($winners[0]->getApplication(), $this->logicalOr(
            $this->equalTo($application1),
            $this->equalTo($application2),
            $this->equalTo($application3)
        ));

        $winnerApplication = $winners[0]->getApplication();

        $now = new \DateTime;

        /**
         * Select winner and send first notification
         */
        $this->mauticService->shouldReceive('notifyWinner')->once()
            ->with($winnerApplication, ScholarshipWinnerNotification::FIRST_NOTIFICATION);

        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        $this->em()->flush($winnerApplication->getWinner()->setPaused(true));

        $this->mauticService->shouldReceive('notifyWinner')->never();

        $now->modify('+2 day');
        $now->modify('+1 minute');
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        $now->modify('+1 day');
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        $this->assertFalse($winnerApplication->getWinner()->isDisqualified());

        $now->modify('+10 day');
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        $this->em()->flush($winnerApplication->getWinner()->setPaused(false));

        /**
         * Disqualify user and send disqualification email.
         */
        $this->mauticService->shouldReceive('notifyWinner')->once()->ordered()
            ->with($winnerApplication, ScholarshipWinnerNotification::DISQUALIFICATION);

        $this->mauticService->shouldReceive('notifyWinner')->once()->ordered()
            ->with(
                \Mockery::on(function(Application $application) use ($winners) {
                    return $application !== $winners[0]->getApplication();
                }),
                ScholarshipWinnerNotification::FIRST_NOTIFICATION
            );

        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );
    }

    public function test_do_not_choose_additional_winners()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $application1 = $this->generateApplication($scholarship, 'test@test1.com');
        $application2 = $this->generateApplication($scholarship, 'test@test2.com');
        $this->generateApplication($scholarship, 'test@test3.com');

        $applicationWinner1 = $this->generateApplicationWinner($application1);
        $applicationWinner2 = $this->generateApplicationWinner($application2);
        $applicationWinner2->setCreatedAt(new \DateTime('+1 day'));
        $this->em()->flush($applicationWinner2);

        $this->mauticService->shouldReceive('notifyWinner')->once()->ordered()
            ->with($application1, ScholarshipWinnerNotification::DISQUALIFICATION);

        $this->mauticService->shouldReceive('notifyWinner')->once()->ordered()
            ->with($application2, ScholarshipWinnerNotification::FIRST_NOTIFICATION);

        $now = new \DateTime('+4 day');
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        /** @var ApplicationWinner[] $winners */
        $winners = $this->em()->getRepository(ApplicationWinner::class)
            ->createQueryBuilder('aw')
            ->join('aw.application', 'a')
            ->join('a.scholarship', 's', 'WITH', 's.id = :scholarship')
            ->where('aw.disqualifiedAt IS NULL')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getResult();

        $this->assertCount(1, $winners);
        $this->assertTrue($applicationWinner1->isDisqualified());
        $this->assertEquals($applicationWinner2, $winners[0]);
    }

    public function test_scholarship_not_select_another_winner()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Can\'t select another winner as scholarship already have enough winners.');

        /**
         * Generate winner.
         */
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $this->generateApplication($scholarship, 'test@test.com');
        $this->sm()->expire($scholarship);

        $this->sm()->chooseWinners($scholarship, 1);
    }

    public function test_re_picking_winner_mechanism()
    {
        /**
         * Generate winner.
         */
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $application1 = $this->generateApplication($scholarship, 'test@test1.com');
        $application2 = $this->generateApplication($scholarship, 'test@test2.com');
        $application3 = $this->generateApplication($scholarship, 'test@test3.com');
        $this->sm()->expire($scholarship);

        /** @var ApplicationWinner[] $winners */
        $winners = $this->em()->getRepository(ApplicationWinner::class)
            ->createQueryBuilder('aw')
            ->join('aw.application', 'a')
            ->join('a.scholarship', 's', 'WITH', 's.id = :scholarship')
            ->where('aw.disqualifiedAt IS NULL')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getResult();

        $this->assertCount(1, $winners);
        $this->assertThat($winners[0]->getApplication(), $this->logicalOr(
            $this->equalTo($application1),
            $this->equalTo($application2),
            $this->equalTo($application3)
        ));

        /**
         * After 3 days winner should be disqualified and first email should be sent.
         */
        $now = new \DateTime('+3 days');
        $now->modify('+1 minute');

        $this->mauticService->shouldReceive('notifyWinner')->once()->ordered()
            ->with($winners[0]->getApplication(), ScholarshipWinnerNotification::DISQUALIFICATION);

        $this->mauticService->shouldReceive('notifyWinner')->once()->ordered()
            ->with(
                \Mockery::on(function(Application $application) use ($winners) {
                    return $application !== $winners[0]->getApplication();
                }),
                ScholarshipWinnerNotification::FIRST_NOTIFICATION
            );

        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );
        $this->assertTrue($winners[0]->isDisqualified());

        /**
         * Clear date because of new winner was created
         */
        $now = new \DateTime;

        $this->mauticService->shouldReceive('notifyWinner')->never();
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        /** @var ApplicationWinner[] $nextWinners */
        $nextWinners = $this->em()->getRepository(ApplicationWinner::class)
            ->createQueryBuilder('aw')
            ->join('aw.application', 'a')
            ->join('a.scholarship', 's', 'WITH', 's.id = :scholarship')
            ->where('aw.disqualifiedAt IS NULL')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getResult();

        $this->assertCount(1, $nextWinners);
        $this->assertThat($nextWinners[0]->getApplication(), $this->logicalOr(
            $this->equalTo($application1),
            $this->equalTo($application2),
            $this->equalTo($application3)
        ));

    }

    public function test_scholarship_basic_winner_notifications_logic()
    {
        /**
         * Generate winner.
         */
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('markContactAsWinner');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $winnerApplication = $this->generateApplication($scholarship, 'test@test.com');
        $this->sm()->expire($scholarship);

        $now = new \DateTime;

        /**
         * Select winner and send first notification
         */
        $this->mauticService->shouldReceive('notifyWinner')->once()
            ->with($winnerApplication, ScholarshipWinnerNotification::FIRST_NOTIFICATION);

        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        /**
         * Don't send notification between dates.
         */
        $this->mauticService->shouldReceive('notifyWinner')->never();

        Artisan::call('scholarship:winner:notification', [
            '--date' => $now->format('Y-m-d H:i:s'),
            '--ids' => $scholarship->getId()]
        );

        /**
         * Send second notification for the winner.
         */
        $this->mauticService->shouldReceive('notifyWinner')->once()
            ->with($winnerApplication, ScholarshipWinnerNotification::SECOND_NOTIFICATION);

        $now->modify('+2 day');
        $now->modify('+1 minute');
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );


        /**
         * Don't send notification any notifications.
         */
        $this->mauticService->shouldReceive('notifyWinner')->never();
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        /**
         * Disqualify user and send disqualification email.
         */
        $this->mauticService->shouldReceive('notifyWinner')->once()
            ->with($winnerApplication, ScholarshipWinnerNotification::DISQUALIFICATION);

        $now->modify('+1 day');
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );

        $this->assertTrue($winnerApplication->getWinner()->isDisqualified());

        /**
         * Don't send notification any notifications.
         */
        $this->mauticService->shouldReceive('notifyWinner')->never();
        Artisan::call('scholarship:winner:notification', [
                '--date' => $now->format('Y-m-d H:i:s'),
                '--ids' => $scholarship->getId()]
        );
    }
}
