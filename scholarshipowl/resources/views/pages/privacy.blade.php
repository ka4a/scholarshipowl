@extends('base')

@php $metaData = 'Privacy Policy'; @endphp
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


@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

    @section('content')

    <!-- Privacy header -->
    <div class="blue-bg clearfix">
      <div class="container">
        <div class="row">
          <div class="text-container text-center text-white">
            <h1 class="page-title text-large text-light" style="margin: 20px 0">Privacy Policy</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Privacy content -->
    <div class="section--privacy paleBlue-bg clearfix">
      <div class="container">
        <div class="row">
          <div class="text-container">
            <p><strong>ScholarshipOwl - Privacy Policy</strong></p>
            <p>Last revised: September 15, 2019</p>
            <p>Apply Me, Inc. (“Owl” or “we”) respects the privacy of its customers and visitors (“you”) and is committed to maintaining the privacy and confidentiality of your information including your personal information (collectively, “information”). This Privacy Policy explains what information we collect about you on our website (“Site”) and through other internet-based mechanisms (e.g., email and social media), our online and mobile applications, and our services generally (collectively, “Services”), how we use and disclose such information, and the safeguards we have in place to reduce the risk of unauthorized access and use of such information.</p>
            <p>By visiting our Site and/or using our Services, you accept our privacy practices described in this Privacy Policy. This Privacy Policy is also a part of our Terms of Use.</p>
            <p><strong><u>Table of Contents</u></strong></p>
            <ul style="list-style: none; padding-left: 0;">
                <li><a href="#table1">1. PRIVACY POLICY CHANGES</a></li>
                <li><a href="#table2">2. INFORMATION WE COLLECT</a>
                    <ul style="list-style: none;">
                        <li><a href="#table2.1">a. Personal Information</a></li>
                        <li><a href="#table2.2">b. Usage Data</a></li>
                        <li><a href="#table2.3">c. Communication Recording</a></li>
                    </ul>
                </li>
                <li><a href="#table3">3. HOW WE COLLECT INFORMATION</a>
                    <ul style="list-style: none;">
                        <li><a href="#table3.1">a. Voluntary Disclosure</a></li>
                        <li><a href="#table3.2">b. Third-Party Data Sources</a></li>
                        <li><a href="#table3.3">c. Cookies</a></li>
                        <li><a href="#table3.4">d. Information from Advertisements and Affiliate Marketers</a></li>
                        <li><a href="#table3.5">e. Tracking Pixels and Clear GIFs</a></li>
                        <li><a href="#table3.6">f. Social Media Widgets</a></li>
                    </ul>
                </li>
                <li><a href="#table4">4. HOW WE USE AND SHARE INFORMATION</a>
                    <ul style="list-style: none;">
                        <li><a href="#table4.1">a. Generally</a></li>
                        <li><a href="#table4.2">b. Testimonials</a></li>
                        <li><a href="#table4.3">c. Service Providers</a></li>
                        <li><a href="#table4.4">d. Third-Party Transfers</a></li>
                        <li><a href="#table4.5">e. Mail & Email Marketing</a></li>
                    </ul>
                </li>
                <li><a href="#table5">5. SECURITY</a></li>
                <li><a href="#table6">6. Blogs, Forums, TESTIMONIALS</a></li>
                <li><a href="#table7">7. OTHER WEBSITES</a></li>
                <li><a href="#table8">8. COMPLIANCE WITH LAWS AND LAW ENFORCEMENT</a></li>
                <li><a href="#table9">9. OTHER TRANSFERS</a></li>
                <li><a href="#table10">10. HOW LONG WE RETAIN YOUR DATA</a></li>
                <li><a href="#table11">11. CHILDREN’S PRIVACY</a></li>
                <li><a href="#table12">12. CONTACT US</a></li>
                <li><a href="#table13">13. ADDITIONAL CALIFORNIA CONSUMER RIGHTS</a>
                    <ul style="list-style: none;">
                        <li><a href="#table13.1">a. California “Do Not Track” Disclosure</a></li>
                        <li><a href="#table13.2">b. California Site Ownership Disclosure</a></li>
                    </ul>
                </li>
                <li><a href="#table14">14. ADDITIONAL NEVADA CONSUMER RIGHTS</a></li>
            </ul>

            <h2 id="table1" class="h4"><strong>1.   PRIVACY POLICY CHANGES</strong></h2>
            <p>This Privacy Policy is subject to change. We encourage you to review this Privacy Policy frequently for any revisions or amendments.  Such changes to this Privacy Policy will be posted on the Site and will be effective immediately upon posting.  You will be deemed to have been made aware of and have accepted the changes by your continued use of the Site or Services after the changes have been posted.</p>
            <h2 id="table2" class="h4"><strong>2.   INFORMATION WE COLLECT</strong></h2>
            <p id="table2.1"><strong>a.  Personal Information</strong></p>
            <p>We collect information that personally identifies, relates to, describes, or is capable of being associated with you (“Personal Information”), including:</p>
            <ul>
                <li>Personal Identifiers (e.g., name, date of birth, email address, mailing address, IP address, login username & password, links to social media profiles)</li>
                <li>Personal Characteristics of Protected Classifications (e.g., gender, ethnicity, citizenship, military affiliation)</li>
                <li>Education Information (e.g., high school name, college name, grade point average, career goals, field of study, grade level, major, essays, transcripts, recommendation letters)</li>
                <li>Commercial Information (e.g., payment method, subscription history, transaction history)</li>
                <li>Internet or Other Electronic Network Activity (e.g., device information such as operating system and browser type, email system data such as inbox and outbox content, affiliate marketing data such as transaction ID and offer ID, device type, other technical information regarding your use of our Site/Services that can be associated with you)</li>
                <li>Professional or Employment-Related Information (e.g., occupation)</li>
                <li>Audio & Visual Data (e.g., photos, videos)</li>
                <li>Geolocation Data</li>
                <li>Inferences drawn from the above categories of Personal Information that relate to your preferences, characteristics, psychological trends, predispositions, behavior, attitudes, intelligence, abilities, and aptitudes.</li>
            </ul>
             <p>The types of Personal Information we collect about you may vary based on how you use the Site and/or Services.</p>
            <p id="table2.2"><strong>b.   Usage Data</strong></p>
            <p>We automatically collect information in connection with the actions you take on the Site and in connection with using the Services (“Usage Data”).  For example, each time you use the Site, we automatically collect your IP address, location, hardware settings, browser type and settings, time and duration of your connection, and other device information. We use this information to improve the performance and functionality of our Site and Services, and to better understand how individuals interact with our Site and Services. If this information is capable of being associated with you, directly or indirectly, we treat it as Personal Information. If this information is not capable of being individually associated with you, we treat it as Usage Data.</p>
            <p id="table2.3"><strong>c.  Communication Recording</strong></p>
            <p>We and/or our Service Providers (as defined below), may record calls and retain the content of chat conversations or other written/electronic communications between you and us and/or our Service Provider. By communicating with us and/or our Service Provider, you consent to such recording and retention of communications.</p>
            <h2 id="table3" class="h4"><strong>3.   HOW WE COLLECT INFORMATION</strong></h2>
            <p id="table3.1"><strong>a.  Voluntary Disclosure</strong></p>
            <p>We may ask you to provide us with Personal Information when you register with the Site/Services, purchase a service, complete a scholarship application, claim a scholarship, leave a testimonial, communicate with us (online or offline), and at other times. You are not required to provide us your Personal Information; however, if you choose not to provide the requested information, you may not be able to use some or all of the features of the Site or Services or we may not be able to fulfill your requested interaction.</p>
            <p id="table3.2"><strong>b.   Third-Party Data Sources</strong></p>
            <p>We collect Personal Information from third-party data sources such as market research firms, scholarship sponsors or providers, tutoring and essay services, college placement and employment assistance services, social media, and data brokerage companies. This Personal Information may be appended to Personal Information we have already collected from or about you.</p>
            <p id="table3.3"><strong>c.    Cookies</strong><p>
            <p>We use cookies (a small text file placed on your device to identify your device and browser) to improve the experience of the Site and Services, such as keeping track of your activities on the Site, recognizing return visitors, and analyzing our promotions and Site traffic. Some third parties who advertise on our Site may also use cookies similar technologies. Many web browsers are initially set up to accept cookies.  You can reset your web browser to refuse all cookies or to indicate when a cookie is being sent.  Instructions for how to manage cookies in popular browsers are available at: <a href="https://www.theguardian.com/info/cookies" target="_blank">Internet Explorer</a>, <a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer?redirectlocale=en-US&redirectslug=Cookies" target="_blank">Firefox</a>, <a href="https://support.google.com/chrome/answer/95647?hl=en&ref_topic=14666" target="_blank">Chrome</a>, <a href="https://support.apple.com/en-us/HT201265"  target="_blank">Safari (iOS)</a>, <a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" target="_blank">Safari (Mac)</a>, and <a href="https://blogs.opera.com/news/2015/08/how-to-manage-cookies-in-opera/ " target="_blank">Opera</a>.  However, certain features of the Site or Services may not work if you delete or disable cookies.</p>
            <p>We use the following types of cookies:</p>
            <ul><li><u>Session Cookies:</u> Session cookies keep track of you or your information as you move from page to page within the Site/Services and are typically deleted once you close your browser.</li>
            <li><u>Persistent Cookies:</u> Persistent cookies reside on your system and allow us to customize your experience if you leave and later return to the Site/Services. For example, persistent cookies may allow us to remember your preferences.</li>
            <li><u>Strictly Necessary Cookies:</u> Strictly necessary cookies are, as their name suggests, necessary for the operation of the Site and/or Services. These are cookies that facilitate our compliance with laws, allow us to verify your identity before you access restricted areas of the Site/Services, manage our network, or keep track of user input.</li>
            <li><u>Advertising Cookies:</u> Advertising cookies are used to learn more about you and advertise our or another person’s products/services that might interest you.</li>
            <li><u>Analytics Cookies:</u> Analytics cookies help us understand how our Site and Services are working and who is visiting our Site or using our Services. Google Analytics is one tool we use, and you can learn more by reviewing <a href="https://policies.google.com/privacy" target="_blank">Google’s Privacy Policy</a>.</li></ul>
            <p>Depending on the circumstances, we may use cookies to collect Personal Information, Usage Data, or both.</p>
            <p id="table3.4"><strong>d.   Information from Advertisements and Affiliate Marketers</strong></p>
            <p>If you arrive at the Site via an advertisement (e.g., banner ad) or an affiliate marketer, we may collect information regarding the advertisement or affiliate marketer with which you interacted and your interactions (e.g., item clicked, date, time, offer ID, affiliate ID).</p>
            <p>Some third parties who advertise on our Site may also use cookies and similar technologies. For example, third-party remarketers, including Google, AdRoll, AdSense, HasOffers, and Facebook use cookies to serve ads based on a user’s prior visits to our website. Google’s use of advertising cookies enables it and its partners to serve ads based on visits to our site or other sites on the internet. You can opt out of Google’s personalized advertising by visiting Google’s <a href="https://support.google.com/ads/answer/2662856?hl=en" target="_blank">Ads Settings</a>. Alternately, you can opt out of many other third-party vendors’ use of cookies by visiting the Digital Advertising Alliance’s (DAA) opt out page at <a href="http://www.aboutads.info/choices" target="_blank">http://www.aboutads.info/choices</a> or <a href="http://www.aboutads.info/appchoices" target="_blank">http://www.aboutads.info/appchoices</a>. You may also set your browser to refuse or delete cookies after a period of time. To find out more about how Google uses data it collects please visit <a href="https://policies.google.com/privacy" target="_blank">Google Privacy & Terms</a>.</p>
            <p id="table3.5"><strong>e.  Tracking Pixels and Clear GIFs</strong></p>
            <p>We employ software technology that enables us to track certain aspects of a user's visit to the Site.  This technology helps us better manage content on the Site by informing us what content is effective, how consumers engage with the Site, and how consumers arrive at and/or depart from the Site.  The software typically uses two methods to track user activity: (1) "tracking pixels" and (2) "clear gifs."  Tracking pixels are pieces of executable code that are embedded in a web page that track usage activity including which pages are viewed, when they are viewed, and how long the pages are viewed.  Clear gifs are tiny graphics with unique identifiers which are embedded in web pages and email messages that track whether or not a user has viewed a particular web page or email message.  User activity information may be associated with additional information about a user's session, such as IP addresses and the type of browser used, and Personal Information, if provided by the user.</p>
            <p>If you arrive at the Site by "clicking through" from another website, then certain information about you that you provided to that other website, such as the terms you were searching that led you to the Site, may be transmitted to us and we may use it.  You should review the Privacy Policy of any website from which you reached the Site to determine what information was collected by that website and for what purpose(s) you agreed that website could use that information. We may retain information about you provided to us by other websites and will only use it in accordance with this Privacy Policy.  Such information may be associated with other Usage Data or Personal Information.</p>
            <p id="table3.6"><strong>f.     Social Media Widgets</strong></p>
            <p>The Site may include social media features, such as the Facebook and Twitter widgets.  These features may collect information about your IP address and the pages you visit on our Site as well as other Personal Information.  A cookie may be set to ensure that a feature properly functions.  Your interactions with social media features are governed by the privacy policies of the companies that provide them.</p>
            <h2 id="table4" class="h4"><strong>4.   HOW WE USE AND SHARE INFORMATION</strong></h2>
            <p id="table4.1"><strong>a.  Generally</strong></p>
            <p>We use Personal Information for internal purposes, such as to</p>
            <ul>
                <li>Provide you with the Site and Services;</li>
                <li>Process or complete transactions you requested;</li>
                <li>Improve the Site and Services, including customization and personalization;</li>
                <li>Market and remarket our products and services and the products and services of select third parties to you;</li>
                <li>Communicate with you about the Site and Services;</li>
                <li>Develop new products and services and enhance existing products and services; and</li>
                <li>Compile information and analyses to enhance the customer experience and improve our business.</li>
            </ul>
            <p id="table4.2"><strong>b.   Testimonials</strong></p>
            <p>If you provide a testimonial, we may post it publicly on the Site or in other advertising material. By providing a testimonial, you give us permission to use it in any manner and for any purpose, including in marketing and advertising communications.</p>
            <p id="table4.3"><strong>c.    Service Providers</strong></p>
            <p>From time to time, we may establish a relationship with other businesses that we believe are trustworthy and that we believe have privacy practices consistent with ours that may include corporate affiliates (“Service Providers”).  We contract with Service Providers to provide certain services to us, including:</p>
            <ul>
                <li>Online hosting and maintenance;</li>
                <li>Mobile and online application development, operation, and maintenance;</li>
                <li>Marketing and advertising design, distribution, tracking, and analysis;</li>
                <li>Payment processing;</li>
                <li>Management of access to services;</li>
                <li>Customer service & support;</li>
                <li>Information provision and notifications;</li>
                <li>Data storage and management;</li>
                <li>Analysis of the Site and Services for performance, business, technical, and user experience optimization; and</li>
                <li>Identity and contact information validation. </li>
            </ul>
            <p>We only provide our Service Providers with the information necessary for them to perform these services on our behalf.  Each Service Provider is expected to use reasonable security measures appropriate to the nature of the information involved to protect your Personal Information from unauthorized access, use, or disclosure.  Service Providers are prohibited from using Personal Information other than as specified by us.  We share the following types of Personal Information with one or more Service Providers: personal identifiers; personal characteristics of protected classifications; education information; commercial information; internet or other electronic network activity; professional or employment-related information; audio & visual data; geolocation data; and inferences drawn from the above categories of Personal Information that relate to your preferences, characteristics, psychological trends, predispositions, behavior, attitudes, intelligence, abilities, and aptitudes.</p>
            <p id="table4.4"><strong>d.   Third-Party Transfers</strong></p>
            <p>We share and/or sell your Personal Information to third-party companies who may market products or services likely to be of interest to you or who provide services that you request. For example, we may share your Personal Information with scholarship providers who may be able to provide you with a scholarship opportunity or to whom you wish to apply for a scholarship. We may also sell your Personal Information to college & scholarship information providers, education finance providers, advertising and marketing companies, student support organizations, market research firms, providers of products or services, and education providers. You agree to be contacted by third parties including schools or institutions you expressed interest in. We share/sell the following types of Personal Information with one or more third-party companies: personal identifiers; personal characteristics of protected classifications; and education information.</p>
            <p>If you would like to opt-out of having your Personal Information shared with third-party companies for their marketing purposes, please let us know by sending us an email at contact@scholarshipowl.com with the subject line “Sharing Opt Out” or by mail to the address in the Contact Us section below. Include in your request at least your name, email address, phone number, and a clear statement of your desire to opt-out. We may contact you to verify or obtain additional information to process your request. Please allow up to ten (10) business days to process your opt-out request.</p>
            <p>Please note that even if you opt-out, your information may still be shared with certain third parties for transactional purposes such as to apply for scholarships. Additionally, third parties who have already received your Personal Information may continue to communicate with you. We do not control and are not responsible for third-party communications or the use of your Personal Information by third parties. Use of your information by a third party will be governed by that third party’s terms of use and/or privacy policy.
            <p id="table4.5"><strong>e.  Mail & Email Marketing</strong></p>
            <p>We may use your Personal Information to market products or services likely to be of interest to you, or to provide you with special offers and opportunities, via mail & email.  With respect to commercial email communications from us, you may opt out of these emails via a link in the footer of all commercial email messages. You may also opt out of mail and email marketing communications by emailing us at unsubscribe@scholarshipowl.com.</p>
            <h2 id="table5" class="h4"><strong>5.   SECURITY</strong></h2>
            <p>We recognize the importance of safeguarding the confidentiality of your Personal Information.  Accordingly, we employ commercially-reasonable administrative, technical, and physical safeguards to protect your Personal Information from unauthorized access, disclosure, and use. For example, we work to protect the security of your information during transmission using Secure Sockets Layer (“SSL”) software and have deployed commercially-reasonable encryption, firewalls, and access controls. Even with these safeguards, no data transmission over the Internet or other network can be guaranteed 100% secure.  As a result, while we strive to protect information transmitted on or through our Site and Services we cannot, and do not, guarantee the security of any information you or we transmit on our Site or using our Services, or that you or we transmit over any other electronic network. You understand and agree that data and communications, including information collected and/or transferred via the Site or Services, may be accessed by unauthorized third parties at rest or in transit. Your provision of information to us is at your own risk and is subject to this Privacy Policy and our Terms of Use.</p>
            <h2 id="table6" class="h4"><strong>6.   Blogs, Forums, TESTIMONIALS</strong></h2>
            <p>Our Site and/or Services may contain features such as blogs, forums, message boards, testimonial opportunities, or other public-facing message systems. If you use one of these features, your comments, including any Personal Information you share, will be viewable to other users and the public. You use such features at your own risk. We are not responsible for the Personal Information you share using such features or any consequences that result therefrom.
            We reserve the right, but are under no obligation, to remove any comments from such features that we believe, in our sole discretion, constitute defamation, libel, slander, obscenity, pornography, profanity, hate speech, discrimination, or otherwise offend other users or our brand.</p>
            <h2 id="table7" class="h4"><strong>7.   OTHER WEBSITES</strong></h2>
            <p>Please be aware that third-party websites and social media platforms accessible through the Site and/or Services have their own privacy and data collection policies and practices.  We are not responsible for any actions, content of websites, or privacy policies of such third parties.  You should check the applicable privacy policies of those third parties before providing information to them.</p>
            <h2 id="table8" class="h4"><strong>8.   COMPLIANCE WITH LAWS AND LAW ENFORCEMENT</strong></h2>
            <p>We cooperate with government and law enforcement officials and private parties to enforce and comply with the law.  We may disclose Personal Information, Usage Data, and any other information about you to government or law enforcement officials or private parties if, in our sole discretion, we believe it is necessary or appropriate to respond to legal requests (including court orders, investigative demands, and subpoenas), to protect the safety, property, or rights of ourselves, users, or any other third party, to prevent or stop any illegal, unethical, or legally actionable activity, to enforce this Privacy Policy, our Terms of Use, or any other agreement you have with us, or to comply with law.</p>
            <h2 id="table9" class="h4"><strong>9.   OTHER TRANSFERS</strong></h2>
            <p>We may share Personal Information and Usage Data with businesses controlling, controlled by, or under common control with us.  If we are merged, acquired, or sold, or in the event of a transfer of some or all of our assets, we may disclose or transfer Personal Information and Usage Data in connection with such transaction.  You will have the opportunity to opt-out of any such transfer if, in our reasonable judgment, the new entity plans to handle your information in a way that differs materially from this policy.</p>
            <h2 id="table10" class="h4"><strong>10.                 HOW LONG WE RETAIN YOUR DATA</strong></h2>
            <p>We retain your Personal Information for as long as we have a relationship with you.  We also retain your Personal Information for a period of time after our relationship with you has ended where there is an ongoing business need to retain it.  This includes retention to comply with our legal, regulatory, tax, and/or accounting obligations.  Generally, we retain Personal Information for approximately seven (7) years after our relationship with you has ended, but the term for which we retain your Personal Information may be longer or shorter. We do so in accordance with our data retention policies and applicable law.</p>
            <h2 id="table11" class="h4"><strong>11.                 CHILDREN’S PRIVACY</strong></h2>
            <p>The Site is not intended for children under the age of 16 and we do not knowingly collect Personal Information from children under the age of 16.  If we become aware that we have inadvertently received Personal Information from a child under the age of 16, we will delete such information from our records.</p>
            <h2 id="table12" class="h4"><strong>12.                 CONTACT US</strong></h2>
            <p>If you have any questions about our Site, Services, or this Privacy Policy, please email us at contact@scholarshipowl.com. You may also send us a letter to the following address:</p>
            <p style="margin-left: 30px">
                <span>Apply Me, Inc.</span></br>
                <span>Attn: Privacy Dept.</span></br>
                <span>427 N. Tatnall St. #91572</span></br>
                <span>Wilmington, Delaware 19801-2230</span>
            </p>

            <h2 id="table13" class="h4"><strong>13.   ADDITIONAL CALIFORNIA CONSUMER RIGHTS</strong></h2>
            <p>If you are a resident of California, you have additional rights to access and control your Personal Information.</p>
            <p id="table13.1"><strong>a.  California “Do Not Track” Disclosure</strong></p>
            <p>Do Not Track is a web browser privacy preference that causes the web browser to broadcast a signal to websites requesting that a user’s activity not be tracked.  At this time, our Site and Services do not respond to “do not track” signals.</p>
            <p id="table13.2"><strong>b.  California Site Ownership Disclosure</strong></p>
            <p>Under California Civil Code Section 1789.3, California residents are entitled to the following information: The provider of the Site is Apply Me, Inc., 427 N. Tatnall St. #91572, Wilmington, Delaware 19801-2230. To file a complaint regarding the Site or to receive further information about the Site, please contact us. You may also contact the Complaint Assistance Unit of the Division of Consumer Services of the Department of Consumer Affairs in writing at 1625 North Market Blvd., Suite N 112, Sacramento, California 95834 or by telephone at (800) 952-5210.</p>
            <h2 id="table14" class="h4"><strong>14.   ADDITIONAL NEVADA CONSUMER RIGHTS</strong></h2>
            <p>If you are a resident of Nevada, you have the right to direct us not to sell your Personal Information to third parties. You may exercise these rights by sending an email to contact@scholarshipowl.com with the subject line “Nevada Opt Out.” Although Nevada law gives us 60 days to respond to your request, plus an additional 30 days in certain instances, we will strive to respond to your request as soon as reasonably possible. We may request additional information in order to verify your identity or locate your Personal Information.</p>
            <p><strong><u>Update History</u></strong></p>
            <div style="overflow: hidden;">
              <p style="float: left; width: 25%;"><strong>September 2019</strong></p>
              <p style="float: left; width: 75%;"><strong> – Updated Privacy Policy to incorporate additional detail regarding information collection, use, and disclosure; add California Consumer Privacy Act provisions; and add Nevada opt out rights.</strong></p>
            </div>
            <div style="overflow: hidden">
              <p style="float: left; width: 25%;"><strong>November 2017</strong></p>
              <p style="float: left; width: 75%;"><strong> – Revised provisions related to California privacy rights; information security; and collection and disclosure of information.</strong></p>
            </div>
            <div style="overflow: hidden">
              <p style="float: left; width: 25%;"><strong>June 2017</strong></p>
              <p style="float: left; width: 75%;"><strong> – Privacy Policy created.</strong></p>
            </div>
            </div>
          </div>
        </div>
      </div>

      @include('includes/refer')
      @stop
