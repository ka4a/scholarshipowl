@if(isset($marketing) && $marketing->getHasOffersTransactionId())
    @if (!($freemium ?? false))
        @if($freeTrial ?? false)
            <iframe src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=40&transaction_id={{$marketing->getHasOffersTransactionId()}}" scrolling="no" frameborder="0" width="1" height="1"></iframe>
        @else
            <iframe src="https://scholarship.go2cloud.org/aff_l?offer_id=32&transaction_id={{$marketing->getHasOffersTransactionId()}}" scrolling="no" frameborder="0" width="1" height="1"></iframe>
        @endif
    @endif
@endif
