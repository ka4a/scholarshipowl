@extends("admin/base")
@section("content")

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };
    $(function () {
        $("#MissionGoalsTable tbody").sortable({
            helper: fixHelper
        }).disableSelection();
    });
</script>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Mission</span>
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
				<form method="post" action="/admin/missions/post-save" class="form-horizontal ajax_form" id="SavePackageForm">
					{{ Form::token() }}
					{{ Form::hidden('mission_id', $mission->getMissionId()) }}

					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-6">
								{{ Form::text('name', $mission->getName(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Package</label>
							<div class="col-sm-3">
								{{ Form::select('package_id', $options["packages"], $mission->getPackage()->getPackageId(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>

                        <hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Start Date</label>

							<div class="col-sm-3">
								{{ Form::text('start_date', format_date($mission->getStartDate()), array("class" => "form-control date_picker")) }}
							</div>
						</div>


						<div class="form-group">
							<label class="col-sm-3 control-label">End Date</label>

							<div class="col-sm-3">
								{{ Form::text('end_date', format_date($mission->getEndDate()), array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Active</label>
							<div class="col-sm-3">
								{{ Form::select('is_active', $options["active"], $mission->isActive(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Is Visible</label>
							<div class="col-sm-3">
								{{ Form::select('is_visible', $options["visible"], $mission->isVisible(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Affiliate Goals</label>

							<div class="col-sm-9">
								<table class="table table-hover table-striped table-bordered table-heading" id="MissionGoalsTable">
									<thead>
										<tr>
                                            <th>Type</th>
											<th>Name</th>
											<th>Points</th>
											<th>Properties</th>
											<th>Active</th>
											<th>Actions</th>
										</tr>
									</thead>

									<tbody class="sorttable">
										@foreach ($mission_goals as $missionGoalId => $goalData)
                                            @if($goalData["type"] == \ScholarshipOwl\Data\Entity\Mission\MissionGoalType::AFFILIATE)
                                                <tr data-mission-goal-id="{{ $goalData['mission_goal_id'] }}" id="{{ $goalData['mission_goal_id'] }}">
                                                    <td>{{ Form::hidden('affiliate_goal_' . $missionGoalId . '_mission_goal_id', $goalData['mission_goal_id']) }}
                                                        {{ Form::hidden('affiliate_goal_' . $missionGoalId . '_type', \ScholarshipOwl\Data\Entity\Mission\MissionGoalType::AFFILIATE) }} Affiliate</td>

                                                    @if (!in_array($goalData['mission_goal_id'], array_keys($options['used_goals'])))
                                                        <td>{{ Form::text('affiliate_goal_' . $missionGoalId . '_name', $goalData['name'], array("class" => "form-control")) }}</td>
                                                        <td>{{ Form::text('affiliate_goal_' . $missionGoalId . '_points', $goalData['points'], array("class" => "form-control", "size" => "3")) }}</td>
                                                        <td>&nbsp;</td>
                                                        <td>{{ Form::checkbox('affiliate_goal_' . $missionGoalId . '_active', "1", $goalData['active']) }}</td>
                                                        <td><a class="btn btn-danger DeleteAffiliateGoalButton" data-mission-goal-id="{{ $goalData['mission_goal_id'] }}" href="#">Delete</a></td>
                                                    @else
                                                        <td>{{ Form::text('affiliate_goal_' . $missionGoalId . '_name', $goalData['name'], array("class" => "form-control")) }}</td>
                                                        <td>{{ Form::text('affiliate_goal_' . $missionGoalId . '_points', $goalData['points'], array("class" => "form-control", "size" => "3")) }}</td>
                                                        <td>&nbsp;</td>
                                                        <td>{{ Form::checkbox('affiliate_goal_' . $missionGoalId . '_active', "1", $goalData['active']) }}</td>
                                                        <td></td>
                                                    @endif
                                                </tr>
                                            @elseif($goalData["type"]  == \ScholarshipOwl\Data\Entity\Mission\MissionGoalType::REFER_A_FRIEND)
                                                <tr data-mission-goal-id="{{ $goalData['mission_goal_id'] }}"  id="{{ $goalData['mission_goal_id'] }}">
                                                    <td>{{ Form::hidden('referral_award_' . $missionGoalId . '_mission_goal_id', $goalData['mission_goal_id']) }}
                                                        {{ Form::hidden('affiliate_goal_' . $missionGoalId . '_type', \ScholarshipOwl\Data\Entity\Mission\MissionGoalType::REFER_A_FRIEND) }} Refer A Friend</td>

                                                    @if (!in_array($goalData['mission_goal_id'], array_keys($options['used_goals'])))
                                                        <td>{{ Form::text('referral_award_' . $missionGoalId . '_name', $goalData['name'], array("class" => "form-control")) }}</td>
                                                        <td>{{ Form::text('referral_award_' . $missionGoalId . '_points', $goalData['points'], array("class" => "form-control", "size" => "3")) }}</td>
                                                        <td>&nbsp;</td>
                                                        <td>{{ Form::checkbox('referral_award_' . $missionGoalId . '_active', "1", $goalData['active']) }}</td>
                                                        <td><a class="btn btn-danger DeleteReferralAwardButton" data-mission-goal-id="{{ $goalData['mission_goal_id'] }}" href="#">Delete</a></td>
                                                    @else
                                                        <td>{{ Form::text('referral_award_' . $missionGoalId . '_name', $goalData['name'], array("class" => "form-control")) }}</td>
                                                        <td>{{ Form::text('referral_award_' . $missionGoalId . '_points', $goalData['points'], array("class" => "form-control", "size" => "3")) }}</td>
                                                        <td>&nbsp;</td>
                                                        <td>{{ Form::checkbox('referral_award_' . $missionGoalId . '_active', "1", $goalData['active']) }}</td>
                                                        <td></td>
                                                    @endif
                                                </tr>
                                            @elseif($goalData["type"]  == \ScholarshipOwl\Data\Entity\Mission\MissionGoalType::ADVERTISEMENT)
                                                <tr data-mission-goal-id="{{ $goalData['mission_goal_id'] }}"  id="{{ $goalData['mission_goal_id'] }}">
                                                    <td>{{ Form::hidden('ad_mission_goal_id_' . $missionGoalId, $goalData['mission_goal_id']) }}
                                                        {{ Form::hidden('ad_type_' . $missionGoalId, \ScholarshipOwl\Data\Entity\Mission\MissionGoalType::ADVERTISEMENT) }} Advertisement</td>
                                                    <td>{{ Form::text('ad_name_' . $missionGoalId, $goalData['name'], array("class" => "form-control")) }}</td>
                                                    <td>&nbsp;</td>
                                                    <td>{{ Form::text('ad_parameters_' . $missionGoalId, $goalData['parameters'], array("class" => "form-control")) }}</td>
                                                    <td>{{ Form::checkbox('ad_active_' . $missionGoalId, "1", $goalData['active']) }}</td>
                                                    <td><a class="btn btn-danger DeleteAdButton" data-mission-goal-id="{{ $goalData['mission_goal_id'] }}" href="#">Delete</a></td>
                                                </tr>
                                            @endif
										@endforeach
									</tbody>
								</table>

								<p><a class="btn btn-primary" data-toggle="modal" data-target="#GoalTypes" href="#">Add Goal</a></p>
								<p><i>Goals that are already used by customers can not be deleted</i></p>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-6">
								{{ Form::textarea('description', $mission->getDescription(), array("class" => "form-control")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
                            <label class="col-sm-3 control-label">General Message</label>
                            <div class="col-sm-6">
                                {{ Form::textarea('message', $mission->getMessage(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Success Message</label>
                            <div class="col-sm-6">
                                {{ Form::textarea('success_message', $mission->getSuccessMessage(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Reward Message</label>
                            <div class="col-sm-6">
                                {{ Form::textarea('reward_message', $mission->getRewardMessage(), array("class" => "form-control")) }}
                            </div>
                        </div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">Save Mission</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/missions/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

<div id="GoalTypes" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Choose Goal Type</h4>
            </div>

            <div class="modal-body">
                <p><a class="btn btn-primary" data-toggle="modal" data-target="#AffiliateGoals" href="#">Affiliate Goal</a></p>
                <p><a class="btn btn-primary" data-toggle="modal" data-target="#ReferralAwards" href="#">Referral Award</a></p>
                <p><a class="btn btn-primary AddAdButton" href="#">Advertizement</a></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div id="AffiliateGoals" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Affiliate Goals
				</h4>
			</div>

			<div class="modal-body">
				@foreach ($options["affiliates"] as $affiliateId => $affiliate)
					@foreach ($affiliate->getAffiliateGoals() as $affiliateGoalId => $affiliateGoal)
							<a data-affiliate-goal-full-name="{{ $affiliate->getName() }} ({{ $affiliateGoal->getName() }})" data-affiliate-goal-id="{{ $affiliateGoalId }}" title="{{ $affiliateGoal->getUrl() }}" style="margin-top: 2px; margin-bottom: 2px;" href="#" class="btn btn-primary AddAffiliateGoalButton">
								{{ $affiliate->getName() }} ({{ $affiliateGoal->getName() }})
							</a>
					@endforeach
				@endforeach
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div id="ReferralAwards" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Referral Awards
				</h4>
			</div>

			<div class="modal-body">
				@foreach ($options["awards"] as $referralAwardId => $referralAward)
						<a data-referral-award-name="{{ $referralAward->getName() }}" data-referral-award-id="{{ $referralAwardId }}" title="{{ $referralAward->getName() }}" style="margin-top: 2px; margin-bottom: 2px;" href="#" class="btn btn-primary AddReferralAwardButton">
							{{ $referralAward->getName() }}
						</a>
				@endforeach
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


@stop
