Recurrent scholarship have been cloned.
<br><br>

<?php $scholarship = unserialize($scholarship); ?>
{{ $scholarship->getScholarshipId() }}, {{ $scholarship->getTitle() }},
<a href="{{ url('/admin/scholarships/save?id=' . $scholarship->getScholarshipId()) }}">Edit</a>
<br><br>

Kind Regards,
<br>
ScholarshipOwl Team
