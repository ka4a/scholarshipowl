<?php

/**
 * IDDL
 *
 * @package     ScholarshipOwl\Data\Service
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created        07. October 2014.
 * @copyright    Sirio Media
 */

namespace ScholarshipOwl\Data\Service;


interface IDDL
{
    // Account Tables
    const TABLE_ACCOUNT = "account";
    const TABLE_ACCOUNT_TYPE = "account_type";
    const TABLE_ACCOUNT_STATUS = "account_status";
    const TABLE_CONVERSATION = "conversation";
    const TABLE_LOGIN_HISTORY = "login_history";
    const TABLE_FORGOT_PASSWORD = "forgot_password";
    const TABLE_PROFILE = "profile";
    const TABLE_REFERRAL = "referral";
    const TABLE_REFERRAL_AWARD = "referral_award";
    const TABLE_REFERRAL_AWARD_TYPE = "referral_award_type";
    const TABLE_REFERRAL_AWARD_ACCOUNT = "referral_award_account";
    const TABLE_REFERRAL_SHARE = "referral_share";
    const TABLE_REFERRAL_AWARD_SHARE = "referral_award_share";
    const TABLE_ACCOUNT_LOGIN_TOKEN_TABLE = "account_login_token";
    const TABLE_ACCOUNT_ONBOARDING_CALLS = "account_onboarding_call";
    const TABLE_ACCOUNT_FILE = "account_file";


    // Info Tables
    const TABLE_CAREER_GOAL = "career_goal";
    const TABLE_CITIZENSHIP = "citizenship";
    const TABLE_COUNTRY = "country";
    const TABLE_DEGREE = "degree";
    const TABLE_DEGREE_TYPE = "degree_type";
    const TABLE_ETHNICITY = "ethnicity";
    const TABLE_FIELD = "field";
    const TABLE_SCHOOL_LEVEL = "school_level";
    const TABLE_STATE = "state";
    CONST TABLE_HIGHSCHOOL = "highschool";
    CONST TABLE_UNIVERSITY = "university";
    CONST TABLE_MILITARY_AFFILIATION = "military_affiliation";
    const TABLE_CAPPEX_COLLEGE = "cappex_college";
    const TABLE_COLLEGE = "college";


    // Payment Tables
    const TABLE_APPLICATION = "application";
    const TABLE_APPLICATION_ESSAY = "application_essay";
    const TABLE_APPLICATION_TEXT = "application_text";
    const TABLE_APPLICATION_FILE = "application_file";
    const TABLE_APPLICATION_IMAGE = "application_image";
    const TABLE_APPLICATION_ESSAY_STATUS = "application_essay_status";
    const TABLE_APPLICATION_STATUS = "application_status";
    const TABLE_PACKAGE = "package";
    const TABLE_PACKAGE_STYLE = "package_style";
    const TABLE_PAYMENT_METHOD = "payment_method";
    const TABLE_SUBSCRIPTION = "subscription";
    const TABLE_SUBSCRIPTION_STATUS = "subscription_status";
    const TABLE_TRANSACTION = "transaction";
    const TABLE_TRANSACTION_STATUS = "transaction_status";
    const TABLE_POPUP = "popup";
    const TABLE_POPUP_CMS = "popup_cms";


    // Scholarship Tables
    const TABLE_ELIGIBILITY = "eligibility";
    const TABLE_ESSAY = "essay";
    const TABLE_FORM = "form";
    const TABLE_SCHOLARSHIP = "scholarship";
    const TABLE_REQUIREMENT_FILE = "requirement_file";
    const TABLE_REQUIREMENT_IMAGE = "requirement_image";
    const TABLE_REQUIREMENT_NAME = "requirement_name";
    const TABLE_REQUIREMENT_TEXT = "requirement_text";


    // Email Tables
    const TABLE_EMAIL = "email";
    const TABLE_EMAIL_ATTACHMENT = "email_attachment";
    const TABLE_MAILCHIMP_SKIP = "mailchimp_skip";

    // Statistic Tables
    const TABLE_STATISTIC_DAILY = "statistic_daily";
    const TABLE_STATISTIC_DAILY_TYPE = "statistic_daily_type";


    // Mission Tables
    const TABLE_MISSION = "mission";
    const TABLE_MISSION_ACCOUNT = "mission_account";
    const TABLE_MISSION_GOAL = "mission_goal";
    const TABLE_MISSION_GOAL_ACCOUNT = "mission_goal_account";
    const TABLE_MISSION_GOAL_TYPE = "mission_goal_type";


    // Marketing Tables
    const TABLE_AB_TEST = "ab_test";
    const TABLE_AB_TEST_ACCOUNT = "ab_test_account";
    const TABLE_AFFILIATE = "affiliate";
    const TABLE_AFFILIATE_GOAL = "affiliate_goal";
    const TABLE_AFFILIATE_GOAL_MAPPING = "affiliate_goal_mapping";
    const TABLE_AFFILIATE_GOAL_RESPONSE = "affiliate_goal_response";
    const TABLE_AFFILIATE_GOAL_RESPONSE_DATA = "affiliate_goal_response_data";
    const TABLE_ACCOUNT_HASOFFERS_FLAG = "account_hasoffers_flag";
    const TABLE_COREG_PLUGINS = "coreg_plugins";
    const TABLE_COREG_PLUGIN_ALLOCATION = "coreg_plugin_allocation";
    const TABLE_DANE_MEDIA_CAMPAIGN = "dane_media_campaign";
    const TABLE_DANE_MEDIA_CAMPAIGN_ALLOCATION = "dane_media_campaign_allocation";
    const TABLE_DANE_MEDIA_CAMPUS = "dane_media_campus";
    const TABLE_DANE_MEDIA_PROGRAM = "dane_media_program";
    const TABLE_MARKETING_SYSTEM = "marketing_system";
    const TABLE_MARKETING_SYSTEM_ACCOUNT = "marketing_system_account";
    const TABLE_MARKETING_SYSTEM_ACCOUNT_DATA = "marketing_system_account_data";
    const TABLE_REDIRECT_RULE = "redirect_rule";
    const TABLE_REDIRECT_RULES_SET = "redirect_rules_set";
    const TABLE_TRANSACTIONAL_EMAIL = "transactional_email";
    const TABLE_TRANSACTIONAL_EMAIL_SEND = "transactional_email_send";
    const TABLE_ZU_USA_CAMPAIGN = "zu_usa_campaign";
    const TABLE_ZU_USA_CAMPAIGN_ALLOCATION = "zu_usa_campaign_allocation";
    const TABLE_ZU_USA_CAMPUS = "zu_usa_campus";
    const TABLE_ZU_USA_CAMPUS_ALLOCATION= "zu_usa_campus_allocation";
    const TABLE_ZU_USA_PROGRAM = "zu_usa_program";

    // Submissions Tables
    const TABLE_SUBMISSION = "submission";

    const TABLE_LOG_PAYMENT_MESSAGE = "log_payment_message";

    const TABLE_LOG_GTS_FORM_URL = 'log_gts_form_url';

    const TABLE_QUEUE_PAYMENT_MESSAGE = 'queue_payment_message';

    // Website Tables
    const TABLE_SETTING = "setting";


    // Cms
    const TABLE_CMS = "cms";

    // Files
    const TABLE_FILES = "files";
    const TABLE_ESSAY_FILES = "essay_files";

}
