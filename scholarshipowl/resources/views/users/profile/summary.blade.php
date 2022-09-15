<h4 class="sr-only sr-only-focusable">Summary</h4>
@if ($subscription->isEmpty())
    <!-- Free -->

    <div id="totalApps" class="free row center-block no-gutter">
        <div class="col-xs-6 col-sm-4 text-center">
            <div class="summary-icon img-responsive center-block">
                <a href="{{ url_builder('select') }}">
                    <img class="img-circle img-responsive" src="assets/img/schollaship-icon.png">
                    <span class="badge blue-bg"><big>{{ $eligibility_count }}</big></span>
                </a>
            </div>
            <p class="summaryText">
                <a href="{{ url_builder('select') }}">
                    Scholarships
                </a> you are eligible for
            </p>
        </div>
        <div class="col-xs-6 col-sm-4 text-center">
            <div class="summary-icon center-block">
                <a href="/scholarships#sent">
                    <img class="img-circle img-responsive" src="assets/img/applications-icon.png">
                    <span class="badge blue-bg">
                        <big class="submitted">{{ $submitted_applications_count }}</big><small class="total-apps">/{{ $applications_count }}</small>
                    </span>
                </a>
            </div>
            <p class="summaryText">
                <a href="/scholarships#sent">Applications</a> submitted
            </p>
        </div>
        <div class="col-xs-12 col-sm-4 text-center">
            <div class="mail-notification-wrapper summary-icon center-block">
                <a href="{{ url_builder('mailbox') }}">
                <img class="img-circle img-responsive" src="assets/img/messages-icon.png">
                <span class="badge blue-bg">
                    <big class="submitted unread-messages"></big><small class="total-apps total-messages"></small>
                </span>
                </a>
            </div>
            <p class="summaryText">
                <a href="{{ url_builder('mailbox') }}">Messages</a> unread
            </p>
        </div>

    </div>
@else
    <!-- Paid -->

    <div id="totalApps" class="free row center-block no-gutter">

        <div class="col-xs-6 col-sm-4 text-center">
            <div class="summary-icon img-responsive center-block">
                <a href="{{ url_builder('select') }}">
                    <img class="img-circle img-responsive" src="assets/img/schollaship-icon.png">
                    <span class="badge blue-bg"><big>{{ $eligibility_count }}</big></span>
                </a>
            </div>
            <p class="summaryText">
                <a href="{{ url_builder('select') }}">
                    Scholarships
                </a> you are eligible for
            </p>
        </div>
        <div class="col-xs-6 col-sm-4 text-center">
            <div class="summary-icon center-block">
                <a href="/scholarships#sent">
                    <img class="img-circle img-responsive" src="assets/img/applications-icon.png">
                    <span class="badge blue-bg">
                        <big class="submitted">{{ $submitted_applications_count }}</big><small class="total-apps">/{{ $applications_count }}</small>
                    </span>
                </a>
            </div>
            <p class="summaryText">
                <a href="/scholarships#sent">Applications</a> submitted
            </p>
        </div>
        <div class="col-xs-12 col-sm-4 text-center">
            <div class="mail-notification-wrapper summary-icon center-block">
                <a href="{{ url_builder('mailbox') }}">
                    <img class="img-circle img-responsive" src="assets/img/messages-icon.png">
                <span class="badge blue-bg">
                    <big class="submitted unread-messages"></big><small class="total-apps total-messages"></small>
                </span>
                </a>
            </div>
            <p class="summaryText">
                <a href="{{ url_builder('mailbox') }}">Messages</a> unread
            </p>
        </div>

    </div>
@endif

    <div id="eligibility-info" class="row">
        <div class="col-xs-12 summaryText text-center ">
            <p>You are eligible for <span class="text-warning">{{ $eligibility_count }}</span> scholarships</p>
        </div>
    </div>

    <div id="upgrade-btn" class="row">
        <div class="col-xs-12 col-sm-6 text-center">
            <a id="upgrade" href="{{ !$isMobile ? "#" : url_builder('upgrade-mobile') }}" class="btn btn-warning btn-block center-block text-uppercase pull-left mod-user-profile-btn {{ !$isMobile ? "GetMoreScholarshipsButton" : "" }}">
                Upgrade
            </a>
        </div>
        <div class="col-xs-12 col-sm-6 text-center">
            <a id="my-applications" href="/scholarships#sent" class="pull-right btn btn-primary btn-block center-block text-uppercase mod-user-profile-btn">My applications</a>
        </div>
    </div>

    <footer>
        <div class="row summaryText">
            <div class="col-sm-4">
                <div>
                    <a href="{{ url_builder('mailbox') }}">Messages</a> <span class="text-warning">(<span class="unread-messages"></span>)</span>
                </div>
                <div>
                    <a href="{{ url_builder('select') }}">Discover more scholarships</a> <span class="text-warning">({{ $eligibility_count }})</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="invisible">
                    Collages Student loans
                </div>
            </div>
            <div class="col-sm-4">
                <div class="invisible">Text book exchange</div>
            </div>
        </div>
    </footer>


