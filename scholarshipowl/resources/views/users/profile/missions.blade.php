<h4 class="sr-only sr-only-focusable">Missions</h4>

<div class="available-missions">

    <h5 class="text-light text-left text-blue">Available missions</h5>
    <div class="scrollbar table-responsive missionAccountTable">
        <table class="AccountMissionsTable table-responsive table-striped"  data-url="/api/v1.0/missions/history" data-method="get" data-type="json">
            <thead>
                <tr>
                    <th class="text-center">
                        Name:
                    </th>
                    <th class="text-center">
                        Description:
                    </th>
                    <th class="text-center">
                        From/Until:
                    </th>
                    <th class="text-center no-break">
                        Reward:
                    </th>
                    <th class="text-center no-break">
                        Status:
                    </th>
                </tr>
            </thead>
            <tbody>
        </table>
    </div>
</div>
@section("popups")
    @include ('includes.payment-missions-direct')
@endsection