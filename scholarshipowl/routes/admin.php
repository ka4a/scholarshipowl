<?php
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin::'], function() {

    // Admin - Login Routes
    Route::get('login', 'IndexController@loginAction')->name('index.login');
    Route::post('post-login', 'IndexController@postLoginAction')->name('index.postLogin');

    Route::group(['middleware' => ['auth:admin', 'admin.activity']], function() {

        // Admin - Dashboard Routes
        Route::group(['as' => 'index.', 'permission' => 'ALLOW'], function() {
            Route::get('dashboard', ['uses' => 'IndexController@indexAction',  'as' => 'index']);
            Route::get('logout',    ['uses' => 'IndexController@logoutAction', 'as' => 'logout']);
        });

        Route::group(['prefix' => 'acl', 'as' => 'acl.', 'permission' => ['acl' => 'Access Limiter']], function(){
            Route::get('',                       ['uses' => 'AclController@adminsAction',              'as' => 'admins']);
            Route::get('admin/{adminId?}',       ['uses' => 'AclController@adminAction',               'as' => 'admin']);
            Route::post('admin/{adminId?}',      ['uses' => 'AclController@adminPostAction',           'as' => 'admin-post']);
            Route::get('admin-delete/{adminId}', ['uses' => 'AclController@adminDeleteAction',         'as' => 'admin-delete']);
            Route::get('role/{roleId?}',         ['uses' => 'AclController@roleAction',                'as' => 'role']);
            Route::post('role/{roleId?}',        ['uses' => 'AclController@rolePostAction',            'as' => 'role-post']);
            Route::get('role-delete/{roleId}',   ['uses' => 'AclController@roleDeleteAction',          'as' => 'role-delete']);
            Route::get('roles',                  ['uses' => 'AclController@rolesAction',               'as' => 'roles']);
            Route::get('permissions/{roleId}',   ['uses' => 'AclController@rolePermissionsAction',     'as' => 'permissions']);
            Route::post('permissions/{roleId}',  ['uses' => 'AclController@rolePermissionsPostAction', 'as' => 'permissions-post']);
            Route::get('permissions-tree/{id}',  ['uses' => 'AclController@permissionsTreeAction',     'as' => 'permissions-tree']);
        });

        Route::group(['prefix' => 'subscriptions', 'as' => 'subscriptions.', 'permission' => ['subscriptions' => 'Subscriptions View/Edit']], function() {
            Route::get('', ['uses' => 'SubscriptionsController@indexAction', 'as' => 'index']);
            Route::get('double-subscriptions', ['uses' => 'SubscriptionsController@doubleSubscriptions', 'as' => 'doubleSubscriptions']);
        });

        // Admin - Scholarships Routes
        Route::group(['prefix' => 'scholarships', 'as' => 'scholarships.', 'permission' => ['scholarships.view' => 'Scholarships View']], function() {
            Route::get('/', 'ScholarshipController@indexAction')->name('index');
            Route::get('/search', 'ScholarshipController@searchAction')->name('search');
            Route::get('/view', 'ScholarshipController@viewAction')->name('view');
            Route::any('/test', 'ScholarshipController@testAction')->name('test');
            Route::get('/maintain', 'ScholarshipController@maintainAction')->name('maintain');

            Route::group(['permission' => ['scholarships.super-collage' => 'Scholarships Supper College API']], function() {
                Route::get('/super-college', 'ScholarshipController@superCollegeAction')->name('superCollege');
                Route::get('/super-college/view', 'ScholarshipController@superCollegeViewAction')->name('superCollegeView');
                Route::get('/super-college-eligibility', 'ScholarshipController@superCollegeEligibilityAction')->name('superCollegeEligibilityAction');
                Route::get('/super-college-match', 'ScholarshipController@superCollegeMatchAction')->name('superCollegeMatch');
            });

            Route::group(['permission' => ['scholarships.edit' => 'Scholarships Edit']], function() {
                Route::get('/save', 'ScholarshipController@saveAction')->name('save');
                Route::get('/delete', 'ScholarshipController@deleteAction')->name('delete');
                Route::get('/recur/{id}', 'ScholarshipController@recurAction')->name('recur');
                Route::get('/delete-logo', 'ScholarshipController@deleteLogoAction')->name('delete-logo');
                Route::get('/copy', 'ScholarshipController@copyAction')->name('copy');
                Route::get('/fetch-form', 'ScholarshipController@fetchFormAction')->name('fetchForm');
                Route::get('/fetch-field', 'ScholarshipController@fetchFieldAction')->name('fetchField');
                Route::post('/post-save-information', 'ScholarshipController@postSaveInformationAction')->name('postSaveInformation');
                Route::post('/post-save-application', 'ScholarshipController@postSaveApplicationAction')->name('postSaveApplication');
                Route::post('/post-save-eligibility', 'ScholarshipController@postSaveEligibilityAction')->name('postSaveEligibility');
                Route::post('/post-save-files', 'ScholarshipFileController@store')->name('postSaveFiles');
                Route::post('/post-save-field', 'ScholarshipController@postSaveFieldAction')->name('postSaveField');
                Route::post('/post-delete-field', 'ScholarshipController@postDeleteFieldAction')->name('postDeleteField');

                Route::post('/save-requirements', 'ScholarshipController@saveRequirements')->name('saveRequirements');
                Route::post('/save-scholarship-metatags/{id}', 'ScholarshipController@saveMetatagsAction')->name('saveMetatags');
            });
        });

        Route::group(['prefix' => 'winners', 'as' => 'winners.', 'permission' => ['winners.view' => 'Winners View']], function() {
            Route::get('/', 'WinnerController@searchAction')->name('index');
            Route::get('/search', 'WinnerController@searchAction')->name('search');
            Route::get('/view/{id}', 'WinnerController@viewAction')->name('view');
            Route::group(['permission' => ['winners.edit' => 'Winners Edit']], function() {
                Route::any('/edit/{id?}', 'WinnerController@editAction')->name('edit');
                Route::any('/delete/{id}', 'WinnerController@deleteAction')->name('delete');
            });
        });

        // Admin - Accounts Routes
        Route::group(['prefix' => 'accounts', 'as' => 'accounts.', 'permission' => ['accounts.view' => 'Accounts View']], function() {

            Route::get('/', 'AccountController@indexAction')->name('index');
            Route::get('/view', 'AccountController@viewAction')->name('view');
            Route::get('/search', 'AccountController@searchAction')->name('search');
            Route::get('/applications', 'AccountController@applicationsAction')->name('applications');
            Route::get('/eligibility', 'AccountController@eligibilityAction')->name('eligibility');
            Route::get('/loginhistory', 'AccountController@loginHistoryAction')->name('loginHistory');

            Route::group(['permission' => ['accounts.mailbox' => 'Accounts Mailbox']], function() {
                Route::get('/mailbox/email/{id}', 'MailboxController@emailAction')->name('email');
                Route::get('/mailbox/folders/{id}', 'MailboxController@foldersAction')->name('folders');
            });

            Route::group(['permission' => ['accounts.edit' => 'Accounts Edit\Register']], function() {
                Route::get('/edit', 'AccountController@editAction')->name('edit');
                Route::get('/register', 'AccountController@registerAction')->name('register');
                Route::get('/delete', 'AccountController@deleteAction')->name('delete');
                Route::post('/post-register', 'AccountController@postRegisterAction')->name('postRegister');
                Route::post('/post-edit', 'AccountController@postEditAction')->name('postEdit');
                Route::post('/post-add-subscription', 'AccountController@postAddSubscriptionAction')->name('postAddSubscription');
            });

            Route::group(['permission' => ['accounts.conversation' => 'Accounts Conversations']], function() {
                Route::get('/conversations', 'AccountController@conversationsAction')->name('conversations');
                Route::get('/conversations/add', 'AccountController@addConversationAction')->name('addConversation');
                Route::get('/conversations/edit', 'AccountController@editConversationAction')->name('editConversation');
                Route::get('/conversations/delete', 'AccountController@deleteConversationAction')->name('deleteConversation');
                Route::post('/conversations/post-add', 'AccountController@postAddConversationAction')->name('postAddConversation');
                Route::post('/conversations/post-edit', 'AccountController@postEditConversationAction')->name('postEditConversation');
            });

            Route::group(['permission' => ['accounts.impersonate' => 'Accounts Impersonate']], function() {
                Route::get('/impersonate', 'AccountController@impersonateAction')->name('impersonate');
            });

            Route::group(['permission' => ['accounts.subscription' => 'Accounts Subscriptions']], function() {
                Route::any('/subscriptions/{id}', 'AccountController@subscriptionsAction')->name('subscriptions');
                Route::get('/subscriptions/{id}/cancel-subscription', 'AccountController@cancelSubscriptionAction')->name('cancelSubscription');
            });

        });

        Route::group(['prefix' => 'file', 'as' => 'accounts.file.', 'permission' => 'accounts.edit'], function() {
            Route::any('delete/{id}', 'AccountFileController@destroy')->name('delete');
            Route::any('download/{id}', 'AccountFileController@show')->name('download');
        });

        // Admin - Applications Routes
        Route::group(['prefix' => 'applications', 'as' => 'applications.', 'permission' => ['applications.view' => 'Applications View']], function() {
            Route::get('/', 'ApplicationController@indexAction')->name('index');
            Route::get('/search', 'ApplicationController@searchAction')->name('search');
            Route::get('/view', 'ApplicationController@viewAction')->name('view');
        });

        Route::group(['prefix' => 'features', 'as' => 'features.', 'permission' => ['features.edit' => 'Features View/Edit']], function() {
            Route::get('', 'Features\IndexController@index')->name('index');
            Route::any('edit/{id?}', 'Features\IndexController@edit')->name('edit');
            Route::any('delete/{id}', 'Features\IndexController@delete')->name('delete');
            Route::any('clone/{id}/{name?}', 'Features\IndexController@clone')->name('clone');

            Route::group(['prefix' => 'content_set', 'as' => 'content_sets.'], function() {
                Route::any('',              'Features\ContentSetController@index')->name('index');
                Route::any('edit/{id?}',    'Features\ContentSetController@edit')->name('edit');
                Route::any('delete/{id}',   'Features\ContentSetController@delete')->name('delete');
                Route::any('clone/{id}/{name?}', 'Features\ContentSetController@clone')->name('clone');
            });

            Route::group(['prefix' => 'payment_set', 'as' => 'payment_sets.'], function() {
                Route::any('',              'Features\PaymentSetController@index')->name('index');
                Route::any('edit/{id?}',    'Features\PaymentSetController@edit')->name('edit');
                Route::any('delete/{id}',   'Features\PaymentSetController@delete')->name('delete');
                Route::any('clone/{id}/{name?}', 'Features\PaymentSetController@clone')->name('clone');
            });

            Route::group(['prefix' => 'company_details_set', 'as' => 'company_details_set.'], function() {
                Route::any('',              'Features\CompanyDetailsSetController@index')->name('index');
                Route::any('edit/{id?}',    'Features\CompanyDetailsSetController@edit')->name('edit');
                Route::any('delete/{id}',   'Features\CompanyDetailsSetController@delete')->name('delete');
                Route::any('clone/{id}/{name?}', 'Features\CompanyDetailsSetController@clone')->name('clone');
            });

            Route::group(['prefix' => 'ab_tests', 'as' => 'ab_tests.'], function() {
                Route::any('', 'Features\AbTestController@index')->name('index');
                Route::any('edit/{id?}', 'Features\AbTestController@edit')->name('edit');
                Route::any('delete/{id}', 'Features\AbTestController@delete')->name('delete');
            });
        });

        // Admin - Marketing Routes
        Route::group(['prefix' => 'marketing', 'as' => 'marketing.', 'permission' => ['marketing.view' => 'Marketing View']], function() {
            Route::get('/', 'MarketingController@indexAction')->name('index');
            Route::get('search', 'MarketingController@searchAction')->name('search');
            Route::get('submissions', 'MarketingController@submissionsAction')->name('submissions');

            Route::group(['permission' => ['marketing.affiliates' => 'Marketing Affiliates View/Edit']], function() {
                Route::get('affiliates', 'MarketingController@affiliatesAction')->name('affiliates');
                Route::get('affiliates/save', 'MarketingController@saveAffiliateAction')->name('saveAffiliate');
                Route::get('affiliates_responses', 'MarketingController@affiliatesResponsesAction')->name('affiliatesResponses');
                Route::post('affiliates/post-save', 'MarketingController@postSaveAffiliateAction')->name('postSaveAffiliate');
            });

            Route::group(['prefix' => 'affiliate_goal_mapping', 'permission' => ['marketing.affiliate_goal_mapping' => 'Marketing Affiliate Goal Mapping View/Edit']], function() {
                Route::get('/', 'MarketingController@affiliateGoalMappingAction')->name('affiliateGoalMapping');
                Route::get('/save', 'MarketingController@saveAffiliateGoalMappingAction')->name('saveAffiliateGoalMapping');
                Route::get('/delete', 'MarketingController@deleteAffiliateGoalMappingAction')->name('deleteAffiliateGoalMapping');
                Route::post('/post-save', 'MarketingController@postSaveAffiliateGoalMappingAction')->name('postSaveAffiliateGoalMapping');
            });

            Route::group(['prefix' => 'coreg_plugin', 'as' => 'coregs.', 'permission' => ['marketing.coreg_plugin' => 'Marketing Coreg Plugin View\Edit']], function(){
                Route::get('/', 'Marketing\CoregsController@coregPluginAction')->name('list');
                Route::get('/save', 'Marketing\CoregsController@saveCoregPluginAction')->name('save');
                Route::get('/delete', 'Marketing\CoregsController@deleteCoregPluginAction')->name('delete');
                Route::post('/post-save', 'Marketing\CoregsController@postSaveCoregPluginAction')->name('post-save');
            });

            Route::group(['prefix' => 'redirect_rules_set', 'permission' => ['marketing.redirect_rules_set' => 'Marketing Redirect Rules Set View\Edit']], function() {
                Route::get('/', 'MarketingController@redirectRulesSetAction')->name('redirectRulesSet');
                Route::get('save', 'MarketingController@saveRedirectRulesSetAction')->name('saveRedirectRulesSet');
                Route::get('delete', 'MarketingController@deleteRedirectRulesSetAction')->name('deleteRedirectRulesSet');
                Route::get('delete-redirect-rule/{redirectRuleId}', 'MarketingController@deleteRedirectRuleAction')->name('deleteRedirectRule');
                Route::post('post-save', 'MarketingController@postSaveRedirectRulesSetAction')->name('postSaveRedirectRulesSet');
            });

            Route::group(['prefix' => 'transactional_email', 'as' => 'transactional_email.', 'permission' => ['marketing.transactional_email' => 'Marketing Transactional Email View\Edit']], function() {
                Route::get('/', 'TransactionalEmailController@transactionalEmailAction')->name('transactionalEmail');
                Route::any('save/{id}', 'TransactionalEmailController@saveTransactionalEmailAction')->name('saveTransactionalEmail');
                Route::get('delete/{transactionalEmailId}', 'TransactionalEmailController@deleteTransactionalEmailAction')->name('deleteTransactionalEmail');
                Route::any('test/{transactionalEmailId?}', 'TransactionalEmailController@testTransactionalEmailAction')->name('testTransactionalEmail');
            });

            Route::group(['prefix' => 'mobile_push_notifications', 'as' => 'mobile_push_notifications.', 'permission' => ['marketing.mobile_push_notifications' => 'Marketing mobile push notifications View\Switch status']], function() {
                Route::get('/', 'Marketing\MobilePushNotificationsController@mobilePushNotificationsListAction')->name('mobilePushNotificationsList');
                Route::get('status-switch/{id}/{status}', 'Marketing\MobilePushNotificationsController@switchStatusAction')->name('switchStatusForPushNotification');
            });

            Route::group(['prefix' => 'banners', 'as' => 'banners.', 'permission' => ['marketing.banners' => 'Marketing Banners View/Edit']], function() {
                Route::get('', 'BannersController@indexAction')->name('index');
                Route::any('edit/{id?}', 'BannersController@editAction')->name('edit');
                Route::any('delete/{id}/{force?}', ['uses' => 'BannersController@deleteAction', 'as' => 'delete'])
                    ->where('force', '^force$');
            });
        });

        // Admin - Call Center Routes
        Route::group(['prefix' => 'call-center', 'as' => 'callcenter.', 'permission' => ['callcenter.view' => 'Call Center View']], function() {
            Route::get('edumax', 'CallCenterController@searchAction')->name('edumaxSearch');
            Route::get('export', 'CallCenterController@exportAction')->name('exportList');
        });

        // Admin - Package Routes
        Route::group(['prefix' => 'packages', 'as' => 'packages.', 'permission' => ['packages.view' => 'Packages View']], function() {
            Route::get('/', 'PackagesController@indexAction')->name('index');
            Route::get('search', 'PackagesController@searchAction')->name('search');

            Route::group(['permission' => ['packages.edit' => 'Packages Edit']], function() {
                Route::get('save', 'PackagesController@saveAction')->name('save');
                Route::get('activate', 'PackagesController@activateAction')->name('activate');
                Route::get('activate_mobile', 'PackagesController@activateMobileAction')->name('activateMobile');
                Route::get('deactivate', 'PackagesController@deactivateAction')->name('deactivate');
                Route::get('deactivate_mobile', 'PackagesController@deactivateMobileAction')->name('deactivateMobile');
                Route::get('mark', 'PackagesController@markAction')->name('mark');
                Route::get('mark_mobile', 'PackagesController@markMobileAction')->name('markMobile');
                Route::get('unmark', 'PackagesController@unmarkAction')->name('unmark');
                Route::get('unmark_mobile', 'PackagesController@unmarkMobileAction')->name('unmarkMobile');
                Route::get('batch-subscription', 'PackagesController@batchSubscriptionAction')->name('batchSubscription');
                Route::post('post-save', 'PackagesController@postSaveAction')->name('postSave');
                Route::post('post-batch-subscription', 'PackagesController@postBatchSubscriptionAction')->name('postBatchSubscription');
            });
        });

        // Admin - Missions Routes
        Route::group(['prefix' => 'missions', 'as' => 'missions.', 'permission' => ['missions.view' => 'Missions View']], function() {
            Route::get('/', 'MissionController@indexAction')->name('index');
            Route::get('search', 'MissionController@searchAction')->name('search');
            Route::get('progress', 'MissionController@progressAction')->name('progress');

            Route::group(['permission' => ['missions.edit' => 'Missions Edit']], function() {
                Route::get('save', 'MissionController@saveAction')->name('save');
                Route::get('activate', 'MissionController@activateAction')->name('activate');
                Route::get('deactivate', 'MissionController@deactivateAction')->name('deactivate');
                Route::get('accomplishments', 'MissionController@accomplishmentsAction')->name('accomplishments');
                Route::get('delete-goal/{missionGoalId}', 'MissionController@deleteMissionGoalAction')->name('deleteMissionGoal');
                Route::post('post-save', 'MissionController@postSaveAction')->name('postSave');
            });
        });

        // Admin - Refer A Friend
        Route::group(['prefix' => 'refer-a-friend', 'as' => 'refer-a-friend.', 'permission' => ['refer-a-friend.view' => 'Refer a Friend View']], function() {
            Route::get('/', 'ReferAFriendController@indexAction')->name('index');
            Route::get('search', 'ReferAFriendController@searchAction')->name('search');
            Route::get('share-report', 'ReferAFriendController@shareReportAction')->name('shareReport');

            Route::group(['permission' => ['awards.view' => 'Awards View']], function() {
                Route::get('awards', 'ReferAFriendController@awardsAction')->name('awards');
                Route::get('awards/history', 'ReferAFriendController@awardsHistoryAction')->name('awardsHistory');
            });

            Route::group(['permission' => ['refer-a-friend.edit' => 'Refer a Friend Edit']], function() {
                Route::get('awards/save', 'ReferAFriendController@saveAwardAction')->name('saveAward');
                Route::get('awards/activate', 'ReferAFriendController@activateAwardAction')->name('activateAward');
                Route::get('awards/deactivate', 'ReferAFriendController@deactivateAwardAction')->name('deactivateAward');
                Route::post('awards/post-save', 'ReferAFriendController@postSaveAwardAction')->name('postSaveAward');
            });
        });

        // Admin - Transaction Routes
        Route::group(['prefix' => 'transactions', 'as' => 'transactions.', 'permission' => ['transactions.view' => 'Transactions View']], function() {
            Route::get('/', 'TransactionController@indexAction')->name('index');
            Route::get('search', 'TransactionController@searchAction')->name('search');
            Route::get('view', 'TransactionController@viewAction')->name('view');

            Route::group(['permission' => ['transactions.edit' => 'Transactions Edit']], function() {
                Route::get('change-status', 'TransactionController@changeStatusAction')->name('changeStatus');
                Route::post('post-change-status', 'TransactionController@postChangeStatusAction')->name('postChangeStatus');
            });
        });

        // Admin Payments Routes
        Route::group(['prefix' => 'payments', 'as' => 'payments.', 'permission' => ['payments.view' => 'Payments View']], function() {
            Route::group(['prefix' => 'braintree', 'as' => 'braintree.', 'permission' => ['payments.braintree' => 'Braintree Accounts View\Edit']], function() {
                Route::get('/', 'PaymentsController@braintreeAccountsAction')->name('index');
                Route::any('/saveDefault', 'PaymentsController@saveBraintreeDefaultSetting')->name('saveDefault');
                Route::any('/saveAccount', 'PaymentsController@saveBraintreeAccount')->name('saveAccount');
            });

            Route::group(['prefix' => 'payment_methods', 'as' => 'payment_methods.'], function() {
                Route::get('/', 'PaymentsMethodController@paymentMethodsAction')->name('index');
                Route::any('/edit/{id}', 'PaymentsMethodController@editPaymentMethodsAction')->name('edit');
            });
        });


        // Admin - Export Routes
        Route::group(['prefix' => 'export', 'as' => 'export.', 'permission' => ['export' => 'Export']], function() {
            Route::get('accounts', 'ExportController@accountsAction')->name('accounts');
            Route::get('applications', 'ExportController@applicationsAction')->name('applications');
            Route::get('scholarships', 'ExportController@scholarshipsAction')->name('scholarships');
            Route::get('scholarships_public', 'ExportController@scholarshipsPublicAction')->name('scholarships_public');
            Route::get('transactions', 'ExportController@transactionsAction')->name('transactions');
            Route::get('daily-management', 'ExportController@dailyManagementAction')->name('dailyManagement');
            Route::get('marketing-system', 'ExportController@marketingSystemAction')->name('marketingSystem');
            Route::get('ab_tests_accounts/{id?}', 'ExportController@abTestsAccountsAction')->name('abTestsAccounts');
            Route::get('refer-a-friend', 'ExportController@referAFriendAction')->name('referAFriend');
            Route::get('missions-progress', 'ExportController@missionsProgressAction')->name('missionsProgress');
            Route::get('affiliates-responses', 'ExportController@affiliatesResponsesAction')->name('affiliatesResponses');
            Route::get('submissions', 'ExportController@submissionsAction')->name('submissions');
            Route::get('share-report', 'ExportController@shareReportAction')->name('shareReport');
            Route::get('edumax', 'ExportController@edumaxAction')->name('edumax');
            Route::get('call-center', 'ExportController@callCenterAction')->name('callCenter');
        });

        // Admin - Statistics Routes
        Route::group(['prefix' => 'statistics', 'as' => 'statistics.', 'permission' => ['statistics' => 'Statistics']], function() {
            Route::get('/', 'StatisticController@indexAction')->name('index');
            Route::get('daily-management', 'StatisticController@dailyManagementStatisticAction')->name('dailyManagementStatistic');
            Route::get('customer-report', 'StatisticController@customerDataReportAction')->name('customerDataReport');
            Route::get('/api/conversion', 'Api\DailyConversionController@conversion')->name('conversion');
        });

        // Admin - Website Routes
        Route::group(['prefix' => 'website', 'as' => 'website.', 'permission' => ['website' => 'Website Settings Edit']], function() {
            Route::get('/', 'WebsiteController@indexAction')->name('index');
            Route::get('settings', 'WebsiteController@settingsAction')->name('settings');
            Route::get('/clear-cache', 'WebsiteController@clearCache')->name('clear-cache');
            Route::get('mail-template', 'WebsiteController@mailTemplateAction')->name('mailTemplate');
            Route::any('commands', 'WebsiteController@commands')->name('commands');
            Route::post('post-settings', 'WebsiteController@postSettingsAction')->name('postSettings');
        });

        Route::group(['as' => 'static-data.', 'permission' => ['static-data' => 'Static Data View']], function() {
            Route::get('/static_data',  ['uses' => 'WebsiteController@staticDataAction', 'as' => 'staticData']);
            Route::get('/universities', ['uses' => 'UniversityController@indexAction',   'as' => 'universities']);
            Route::get('/highschools',  ['uses' => 'HighschoolController@indexAction',   'as' => 'highschools']);
            Route::get('/website/account_fields', ['uses' => 'WebsiteController@accountFieldsAction', 'as' => 'accountFields']);
        });

        // Admin - Cms Routes
        Route::group(['prefix' => 'cms', 'as' => 'cms.', 'permission' => ['cms' => 'Cms']], function() {
            Route::get('pages', 'CmsController@pagesAction')->name('pages');
            Route::any('create', 'CmsController@createAction')->name('create');
            Route::any('edit/{id}', 'CmsController@editAction')->name('edit');

            Route::group(['prefix' => 'special-offer-pages', 'as' => 'special-offer-pages.', 'permission' => ['cms' => 'Cms']], function() {
                Route::get('/', 'SpecialOfferPagesController@indexAction')->name('index');
                Route::any('edit/{id?}', 'SpecialOfferPagesController@editAction')->name('edit');
                Route::any('delete/{id}', 'SpecialOfferPagesController@deleteAction')->name('delete');
            });
        });

        Route::group(['prefix' => 'notification', 'as' => 'notification.', 'permission' => ['notification' => 'Notifications']], function() {
            Route::get('/{app}', 'NotificationController@index')->name('index');
            Route::any('/{app}/{type}', 'NotificationController@edit')->name('edit');
        });

        Route::group(['prefix' => 'pages', 'as' => 'pages.', 'permission' => ['cms' => 'Cms']], function() {
            Route::any('edit/{id?}', 'CmsController@editPageAction')->name('edit');
            Route::any('delete/{id}', 'CmsController@deletePageAction')->name('delete');
        });

        // Admin - Popup Routes
        Route::group(['prefix' => 'popup', 'as' => 'popup.', 'permission' => ['popup' => 'Popup']], function() {
            Route::get('/', 'PopupController@indexAction')->name('index');
            Route::get('search', 'PopupController@searchAction')->name('search');
            Route::get('save', 'PopupController@saveAction')->name('save');
            Route::post('post-save', 'PopupController@postSaveAction')->name('postSave');
            Route::get('delete', 'PopupController@deleteAction')->name('delete');
        });

        // Admin - Cms Routes
        Route::group(['prefix' => 'priorities', 'as' => 'priorities.', 'permission' => ['priorities' => 'Priorities']], function() {
            Route::get('missiongoals', 'PrioritiesController@missiongoalsAction')->name('missiongoals');
            Route::get('packages', 'PrioritiesController@packagesAction')->name('packages');
            Route::any('savelayout', 'PrioritiesController@saveLayoutConfigurationAction')->name('saveLayoutConfiguration');
            Route::any('savemissiongoals', 'PrioritiesController@saveMissionGoalsAction')->name('saveMissionGoals');
        });

        Route::group(['prefix' => 'logs', 'as' => 'logs.', 'permission' => ['Logs' => 'Logs']], function() {
            Route::group(['permission' => 'Log Admin Activity'], function() {
                Route::get('admin-activity', 'LogsController@adminActivityLogs')->name('adminActivity');
                Route::get('api/admin-activity', 'Api\ActivityLogRestController@index')->name('restAdminActivity');
            });
        });

        Route::group(['prefix' => 'apply-me', 'as' => 'applyme.', 'permission' => ['ApplyMe' => 'ApplyMe']], function() {
            Route::group(['prefix' => 'settings', 'as' => 'settings.'], function() {
                Route::get('/', 'ApplyMeController@getSettings')->name('index');
                Route::post('/', 'ApplyMeController@saveSettings')->name('save');
            });
        });

    });

});
