<h4 class="sr-only sr-only-focusable">Account</h4>
<form action="post-account" method="post" class="ajax_from">
    {{ Form::token() }}

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group col-sm-6">
                <label for="email">Email</label>

                <div class="input-group">
                    <label for="email" class="sr-only">Email</label>
                    {{ Form::text("email", $user->getEmail(), array("placeholder" => "Email", "required" => "", "class" => "form-control")) }}
                </div>

                <small data-error="email" class="help-block" style="display: none;"></small>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="password">Password</label>

        <div class="input-group">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" class="form-control"/>
        </div>

        <small data-error="password" class="help-block" style="display: none;"></small>
    </div>

    <div class="form-group col-sm-6">
        <label for="retype_password">Retype Password</label>

        <div class="input-group">
            <label for="retype_password" class="sr-only">Email</label>
            <input type="password" name="password_confirmation" class="form-control"/>
        </div>

        <small data-error="retype_password" class="help-block" style="display: none;"></small>
    </div>

    <div class="clearfix"></div>

    <div class="form-group col-sm-6 saveProfileChanges">
        <a class="btn btn-primary btn-block text-uppercase SaveProfile mod-user-profile-btn" href="#"
           data-toggle="modal" data-target="#saveModal">save changes</a>
    </div>

    <div class="clearfix"></div>

    <footer>
        <div class="row summaryText">
            <div class="col-sm-12">
                <div class="col-sm-12">
                    <h4>Social Accounts</h4>
                    @if (!$socialAccount)
                        <p>Connect to Facebook to make logging in to ScholarshipOwl easier</p>

                        <div class="row">
                            <div class="col-sm-6">
                                <a href="/fb-redirect" class="facebook-button">
                                    <div class="facebook-button-bg">
                                        <img src="/assets/img/my-account/facebook-f.png"
                                             class="facebook-f">
                                        Connect to Facebook
                                    </div>
                                </a>
                            </div>
                        </div>
                    @else
                        <p>
                            <img src="/assets/img/my-account/f-logo-blue.png" class="facebook-logo-blue">
                            Connected
                            <a href="{{ $socialAccount->getLink() }}" target="_blank">
                                {{ $socialAccount->getLink() }}
                            </a>
                        </p>

                        <div class="row" style="display: none;">
                            <div class="col-sm-6">
                                <a href="/fb-disconnect">
                                    Disconnect
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</form>
