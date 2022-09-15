<h4 class="sr-only sr-only-focusable">Active membership</h4>

<div class="membership">

    <h4 class="text-light text-left text-blue">Active membership</h4>
    <div class="panel panel-default text-left">
        <div class="panel-heading">
            <h3 class="panel-title text-capitalize text-bold pull-left">
                {{ $subscription->getName() ?: 'None' }}
            </h3>
            @if($subscriptionEntity && $subscriptionEntity->isFreeTrial())
                <span class="free-trial-info">free trial until {{ $subscriptionEntity->getFreeTrialEndDate()->format('M j, Y') }}</span>
            @endif
            <h3 class="panel-title text-capitalize text-bold pull-right">
                @if($subscriptionEntity)
                @if($subscriptionEntity->isRecurrent())
                    ${{ $subscription->getPrice()/$subscription->getExpirationPeriodValue() }}<sup>/{{ str_limit($subscription->getExpirationPeriodType(), 2, "") }}</sup>
                @elseif ((int) $subscriptionEntity->getPrice() === 0 )
                    Free
                @else
                    ${{ $subscription->getPrice() }}
                @endif
                @endif
            </h3>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            @if($subscriptionEntity && $subscriptionEntity->isRecurrent())
                <p class="pull-left">Billing:</p>
                <p class="pull-right text-blue">
                    ${{ $subscriptionEntity->getPrice() }} billed every {{ $subscriptionEntity->getExpirationPeriod() }}
                </p>
                <div class="clearfix"></div><hr>
                <p class="pull-left">Next renewal date:</p>
                <p class="pull-right text-blue">{{ $subscriptionEntity->getRenewalDate()->format('m/d/Y') }}</p>
                <div class="clearfix"></div><hr>
            @else
                @if($subscription->getName())
                    @if ($subscriptionEntity && $subscriptionEntity->getStartDate())
                        <p class="pull-left">Start date:</p>
                        <p class="pull-right text-blue">{{ $subscriptionEntity->getStartDate()->format('m/d/Y') }}</p>
                        <div class="clearfix"></div>
                        <hr>
                    @endif
                    @if ($subscriptionEntity->getEndDate())
                        <p class="pull-left">End date:</p>
                        @if ($subscriptionEntity->getExpirationType() === \App\Entity\Package::EXPIRATION_TYPE_NO_EXPIRY)
                            <p class="pull-right text-blue">Open-ended</p>
                        @else
                            <p class="pull-right text-blue">{{ $subscriptionEntity->getEndDate()->format('m/d/Y') }}</p>
                        @endif
                        <div class="clearfix"></div>
                        <hr>
                    @endif
                @endif
                <p class="pull-left">Allowed scholarship applications:</p>
                @if ($subscriptionEntity && $subscriptionEntity->isFreemium())
                    @if ($subscriptionEntity->getFreemiumRecurrenceValue() === 1)
                        <p class="pull-right text-blue">{{ sprintf('%s per %s', $subscriptionEntity->getFreemiumCredits(), $subscriptionEntity->getFreemiumRecurrencePeriod()) }}</p>
                    @else
                        <p class="pull-right text-blue">{{ sprintf('%s per %s %ss', $subscriptionEntity->getFreemiumCredits(), $subscriptionEntity->getFreemiumRecurrenceValue(), $subscriptionEntity->getFreemiumRecurrencePeriod()) }}</p>
                    @endif
                @else
                    <p class="pull-right text-blue">{{ $subscription->isScholarshipsUnlimited()?"UNLIMITED":$subscription->getScholarshipsCount() }}</p>
                @endif
                <div class="clearfix"></div>
            @endif
        </div>
    </div>

    @if($subscriptionEntity && $subscriptionEntity->isRecurrent())
        <div class="subscription-cancel-container" data-free-trial="{!! $subscriptionEntity->isFreeTrial() !!}">
            @if($subscriptionEntity->getSubscriptionAcquiredType()->is(\App\Entity\SubscriptionAcquiredType::PURCHASED))
                @if( $subscription->getRemoteStatus() ===  \App\Entity\Subscription::ACTIVE )
                    <div class="text-left text-light" id="subscription_active">{!! $activeText !!}</div>
                    <div class="text-left text-light" id="subscription_suspended" style="display: none;">{!! $cancelText !!}<br/><br/></div>
                @elseif( $subscription->getRemoteStatus() ==  \App\Entity\Subscription::CANCELLED )
                    <div class="text-left text-light">{!! $cancelText !!}<br/><br/></div>
                @endif
            @endif
        </div>
    @endif

    <p class="text-left text-light">For any queries about your membership or ScholarshipOwl, please write  an email to <a href="mailto:contact@scholarshipowl.com">Contact@ScholarshipOwl.com</a> </p>
</div>
@section("popups")
    @include ('includes.payment-missions-direct')
    @if ($subscriptionEntity)
        @include('popup.cancel-membership', ['subscription' => $subscriptionEntity])
    @endif
@endsection
