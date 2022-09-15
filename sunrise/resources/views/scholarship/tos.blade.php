<h1>Terms of Use</h1>

<h2>1. Applicability</h2>
<p>
    These Terms of Use apply to all websites that are owned, operated, and maintained by or for {{ $business['name'] }},
    a Delaware limited liability company (“{{ $business['companyName'] }}”) including
    <a href="http://www.positiverewards.net/" target="_blank">www.positiverewards.net</a>,
    @for ($i = 0; $i < count($scholarshipWebsites); $i++)
        <a href="//{{ $scholarshipWebsites[$i] }}" target="_blank">{{ $scholarshipWebsites[$i] }}</a>@if($i < count($scholarshipWebsites) - 1),@endif
    @endfor
    and other {{ $business['companyName'] }} websites on which these Terms of Use are linked (“Site(s)”).
</p>


<h2>2. General Provisions</h2>
<p>
    THESE TERMS OF USE TOGETHER WITH THE PRIVACY POLICY AND ANY SUPPLEMENTAL TERMS, CONDITIONS,
    OR RULES POSTED TO A SPECIFIC AREA OF THIS SITE (“TERMS”) SET FORTH THE LEGALLY BINDING
    TERMS GOVERNING YOUR USE OF THIS SITE.
</p>
<p>
    By entering this Site, you acknowledge and agree to all terms, conditions, and rules stated
    in these Terms. You are not permitted to use this Site if you do not agree to be legally bound
    by these Terms. Please read these Terms carefully.
</p>
<p>
    We may, in our sole discretion, modify the Terms from time to time and we reserve the right
    to make changes at any time, without notice or obligation, to any of the content and information
    contained on this Site. By entering the Site you acknowledge and agree that you shall be bound
    by any such revisions. We suggest periodically visiting this page of the Site to review these Terms.
</p>

<h3>3. Jurisdiction and Governing Law</h3>
<p>
    {{ $business['companyName'] }} makes no representations that the information and materials contained
    within this Site are appropriate for locations outside the United States. By entering this Site, you
    acknowledge and agree that the Site is intended for use only by citizens and legal permanent
    residents of the United States of America residing within the United States of America and will only
    be governed according to the laws of the State of Delaware, without regard to conflicts of laws principles.
    If you are not a member of the intended audience, you are prohibited from accessing the Site or providing
    any personal information to {{ $business['companyName'] }} through the Site.
</p>

<h3>4. Disclaimer of Warranties</h3>
<p>
    This Site, and all information and materials contained herein, is provided to you on an "AS IS" and
    “AS AVAILABLE” basis, and AT YOUR OWN RISK TO THE FULLEST EXTENT PERMITTED UNDER APPLICABLE LAW.
    Although {{ $business['companyName'] }} and all parties involved in creating, producing, or delivering this Site make
    all reasonable efforts to ensure that all material on this Site is correct, accuracy cannot be guaranteed.
    WE DISCLAIM, TO THE FULLEST EXTENT PERMITTED BY LAW, ALL WARRANTIES, WHETHER EXPRESS OR IMPLIED, INCLUDING
    WITHOUT LIMITATION, ANY IMPLIED WARRANTIES OF TITLE, MERCHANTABILITY, NON-INFRINGEMENT AND FITNESS FOR A
    PARTICULAR PURPOSE AND ALL WARRANTIES REGARDING SECURITY, CURRENCY, CORRECTNESS, QUALITY, ACCURACY,
    COMPLETENESS, RELIABILITY, PERFORMANCE, TIMELINESS, OR CONTINUED AVAILABILITY WITH RESPECT TO THIS SITE.
    We expressly disclaim, to the fullest extent permitted by applicable law, any warranties with respect to
    any downtime, delays or errors in the transmission or delivery of any information, materials, or services
    through the Site. To the extent any jurisdiction does not allow the exclusion of certain warranties, some
    of the above exclusions do not apply.
</p>

<h3>5. {{ $business['companyName'] }}’ Intellectual Property</h3>
<p>
    {{ $business['companyName'] }} will aggressively enforce its intellectual property rights to the full extent
    of the law. All images, text, sound, photos, custom graphics, button icons, the collection and compilation
    and assembly thereof, and the overall “look and feel” and distinctiveness of the Site constitute trade dress
    and are either the property of {{ $business['companyName'] }} or used on this Site with permission.
    The absence on the Site of our name or logo does not constitute a waiver of our trademark or other intellectual
    property rights relating to such name or logo. All other product names, company names, marks, logos, and symbols
    appearing on the Site may be the trademarks and the property of their respective owners.
</p>
<p>
    You acknowledge and agree that information, and services available on the Site are protected by copyrights,
    trademarks, service marks, patents, trade secrets, or other proprietary rights and laws and are owned or
    licensed by {{ $business['companyName'] }}. Except as expressly authorized by {{ $business['companyName'] }},
    either in these Terms or elsewhere, you agree not to sell, license, rent, modify, distribute, copy, reproduce,
    transmit, publicly display, publicly perform, publish, adapt, edit, or create derivative works from the Site,
    information, or services. Without waiving any of the foregoing rights, you may print or download information
    from the Site for your own personal, non-commercial home use, provided that you keep intact all copyright and
    other proprietary notices. Systematic retrieval of information or services from the Site to create or compile,
    directly or indirectly, a collection, compilation, database, or directory without written permission from
    {{ $business['companyName'] }} is prohibited.
</p>

<h3>6. Digital Millennium Copyright Act Notice</h3>
<p>
    If you believe that any material on this Site infringes your copyright rights, please contact
    {{ $business['companyName'] }}’ designated agent for Digital Millennium Copyright Act notices at:
</p>
<p>
	<span>{{ $business['name'] }}</span><br/>
	<span>Attn: DMCA</span><br/>
    <span>{{ $business['address'] }}</span>&nbsp;<span>{{ $business['address2'] }}</span><br/>
    <span>{{ $business['city'] }}, {{ $business['region'] }} {{ $business['zip'] }}</span><br/>
	<span>Email address: <a href="mailto:{{ $business['email'] }}">{{ $business['email'] }}</a></span><br/>
    @if ($business['phone'])
        <span>Phone number: <a href="tel:{{ $business['phone'] }}">{{ $business['phone'] }}</a></span><br/>
    @endif
</p>

<p>In your notice, please include:</p>
<ul>
    <li>
        Your physical or electronic signature;
    </li>
    <li>
        Identification of the copyrighted work you claim to have been infringed, or, if there are multiple
        copyrighted works, a list of such works;
    </li>
    <li>
        Identification of the material that you claim to be infringing, and where the material is located on the Site;
    </li>
    <li>
        Your address, telephone number, and email address;
    </li>
    <li>
        A statement that you have a good faith belief that use of the material in the manner complained
        of is not authorized by you or the law; and
    </li>
    <li>
        A statement, under penalty of perjury, that the information in your notice is accurate.
    </li>
</ul>
<p>
    If the notice is submitted by someone else on your behalf, the notice must also contain a statement that,
    under penalty of perjury, the person submitting the notice is authorized to act on your behalf.
</p>

<h3>7. Limitation of Liability</h3>
<p>
    By using this Site you agree that, to the fullest extent permitted under applicable law, none of the
    parties involved in creating, producing, or delivering this Site is liable for any direct, incidental,
    consequential, indirect, or punitive damages, or any other losses, costs, or expenses or any kind which
    may arise, directly or indirectly, through the access to, use of, implementation of, or browsing of this Site.
</p>

<h3>8. Dispute Resolution</h3>
<p>
    Any controversy, claim or dispute arising out of or related to these Terms, the Site, and your relationship
    with {{ $business['companyName'] }}, including, but not limited to, alleged violations of state or federal
    statutory or common law rights or duties shall be solely and exclusively resolved according to the procedures
    set forth in this paragraph. If the dispute or claim is not otherwise resolved through direct discussions
    or mediation, it shall then be resolved by final and binding arbitration administered by the American
    Arbitration Association, in accordance with its rules for the resolution of consumer disputes ("AAA Rules")
    and applying Delaware law. You waive any right to bring your dispute or claim in court except as permitted
    by the AAA Rules. All proceedings brought pursuant to this section will be conducted in New York, New York.
    You further agree that, to the fullest extent permitted by law, (i) any and all claims, judgments, and awards
    shall be limited to actual out-of-pocket costs incurred, and in no event will you be entitled to received
    attorneys’ fees or other legal costs; and (ii) under no circumstances will you be permitted to obtain awards
    for, and you hereby waive all rights to claim, punitive, incidental, and consequential damages, and any other
    damages, other than for actual out-of-pocket expenses, and any and all rights to have damages multiplied
    or otherwise increased.
</p>
<p>
    BY AGREEING TO THESE TERMS, EACH PARTY IRREVOCABLY WAIVES ANY RIGHT IT MAY HAVE TO JOIN CLAIMS OR DISPUTES
    WITH THOSE OF OTHERS IN THE FORM OF A CLASS ACTION, CLASS ARBITRATION OR SIMILAR PROCEDURAL DEVICE;
    AND WAIVES ANY RIGHT IT MAY HAVE TO PRESENT ITS CLAIM OR DISPUTE IN A COURT OF LAW EXCEPT IN ACCORDANCE WITH
    THE AAA RULES. Judgment on the award rendered by the arbitrator(s), if any, may be entered for enforcement
    purposes in any court having jurisdiction thereof.
</p>

<h3>9. Indemnity</h3>
<p>
    You agree to defend, indemnify, and hold {{ $business['companyName'] }}, our affiliates, subsidiaries, joint
    ventures, third-party service providers, employees, contractors, agents, officers, and directors harmless
    from any and all liability, claims, and expenses (including reasonable attorneys' fees) that arise out of
    or are related to your violation of these Terms or use of the Site.
</p>

<h3>10. Sweepstakes</h3>
<p>
    The Site offers sweepstakes from time to time.  While no purchase is necessary to enter any such sweepstakes,
    you agree to comply with the Official Rules of the relevant sweepstakes advertised on this Site
    (incorporated herein by reference).
</p>

<h3>11. Privacy &amp; Security</h3>
<p>
    Our <a href="{{ $scholarship['pp_url'] }}" target="_blank">Privacy Policy</a> is incorporated into these Terms.
    You acknowledge that the requesting URLs of the machine originating the request and the time of the request
    are logged for access statistics and security purposes and agree that your use of this Site constitutes
    consent to such monitoring. {{ $business['companyName'] }} maintains exclusive control of access and right of
    access to this Site. You understand and agree that we reserve the right to revoke your access at any time without
    notice or cause of action for any reason whatsoever.
</p>

<h3>12. Links</h3>
<p>
    This Site may contain links to or be accessed through links that are owned and operated by independent third
    parties to which these Terms do not apply. We provide links as a convenience and the inclusion of the link does
    not imply that {{ $business['companyName'] }} endorses or accepts any responsibility for the content on those sites.
    {{ $business['companyName'] }} is not responsible for content including but not limited to advertising claims,
    special offers, illustrations, names or endorsements on any other sites to which this Site may be linked to or from
    which this Site may be accessed. Further, {{ $business['companyName'] }} is not, directly or indirectly, implying
    any approval, association, sponsorship, endorsement, or affiliation with the linked site, unless specifically
    stated therein. Your linking to any other off-site pages or other sites is at your own risk.  We recommend that
    you review any terms of use statement and privacy policy before using any other linked site.
</p>

<h4>Contact Information</h4>
<p>
	<span>{{ $business['name'] }}</span><br/>
    <span>{{ $business['address'] }}</span>&nbsp;<span>{{ $business['address2'] }}</span><br/>
    <span>{{ $business['city'] }}, {{ $business['region'] }} {{ $business['zip'] }}</span><br/>
	<span>Email address: <a href="mailto:{{ $business['email'] }}">{{ $business['email'] }}</a></span><br/>
    @if ($business['phone'])
        <span>Phone number: <a href="tel:{{ $business['phone'] }}">{{ $business['phone'] }}</a></span><br/>
    @endif
</p>

<h4>LAST UPDATED:  August 10, 2018</h4>
<h4>Update Summary:</h4>

<table cellpadding="10">
	<thead>
        <tr>
            <th>
                <u>Date</u>
            </th>
            <th>
                <u>Summary of change</u>
            </th>
        </tr>
	</thead>
	<tbody>
		<tr>
			<td>
				August 10, 2018
			</td>
			<td>
				Terms of Use Created
			</td>
		</tr>
	</tbody>
</table>
