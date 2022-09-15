<?php namespace Test\Http\Controller\Rest;

use App\Entity\Account;
use App\Entity\Popup;
use App\Entity\PopupCms;
use App\Testing\TestCase;
use Carbon\Carbon;

class PopupRestControllerTest extends TestCase
{
    public function setUp(): void
    {
        static::$truncate[] = 'popup';
        static::$truncate[] = 'cms';
        parent::setUp();
    }

    public function testAvailableAllPopups()
    {
        $defaultPopupProps = [
            'popupDisplay'      => 1,
            'popupText'         => 'test text',
            'popupTargetId'     => 0,
            'popupDelay'        => 1,
            'popupDisplayTimes' => 2
        ];
        /**
         * @var Account $account
         */
        $account = $this->generateAccount();
        $this->initPopups();

        $resp = $this->get(
            route(
                'rest::v1.popup.display', [
                    'pageUrl' => 'test-rest'
                ]
            )
        );

        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccess(
            $resp,
            [
                array_merge(
                    [
                        'popupId'    => 1,
                        'popupTitle' => 'test',
                    ], $defaultPopupProps
                )
            ]
        );

        $resp = $this->get(
            route(
                'rest::v1.popup.display', [
                    'pageUrl'   => 'profile',
                    'accountId' => $account->getAccountId(),
                ]
            )
        );

        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccess(
            $resp,
            [
                array_merge(
                    [
                        'popupId'    => 2,
                        'popupTitle' => 'test with rule'
                    ], $defaultPopupProps
                )
            ]
        );
    }

    public function testPopupsPriority()
    {
        /**
         * @var Account $account
         */
        $account = $this->generateAccount();
        $popupData1 = [
            'popupDisplay'          => Popup::POPUP_DISPLAY_BEFORE,
            'popupTitle'            => 'test',
            'popupText'             => 'test text',
            'popupType'             => Popup::POPUP_TYPE_POPUP,
            'popupTargetId'         => 0,
            'popupDelay'            => 1,
            'popupDisplayTimes'     => 2,
            'startDate'             => Carbon::now(),
            'endDate'               => Carbon::tomorrow(),
            'ruleSet' => null
        ];

        $popupData2 = [
            'popupDisplay'      => Popup::POPUP_DISPLAY_BEFORE,
            'popupTitle'        => 'test with rule',
            'popupText'         => 'test text',
            'popupType'         => Popup::POPUP_TYPE_POPUP,
            'popupTargetId'     => 0,
            'popupDelay'        => 1,
            'popupDisplayTimes' => 2,
            'startDate'         => Carbon::now(),
            'endDate'           => Carbon::tomorrow(),
            'ruleSet'           => $this->generateRuleSet()
        ];

        $cms = $this->generateCms('/test-page');


        $popup1 = $this->generatePopup($popupData1);

        $popupCms = new PopupCms($popup1->getPopupId(), $cms->getCmsId());
        \EntityManager::persist($popupCms);
        \EntityManager::flush($popupCms);

        $popup2 = $this->generatePopup($popupData2);

        $popupCms = new PopupCms($popup2->getPopupId(), $cms->getCmsId());
        \EntityManager::persist($popupCms);
        \EntityManager::flush($popupCms);

        $resp = $this->get(
            route(
                'rest::v1.popup.display', [
                    'pageUrl' => 'test-page',
                    'accountId' => $account->getAccountId(),
                ]
            )
        );

        //ordered by popupId
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccessSubset(
            $resp,
            [
                [
                    'popupId'    => 2,
                    'popupTitle' => 'test with rule',
                ]
            ]
        );

        $popup1->setPriority(1);
        \EntityManager::persist($popup1);
        \EntityManager::flush($popup1);

        $resp = $this->get(
            route(
                'rest::v1.popup.display', [
                    'pageUrl' => 'test-page',
                    'accountId' => $account->getAccountId(),
                ]
            )
        );

        //ordered by priority
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccessSubset(
            $resp,
            [
                [
                    'popupId'    => 1,
                    'popupTitle' => 'test',
                ]
            ]
        );

        $popup1->setPopupDisplay(Popup::POPUP_DISPLAY_NONE);
        \EntityManager::persist($popup1);
        \EntityManager::flush($popup1);

        $resp = $this->get(
            route(
                'rest::v1.popup.display', [
                    'pageUrl' => 'test-page',
                    'accountId' => $account->getAccountId(),
                ]
            )
        );

        //ordered by priority
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccessSubset(
            $resp,
            [
                [
                    'popupId'    => 2,
                    'popupTitle' => 'test with rule',
                ]
            ]
        );

    }

    protected function initPopups()
    {
        $popupData = [
            'popupDisplay'          => Popup::POPUP_DISPLAY_BEFORE,
            'popupTitle'            => 'test',
            'popupText'             => 'test text',
            'popupType'             => Popup::POPUP_TYPE_POPUP,
            'popupTargetId'         => 0,
            'popupDelay'            => 1,
            'popupDisplayTimes'     => 2,
            'startDate'             => Carbon::now(),
            'endDate'               => Carbon::tomorrow(),
            'ruleSet' => null
        ];

        $popupDataWithRule = [
            'popupDisplay'      => Popup::POPUP_DISPLAY_BEFORE,
            'popupTitle'        => 'test with rule',
            'popupText'         => 'test text',
            'popupType'         => Popup::POPUP_TYPE_POPUP,
            'popupTargetId'     => 0,
            'popupDelay'        => 1,
            'popupDisplayTimes' => 2,
            'startDate'         => Carbon::now(),
            'endDate'           => Carbon::tomorrow(),
            'ruleSet'           => $this->generateRuleSet()
        ];

        $genSpecifiedPopup = function($data, $cmsPage)
        {
            $popup = $this->generatePopup($data);
            $this->generatePopupCms($popup, $cmsPage);
        };
        $genSpecifiedPopup($popupData, 'test-rest');
        $genSpecifiedPopup($popupDataWithRule, 'profile');
    }
}
