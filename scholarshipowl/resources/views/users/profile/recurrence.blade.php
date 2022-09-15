<div class="row">
    <div class="col-xs-12">
        <h4>
            <span class="recurrent-icon glyphicon glyphicon-refresh"></span>
            Recurring Scholarship Settings
            <span class="icon icon-help tooltip-controller" data-trigger="manual" data-toggle="tooltip" data-placement="auto top" title="Recurring scholarships are scholarships which are reinstated periodically (e.g. weekly, monthly, yearly…)"></span>
        </h4>

        <p class="reccurence-inf">
            Recurring scholarships are submitted automatically.<br>
            Change settings:
        </p>

        <div class="row recurring-application-settings">
            <label class="col-xs-12">
                {{ Form::radio('recurring_application', \App\Entity\Profile::RECURRENT_APPLY_ON_DEADLINE, \Auth::user()->getProfile()->getRecurringApplication() === \App\Entity\Profile::RECURRENT_APPLY_ON_DEADLINE) }}
                <span class="lbl">Automatically apply 24 hours before deadline</span>
            </label>
            <label class="col-xs-12">
                {{ Form::radio('recurring_application', \App\Entity\Profile::RECURRENT_APPLY_DISABLED, \Auth::user()->getProfile()->getRecurringApplication() === \App\Entity\Profile::RECURRENT_APPLY_DISABLED) }}
                <span class="lbl">Do nothing</span>
            </label>
            <div class="col-lg-8 col-xs-10 reccuring-footer">
                <p>*note: when there is a change in scholarship (new requirement: essay, photo)
                it will automatically stop being recurring</p>
            </div>
        </div>

    </div>
</div>
