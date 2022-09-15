<?php


namespace App\Http\Controllers\Admin;


use App\Entity\Domain;
use App\Entity\Resource\Resource;
use ScholarshipOwl\Data\Service\Marketing\EdumaxService as SearchService;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Data\Service\Payment\StatisticService as PaymentStatisticService;
use ScholarshipOwl\Http\ViewModel;

class CallCenterController extends BaseController
{
    public function searchAction()
    {
        $model = new ViewModel("admin/call-center/edumax");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Call Center" => "/admin/call-center",
                "React2media" => "/admin/call-center/export"
            ),
            "title" => "Call Center",
            "active" => "call-center",
            "accounts" => array(),
            "count" => 0,
            "search" => array(
                "domain" => Domain::SCHOLARSHIPOWL,
                "created_date_from" => "",
                "created_date_to" => "",
                "school_level_id" => array(),
                "degree_id" => array(),
                "degree_type_id" => array(),
                "has_active_subscription" => "",
                "paid" => "",
                "agree_call" => "",
            ),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/call-center/edumax",
                "url_params" => array()
            ),
            "options" => array(
                "domains" => Domain::options(),
                "subscriptions" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
                "paid_subscriptions" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
                "school_levels" => InfoServiceFactory::getArrayData("SchoolLevel"),
                "degrees" => InfoServiceFactory::getArrayData("Degree"),
                "degree_types" => InfoServiceFactory::getArrayData("DegreeType"),
                "agree_call" => ["" => "--- Select ---", "1" => "Yes", "0" => "No"],
            )
        );

        try {
            $accountSearchService = new SearchService();
            $paymentStatisticService = new PaymentStatisticService();

            $display = 50;
            $pagination = $this->getPagination($display);

            $input = $this->getAllInput();
            unset($input["page"]);
            foreach ($input as $key => $value) {
                $data["search"][$key] = $value;
            }

            $searchResult = $accountSearchService->searchAccounts($data["search"], $pagination["limit"]);
            if (!empty($searchResult["data"])) {
                $accountIds = array_keys($searchResult["data"]);
                if (!empty($accountIds)) {
                    $data["subscriptions"] = $paymentStatisticService->getTopPrioritySubscriptions($accountIds);
                }
            }

            $doctrineAccounts = [];
            foreach ($searchResult["data"] as $result) {
                $doctrineAccounts[] = new Resource(\EntityManager::findById(
                    \App\Entity\Account::class, $result->getAccountId()
                ), true);
            }

            $data["accounts"] = $doctrineAccounts;
            $data["count"] = $searchResult["count"];
            $data["pagination"]["page"] = $pagination["page"];
            $data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
            $data["pagination"]["url_params"] = $data["search"];

        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }

    public function exportAction()
    {
        $model = new ViewModel("admin/call-center/export");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Call Center" => "/admin/call-center",
                "Export" => "/admin/call-center/export"
            ),
            "title" => "Export",
            "active" => "call-center",
            "accounts" => array(),
            "count" => 0,
        );

        $model->setData($data);
        return $model->send();
    }
}