<!-- Checkout popup modal -->
<div id="payment-popup-direct-mission" class="modal fade in payment-popups" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="payment-popup" aria-hidden="true" data-show-zero="0">
    <div id="payment-mission-direct" class="modal-dialog container">
        <div class="modal-content row text-center">
            <div class="navbar hidden">
                <div class="navbar-inner">
                    <ul class="nav nav-pills">
                        <li><a href="#missionTab" data-toggle="tab" data-step="4">Missions</a></li>
                    </ul>
                </div>
            </div>

            <!-- TAB CONTENT -->
            <div class="tab-content">
                <!-- MISSIONS DIRECT -->
                <div id="missionTab" class="tab-pane fade in">
                    <div class="modal-header clearfix">
                        <button type="button" class="close img-circle text-center" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">Close</span>
                        </button>
                    </div>

                    <div class="modal-body col-xs-12 text-left clearfix missionTable">
                        <div class="row" id="MissionOptionsContainer">
                            <div class="col-xs-12">
                                @foreach ($missionPackages as $missionId => $mission)
                                    <div class="MissionOptions hidden" data-mission-id="{!! $mission->getMissionId() !!}">
                                        <div class="row clearfix">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <big class="bold">{!! $mission->getName() !!}</big>

                                                    <p class="getMessage">
                                                        {!! $mission->getDescription() !!}
                                                    </p>
                                                </div>
                                                <p class="completedGoals">

                                                </p>
                                            </div>

                                            <div class="col-xs-12 MissionOptionsDiv" data-url="/api/v1.0/missions?mission={!! $mission->getMissionId() !!}" data-method="get" data-mission-id="{!! $mission->getMissionId() !!}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /MISSIONS DIRECT -->
            </div>
            <!-- / tab content -->
        </div>
    </div>
</div>
