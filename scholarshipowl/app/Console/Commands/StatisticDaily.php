<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use ScholarshipOwl\Data\Service\Statistic\StatisticDailyService;


class StatisticDaily extends Command {

    protected $signature = 'statistic:daily {--date=now : Provide date for statistics. Format: YYYY-MM-DD}';

    protected $description = "Fills daily statistics data into statistic_daily table.";

	public function handle(){
        $date = new \DateTime($this->option('date'));

		$this->info(sprintf('StatisticDaily Started (for: %s): %s', $date->format('Y-m-d'), date("Y-m-d h:i:s")));

        $statisticDailyService = new StatisticDailyService($date);

        //  #1 - New Signups - statistic_daily_type_id = 1
        $statisticDailyService->saveNewAccountsStatistic();

        //  #2 - New paying customer # - statistic_daily_type_id = 2
        $statisticDailyService->saveNewPayingAccountsStatistic();

        //  #3 - Total active customers - statistic_daily_type_id = 3
        $statisticDailyService->saveTotalAccountsStatistic();

        //  #4 - Total paying customers - statistic_daily_type_id = 4
        $statisticDailyService->saveTotalPayingAccountsStatistic();

        //  #5 - Number of new customers with free applications - statistic_daily_type_id = 5
        $statisticDailyService->saveTotalLoggedAccountsStatistic();

        //  #6 - Number of new accounts with free applications sent
        $statisticDailyService->saveNumberOfNewAccountsWithFreeApplicationsSentStatistic();

        //  #7 - Number of new accounts with paid applications sent
        $statisticDailyService->saveNumberOfNewCustomersWithPaidApplicationsSentStatistic();

        //  #8 - Number of free applications sent by new customers
        $statisticDailyService->saveNumberOfFreeApplicationsSentStatistic();

        //  #9 - Number of paid applications sent by new customers
        $statisticDailyService->saveNumberOfPaidApplicationsSentStatistic();

        //  #10 - Deposited amount
        $statisticDailyService->saveDepositAmountStatistic();

        //  #11 - Number of packages sold
        $statisticDailyService->savePackagesSoldStatistic();

        //  #12 - Number of scholarship applications sold
        $statisticDailyService->saveScholarshipApplicationsSoldStatistic();

        //  #13 - Number of free applications sent
        $statisticDailyService->saveTotalNumberOfFreeApplicationsSentStatistic();

        //  #14 - Number of paid applications sent
        $statisticDailyService->saveTotalNumberOfPaidApplicationsSentStatistic();

        //  #15 - Number of corrected deposits
        $statisticDailyService->saveDepositCorrectionsStatistic();

        //  #16 - Corrected deposits amount
        $statisticDailyService->saveDepositCorrectionAmountStatistic();

        // #17 - Free trial new subscriptions
        $statisticDailyService->saveFreeTrialStatisticsNewSubscriptions();

        // #18 - Free trial first charge
        $statisticDailyService->saveFreeTrialStatistics1stCharge();

        $this->info("StatisticDaily Ended: " . date("Y-m-d h:i:s"));
	}

}
