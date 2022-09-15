@extends('base')

@php $metaData = 'Terms Of Use'; @endphp
@section('metatitle')
    <title>{{ $metaData }}</title>
    <meta property="og:title" content="{{ $metaData }}" />
    <meta name="twitter:title" content="{{ $metaData }}" />
@endsection

@section("metatags")
    <meta name="description" content="{{ $metaData }}" />
    <meta name="keyword" content="{{ \CMS::keywords() }}" />
    <meta name="author" content="{{ \CMS::author() }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ $metaData }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
    <meta name="twitter:description" content="{{ $metaData }}" />
@endsection

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('mainStyle') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('social') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

	@section('content')

			<!-- Terms of Use header -->
	<div class="blue-bg clearfix">
		<div class="container">
			<div class="row">
				<div class="text-container text-white text-center">
					<h1 class="page-title text-large text-light">Terms of Use</h1>
				</div>
			</div>
		</div>
	</div>

	<!-- Terms of Use body -->
	<div class="section--terms-and-conditions paleBlue-bg clearfix">
		<div class="container">
			<div class="row">
				<div class="text-container">

					<h2 style="font-size: 37px"><b>ScholarshipOwl – Double Your Opportunity Promotion ("Promotion")</b></h2>

					<p>
                        <span>{{ company_details()->getCompanyName() }}</span> ("Owl") may Double Your Opportunity by doubling your Scholarship, up to $1,000 per Winner!
                    </p>

                    <p><b>BY ENTERING THE PROMOTION YOU AGREE TO BE BOUND BY THESE RULES AS WELL AS OWL'S <a href="https://scholarshipowl.com/terms" target="_blank">TERMS OF USE</a> AND <a href="https://scholarshipowl.com/privacy" target="_blank">PRIVACY POLICY</a>.</b>This Promotion is an Additional Service, and these Rules are an Additional Agreement, both as defined in Owl's <a href="https://scholarshipowl.com/terms" target="_blank">Terms of Use</a>.</p>

					<p><b>YOU UNDERSTAND THAT ANY VIOLATION OF THESE RULES MAY RESULT IN YOUR DISQUALIFICATION FROM THE PROMOTION. ALL OF OWL'S DECISIONS REGARDING THE PROMOTION SHALL BE FINAL AND BINDING IN ALL RESPECTS. OWL RESERVES THE RIGHT TO AMEND OR DISCONTINUE THE PROMOTION AND THESE RULES AT ANY TIME IN ITS SOLE DISCRETION.</b></p>

					<p><b>THIS PROMOTION IS VOID WHERE PROHIBITED OR RESTRICTED BY LAW.</b></p>

					<p>1. <u>Entering the Promotion</u>.</p>

					<p>1.1 You will be automatically entered into the Promotion when you apply for a third-party scholarship ("<b>Scholarship</b>") through Owl's website and using Owl's services during the Application Period.
                    </p>

					<p>1.2 All applicants that fulfil all requirements in these Rules, including all eligibility requirements in Section 3 below, will win!</p>

					<p>1.3 There is no element of chance in this Promotion.  </p>

                    <p>2. <u>Definitions</u>. The following terms have the following meaning in these Rules:</p>

					<p>2.1 "<b>Scholarship</b>" means the third-party scholarship you applied for during the Application Period through Owl's website and using Owl's services (as evidenced by Owl's electronic records and verified by the scholarship provider). Please note that Owl's "You Deserve it Scholarship" is NOT considered to be a "Scholarship" for the purposes of this Promotion and is NOT included as part of this Promotion.</p>

                    <p>2.2 "<b>Application Period</b>" means 12:01 a.m. EST on July 1, 2017 through 11:59 p.m. EST on December 31, 2019.</p>

                    <p>2.3 "<b>Confirmation Details</b>" means the following information: your name, contact information, Owl registration/account details you used when applying for your Scholarship, name of the third-party Scholarship you won, the date on which you won it, a screenshot of the award confirmation email or other proof of your winning the Scholarship, the amount of the Scholarship you won [and evidence of the amount of the Scholarship actually paid by the scholarship provider], and contact details of the Scholarship provider.
                    </p>

					<p>2.4 "<b>Testimonial</b>" means your video, audio clip, or text that you provide Owl about your positive experiences with Owl and how you feel about the third-party scholarship you won through Owl's website and services, as well as your name and photograph, to be used for Owl's marketing and promotional purposes.
                    </p>

                    <p>3. <u>Winning the Promotion</u>. To win the Promotion, you must satisfy all of the following conditions: </p>

					<p>3.1 you must have used an active membership with Owl (either a free trial or paid membership) to apply for the Scholarship that you won (it is fine if you no longer have an active membership at the time of winning your Scholarship),</p>

					<p>3.2 you must have applied for your Scholarship through Owl's website and using Owl's services during the Application Period,
                    </p>

					<p>3.3 you must have actually won such Scholarship,
					</p>

					<p>3.4 no later than 14 days after you won such Scholarship you must email Owl at <a href="mailto:prize@scholarshipowl.com">prize@scholarshipowl.com</a>, providing Owl with your Confirmation Details and your Testimonial,
                    </p>

					<p>3.5 you must be a resident of any of the 50 United States, District of Columbia or the US Territories,
                    </p>

					<p>3.6 you must have been at least sixteen (16) years old on the day you applied for your Scholarship, and
                    </p>

					<p>3.6 you must not be a "Restricted Person".</p>
                    <p>
                        Owl may request additional information in order to confirm your eligibility. You hereby provide Owl with consent to contact the Scholarship provider or any other party in order to confirm your eligibility.
                    </p>



                    <p>4. <u>Restricted Persons</u>. You may not be a "<b>Restricted Person</b>," defined as an officer, director, member or employee of Owl or any other party associated with the development or administration of the Promotion, or an immediate family member (i.e., parents, children, siblings or spouse) of any of the foregoing, or a person living in the household of any of these individuals, whether or not related. </p>

                    <p>5. <u>Minors</u>. If you are a "<b>minor</b>," meaning that you are under the age of majority in your state, you must obtain permission from your parent or legal guardian, and your parent or legal guardian must consent to be bound by these Rules as if he or she were an entrant. </p>

                    <p>6. <u>Prize</u>. All applicants who evidence to Owl's satisfaction that they meet all requirements set forth in these Rules, including all eligibility requirements in Section 3 above ("<b>Winners</b>"), will win a prize equal to the amount of the first Scholarship won by Winner, up to a maximum of $1000 per Winner (regardless of the number of Scholarships actually won, or the actual value of Scholarship(s) won, during this Promotion) ("<b>Prize</b>"). For instance, if a Winner wins one Scholarship for $900 and a second Scholarship for $500, that Winner will win a Promotion Prize of $900. If Owl is unable to contact a Winner within 21 days, using contact information that Winner provided Owl, Winner will be deemed to have forfeited its Prize. To the extent not prohibited by law, Winner (and if a Winner is a minor, Winner's parent or legal guardian) will be required to sign a release which includes inter alia a declaration of eligibility, grant of publicity rights and a liability release, prior to receipt of a Prize. Winner may be required to provide Owl with a taxpayer identification number, or other identification or account number (if applicable). Prizes are non-transferable and must be accepted as awarded.</p>

                    <p>7. <u>Publicity</u>. Without derogating from the generality of Section 5 ("Publicity") of the <a href="https://scholarshipowl.com/terms" target="_blank">Terms of Use</a>, Winner hereby irrevocably and in perpetuity grants to Owl, those acting under Owl's authority, the unrestricted, absolute, perpetual, worldwide right and license to use Customer's Testimonial in connection with any and all marketing purposes; and to reproduce, copy, modify, create derivative works of, display, perform, exhibit, distribute, transmit or broadcast, publicly or otherwise, or otherwise use and permit to be used, Winner's Testimonial or any part thereof, whether alone or in combination with other materials (including but not limited to text, data, images, photographs, illustrations, and graphics, video or audio segments of any nature), in any media whatsoever, in connection with such marketing purposes; and all the foregoing without any compensation, royalties, remuneration or consideration to Winner or to any third party, and Winner hereby waive all claims to compensation,  royalties, remuneration, consideration, notice or permission in connection therewith.</p>

					<p>8. <u>Taxes, Costs and Expenses</u>. Each Winner shall be solely and exclusively responsible for all taxes, costs and expenses associated with the receipt and/or use of such Winner's Prize, and Winner shall report the value of any Prize for tax purposes as required by law. Without derogating from the generality of the foregoing, Winner shall provide Owl with a completed Form W-9 with valid identification, with a valid taxpayer identification number or social security number before the Prize will be awarded. If Winner wins over $600.00 in Prizes, then Winner will file a Form 1099 at the end of the relevant calendar year with the IRS.</p>

                    <p>9. <u>Ownership</u>. All Testimonials will become Owl's exclusive property and will not be returned. </p>

                    <p>10. <u>Release and Discharge</u>. You hereby release and discharge Owl and Owl's affiliates, officers, directors, employees, agents, and representatives ("<b>Owl Parties</b>") from any and all claims in connection with Owl's use, display, dissemination or exploitation of your Testimonial, including, but not limited to, any claims for defamation, violation of any moral or artist rights, violation of any right of privacy or publicity, any copyright infringement, or any other cause of action arising out of the use, copying, modification, display, transmission, distribution, broadcast or exhibition of the Testimonial or any part thereof.</p>

                    <p>11. <u>Disclaimer of Warranties</u>, Limitation of Liability. For the avoidance of doubt, and as set forth in Section 1.3 ("Additional Services") of Owl's <a href="https://scholarshipowl.com/terms" target="_blank">Terms of Use</a>, the provisions of Owl's <a href="https://scholarshipowl.com/terms" target="_blank">Terms of Use</a> shall apply to these Rules mutatis mutandis, including without limitation the provisions included in Section 1.9 ("Privacy Policy, Site Terms"), Section 7 ("Indemnification"), and Section 8 ("Disclaimers of Warranties and Limitation of Liability"). ALL CAUSES OF ACTION ARISING OUT OF OR CONNECTED WITH THE PROMOTION, INCLUDING WITHOUT LIMITATION IN CONNECTION WITH ANY TESTIMONIAL OR PRIZE, SHALL BE RESOLVED INDIVIDUALLY WITHOUT RESORT TO ANY FORM OF CLASS ACTION, AND IN ANY CAUSE OF ACTION OWL'S MAXIMUM AGGREGATE LIABILITY SHALL BE LIMITED TO APPLICANT'S DIRECT COSTS OF ENTERING AND PARTICIPATING IN THE PROMOTION, AND IN NO EVENT SHALL OWL BE LIABLE FOR ANY ATTORNEYS FEES. </p>

                    <p>12. <u>Assumption of Risk</u>. By taking any action to enter the Promotion, you hereby acknowledge and agree that: (a) YOU HAVE SOLE RESPONSIBILITY FOR YOUR TESTIMONIAL, AND EVEN THOUGH THE PROMOTION DOES NOT REQUIRE OR OTHERWISE ENCOURAGE DANGEROUS BEHAVIOUR, THERE MAY BE DANGER OR RISK OF BODILY INJURY, DEATH, OR PROPERTY DAMAGE INVOLVED IN CREATING AN TESTIMONIAL OR ENTERING THE PROMOTION, (b) THESE RISKS AND DANGERS MAY ARISE FROM FORESEEABLE OR UNFORESEEABLE CAUSES, (c) YOU HEREBY ASSUME ALL RISK AND RESPONSIBILITY FOR ANY PERSONAL INJURY, DEATH, PROPERTY DAMAGE, OR OTHER LOSS ARISING OUT OF THE CREATION OF ANY TESTIMONIAL OR ENTRY INTO THE PROMOTION, WHETHER CAUSED BY NEGLIGENCE OR ANY OTHER CAUSE, AND (d) SUBJECT TO APPLICABLE LAW, YOU HEREBY RELINQUISH ANY AND ALL RIGHTS YOU MAY NOW HAVE OR MAY HAVE IN THE FUTURE TO SUE OR TAKE ANY OTHER ACTION AGAINST THE OWL PARTIES ON THE BASIS OF ANY INJURY, DEATH, DAMAGE, OR OTHER LOSS THAT MAY BE SUFFERED ARISING FROM ANY ACTION TAKEN IN THE CREATION OF ANY TESTIMONIAL OR ENTRY INTO THE PROMOTION, INCLUDING BUT NOT LIMITED TO CLAIMS BASED ON ALLEGATIONS OF NEGLIGENCE BY ANY OF THE OWL PARTIES OR USE OF ANY MACHINERY OR MATERIALS. WITHOUT LIMITATION, THE OWL PARTIES SHALL HAVE NO LIABILITY TO ANY APPLICANT OR ANY OTHER PERSON IN THE EVENT THE TESTIMONIAL OR ANY ACTS OR OMISSIONS VIOLATES ANY OF THESE RULES.</p>

                    <p>13. <u>Right to Cancel, Suspend, or Modify</u>. Owl reserves the right to cancel, suspend, or modify the Promotion for any reason. Owl reserves the right to disqualify any applicant from the Promotion at any time in its sole discretion.</p>

                    <p>14. <u>Applicable Law and Jurisdiction</u>. The Promotion is subject to all applicable laws and regulations. Disputes concerning the construction, validity, interpretation or enforceability of these Promotion Rules shall be governed by the laws of the State of California, without application of its conflict of laws principles.</p>

                    <p>15. <u>Winners List</u>. Names of Winners will be posted on Owl's Winners Page. You may request a Winners' list by sending a stamped, self-addressed envelope to <a href="mailto:prize@scholarshipowl.com">prize@scholarshipowl.com</a>, or to <span>{{ company_details()->getAddress1() }}</span></p>

                    <p>16. <u>Miscellaneous</u>. If any part of these Rules is held by a court of competent jurisdiction to be invalid, illegal, or otherwise unenforceable, such part will be modified by such court to the minimum extent necessary to make it enforceable while preserving to the maximum extent possible the original intent of and the remaining parts of these Rules will remain in full force and effect.</p>

                    <p>17. <u>Sponsorship</u>. This promotion is sponsored solely by Owl. Owl can be reached at <a href="mailto:prize@scholarshipowl.com">prize@scholarshipowl.com</a>, or at <span>{{ company_details()->getAddress1() }}</span></p>
				</div>
			</div>
		</div>
	</div>

	@include('includes/refer')
@stop
