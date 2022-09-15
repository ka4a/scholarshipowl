
Scholarships Expired For Today
<br><br>

Total expirations: {{ $total }}
<br>


<?php $scholarships = unserialize($scholarships); ?>
@foreach ($scholarships as $scholarship)
{{ $scholarship->getScholarshipId() }}, {{ $scholarship->getTitle() }}, {{ $scholarship->getUrl() }}
<br>
@endforeach
<br><br>


Kind Regards, 
<br>
ScholarshipOwl Team
