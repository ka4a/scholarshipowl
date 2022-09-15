
Hi {{ $first_name }} 
<br><br>

We got a request to reset your password. 
<br>
To reset your password <a href='{{ url('') }}/reset-password?token={{ $token }}'>CLICK HERE</a>.
<br><br>

If you didn't make this request <a href='mailto:contact@scholarshipowl.com'>let us know</a>.
<br>
If the link doesn't work, copy and paste the text below into your browser:
<br>
{{ url('') }}/reset-password?token={{ $token }}
<br><br>


Best Regards,
<br>
ScholarshipOwl Support Team
<br><br>

Still need help? Find us on Chat or Email us today to contact@scholarshipowl.com
