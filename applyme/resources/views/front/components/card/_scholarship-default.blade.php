<div class="panel panel-default">
    <div class="panel-body">
        <h2 class="Util--color-title">{{ $scholarship->SCHOL_NM }}</h2>
        <div class="table-responsive">
            <table class="table Util--table-border-none">
                <tbody>
                    <tr>
                        <th>Amount</th>
                        <td>${{ number_format($scholarship->WEB_AMT) }}</td>
                    </tr>
                    <tr>
                        <th>Deadline</th>
                        <td>{{ $scholarship->DEADLINE }}</td>
                    </tr>
                    <tr>
                        <th class="Util--nowrap">City & State</th>
                        <td>{{ $scholarship->CITY }} / {{ $scholarship->STATE }}</td>
                    </tr>
                    <tr>
                        <th>Eligibility</th>
                        <td>{{ $scholarship->SCHOL_ELIG }}</td>
                    </tr>
                    <tr>
                        <th class="Util--nowrap">Sponsored by</th>
                        <td>{{ $scholarship->PATRON_NM }}</td>
                    </tr>
                </tbody>
            </table>
            <p><a href="{{ $scholarship->WEBSITE }}" class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted btn-block">APPLY FOR SCHOLARSHIP</a></p>
        </div>
    </div>
</div>
