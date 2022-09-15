<form action="payment-form" method="post">
    {!! Form::token() !!}
    {!! Form::hidden("package", "") !!}
    {!! Form::hidden("price", "") !!}
    {!! Form::hidden("account_id", $user->getAccountId()) !!}

    @foreach ($packages as $p)
        <?php $package = $p; $packageId = $p->getPackageId();?>
        @include('includes.widget-package')
    @endforeach
</form>
