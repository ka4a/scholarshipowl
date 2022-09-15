<div class="col-xs-12 package ">
    <div class="blue-bg"></div>
    <div class="row text-center ">
        <div class="col-xs-12 col-sm-12">
            <div class="row no-gutter price-select-button-container">
                <div class="col-xs-6 col-sm-12 mod-price">
                    <div class="price">
                        <span class="priceType text-uppercase">{!! $mission->getName() !!}</span>
                        <div class="priceAmmount">
                            <span class="ammount">FREE</span>
                            <em></em>
                        </div>
                        <div class="description">{!! $mission->getPackage()->getDisplayMessage() !!}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-12 mod-select-button">
                    <div class="selectButton missionButtonContainer">
                        @if($missionStatuses[$missionId] == "completed")
                        <a href="#" class="btn btn-success btn-block vertical-center text-uppercase {!! ($missionGoalCount[$missionId] == 1)?"StartMissionButton":"MissionButton" !!}" {!! ($missionGoalCount[$missionId] == 1)?"data-goal-id=\"".$missionFirstGoalIds[$missionId]."\"":"data-mission-id=\"".$mission->getMissionId()."\"" !!}>Completed</a>
                        @else
                        <a href="#" class="btn btn-success btn-block vertical-center text-uppercase {!! ($missionGoalCount[$missionId] == 1)?(($missionFirstGoalTypes[$missionId] == 2)?"ReferralMissionButton":"StartMissionButton"):"MissionButton" !!}" {!! ($missionGoalCount[$missionId] == 1)?"data-goal-id=\"".$missionFirstGoalIds[$missionId]."\"":"data-mission-id=\"".$mission->getMissionId()."\"" !!} {!! ($missionGoalCount[$missionId] == 1)?(($missionFirstGoalTypes[$missionId] == 2)?"data-goal-description=\"".rawurlencode($missionFirstGoalRedirectDescriptions[$missionId])."\"":""):"" !!}>Free Upgrade</a>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 no-gutter-this selectWrapper">
                    <ul class="list-group hidden-xs">
                        @foreach (explode(PHP_EOL, $mission->getPackage()->getDisplayDescription()) as $item)
                        <li class="list-group-item">
                            {!! $item !!}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
