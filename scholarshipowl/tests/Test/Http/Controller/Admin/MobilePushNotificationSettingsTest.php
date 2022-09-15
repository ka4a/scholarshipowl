<?php namespace Test\Http\Controller\Admin;

use App\Entity\Marketing\MobilePushNotificationSettings;
use App\Events\Scholarship\ScholarshipDisqualifiedWinnerEvent;
use App\Events\Scholarship\ScholarshipPotentialWinnerEvent;
use App\Listeners\FireBaseListener;
use App\Services\FireBaseService;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;

use Illuminate\Foundation\Testing\WithoutMiddleware;

class MobilePushNotificationSettingsTest extends TestCase
{
    use EntityGenerator;

    public function setUp(): void
    {
        parent::setUp();
    }

    public  function testEnabledEventIsNotTriggersFirebaseService()
    {
        list($repo, $scholarship, $application) = $this->initRepo();

        $setting = $repo->findOneBy(['eventName' => ScholarshipPotentialWinnerEvent::class]);
        $setting->setActive(true);
        \EntityManager::persist($setting);
        \EntityManager::flush($setting);

        \Event::forget(ScholarshipPotentialWinnerEvent::class);
        $mock = \Mockery::mock(FireBaseService::class);
        $mock->shouldReceive('sendScholarshipEventToUser')
            ->once()
            ->andReturn(null);
        $this->app->instance(FireBaseService::class, $mock);
        \Event::subscribe(FireBaseListener::class);
        \Event::dispatch(new ScholarshipPotentialWinnerEvent($scholarship, $application));
    }

    public function testDisabledEventIsNotTriggersFirebaseService()
    {
        list($repo, $scholarship, $application) = $this->initRepo();
        /**
         * @var MobilePushNotificationSettings $setting
         */
        $setting = $repo->findOneBy(['eventName' =>  ScholarshipPotentialWinnerEvent::class]);
        $setting->setActive(false);
        \EntityManager::persist($setting);
        \EntityManager::flush($setting);

        \Event::forget(ScholarshipPotentialWinnerEvent::class);
        $mock1 = \Mockery::mock(FireBaseService::class, function ($mock1) {
            $mock1->shouldNotReceive('sendScholarshipEventToUser');
        });
        $this->app->instance(FireBaseService::class, $mock1);
        \Event::subscribe(FireBaseListener::class);
        \Event::dispatch(new ScholarshipPotentialWinnerEvent($scholarship, $application));
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function initRepo(): array
    {
        $repo = \EntityManager::getRepository(MobilePushNotificationSettings::class);
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $application = $this->generateApplication($scholarship, $account);
        return array($repo, $scholarship, $application);
    }


}
