@extends("admin/base")
@section("content")

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-name">
                        <i class="fa fa-calendar-o"></i>
                        <span>Results ({{ $count }})</span>
                    </div>

                    <div class="box-icons">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="expand-link">
                            <i class="fa fa-expand"></i>
                        </a>
                    </div>

                    <div class="no-move"></div>
                </div>

                <div class="box-content">
                    <div class="table-scroll">
                        <table class="table table-hover table-striped table-bordered table-heading">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Domain</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Last active Membership ID</th>
                                <th>Membership name</th>
                                <th>Membership status</th>
                                <th>Membership free trial</th>
                                <th>Membership initial purchase date</th>
                                <th>Last renewal date</th>
                                <th>Upcoming renewal date</th>
                                <th>Decline reason</th>
                                <th>Payment Processor</th>
                                <th>Login days</th>
                                <th>Last login date</th>
                                <th># of submitted scholarships</th>
                                <th># of submitted scholarships with requirement</th>
                                <th>Last scholarship submission date</th>
                                <th># of times logged in for the current month</th>
                                <th># of times logged in for the immediate prior month</th>
                                <th>Date last essay submitted</th>
                                <th>Last amount paid</th>
                                <th># of Essays Submitted</th>
                                <th># of Scholarships Eligible</th>
                                <th>Credit card Type</th>
                                <th>Consent to be called</th>
                                <th>Call1</th>
                                <th>Call2</th>
                                <th>Call3</th>
                                <th>Call4</th>
                                <th>Call5</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accounts as $account)
                                <tr>
                                    <td>{{ $account["account"]->getAccountId() }}</td>
                                    <td>{{ $account['account']->getDomain() }}</td>
                                    <td>{{ $account["account"]->getProfile()->getFirstName() }}</td>
                                    <td>{{ $account["account"]->getProfile()->getLastName() }}</td>
                                    <td>{{ $account["account"]->getProfile()->getPhone() }}</td>
                                    <td>{{ $account["account"]->getEmail() }}</td>
                                    <td>{{ $account["subscription"]?$account["subscription"]->getSubscriptionId():"" }}</td>
                                    <td>{{ $account["subscription"]?$account["subscription"]->getName():"" }}</td>
                                    <td>{{ $account["subscription"]?$account["subscription"]->getSubscriptionStatus()->getName():"" }}</td>
                                    <td>{{ ($account["subscription"] && $account["subscription"]->isFreeTrial()) ? 'Yes' : 'No'  }}</td>
                                    <td>{{ $account["subscription"]?$account["subscription"]->getStartDate()->format("Y-m-d"):"" }}</td>
                                    <td>{{ $account["transaction"]?$account["transaction"]->getCreatedDate()->format("Y-m-d"):"" }}</td>
                                    <td>{{ $account["subscription"]?($account["subscription"]->getEndDate()?$account["subscription"]->getEndDate()->format("Y-m-d"):""):"" }}</td>
                                    <td>{{ $account["transaction"]?$account["transaction"]->getFailedReason():"" }}</td>
                                    <td>{{ $account["transaction"]?$account["transaction"]->getPaymentMethod()->getName():"" }}</td>
                                    <td>{{ $account["loginCount"] }}</td>
                                    <td>{{ $account["lastLogin"]->first()->getActionDate() }}</td>
                                    <td>{{ isset($applicationsCount[$account["account"]->getAccountId()])?$applicationsCount[$account["account"]->getAccountId()]:"0" }}</td>
                                    <td>{{ isset($applicationsWithRequirementsCount[$account["account"]->getAccountId()])?$applicationsWithRequirementsCount[$account["account"]->getAccountId()]:"0" }}</td>
                                    <td>{{ isset($account["lastApplication"])?$account["lastApplication"]->getDateApplied()->format("Y-m-d"):"" }}</td>
                                    <td>{{ $account["loginCountMonth"]?$account["loginCountMonth"]:"0" }}</td>
                                    <td>{{ $account["loginCountPreviousMonth"]?$account["loginCountPreviousMonth"]:"0" }}</td>
                                    <td>{{ isset($lastEssaySubmitted[$account["account"]->getAccountId()])?$lastEssaySubmitted[$account["account"]->getAccountId()]:"" }}</td>
                                    <td>{{ $account["transaction"]?"$".$account["transaction"]->getAmount():"" }}</td>
                                    <td>{{ $account["textCount"]?$account["textCount"]:"0" }}</td>
                                    <td>{{ $account["scholarshipCount"]?$account["scholarshipCount"]:"0" }}</td>
                                    <td>{{ $account["transaction"]?$account["transaction"]->getCreditCardType():"" }}</td>
                                    <td>{{ $account["agreeCall"]?"Yes":"No" }}</td>
                                    <td>{{ $account["onboardingCalls"][0]?($account["onboardingCalls"][0]->getCall1()?"No":"Yes"):"No" }}</td>
                                    <td>{{ $account["onboardingCalls"][0]?($account["onboardingCalls"][0]->getCall2()?"No":"Yes"):"No" }}</td>
                                    <td>{{ $account["onboardingCalls"][0]?($account["onboardingCalls"][0]->getCall3()?"No":"Yes"):"No" }}</td>
                                    <td>{{ $account["onboardingCalls"][0]?($account["onboardingCalls"][0]->getCall4()?"No":"Yes"):"No" }}</td>
                                    <td>{{ $account["onboardingCalls"][0]?($account["onboardingCalls"][0]->getCall5()?"No":"Yes"):"No" }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
                </div>
            </div>
        </div>
    </div>


@stop
