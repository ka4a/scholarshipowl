@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('faq') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection


@php
    ob_start();
    //FAQ items must be separated with 3 or more consecutive "="
    //Question and answer must be separated with 3 or more consecutive "-"
@endphp
    What is The You Deserve It Scholarship?
    ------------------------------
    <p>Our mission at ScholarshipOwl is to help students afford college by making
    scholarships easier to obtain and more accessible.
    That's why we created our own scholarship, The You Deserve It Scholarship.
    This scholarship is one of hundreds in our database that students may be
    eligible for.
    You can read more about the scholarship and hear from its winners
    <a href="/awards/scholarship-winners">here</a>.
    On the same page, you can also register for ScholarshipOwl's service for a
        chance at winning The You Deserve It Scholarship.</p>
    ==============================


    What are the requirements for the You Deserve It Scholarship? How do you choose
    a winner?
    ------------------------------
    <p>In order to be eligible for the You Deserve It Scholarship, you must:</p>
    <ol type="a">
    <li><p>Be a resident of any of the 50 United States, District of Columbia or US
    Territories, *(Rhode Island is excluded)</p></li>
    <li><p>Be 16 years of age or older</p></li>
    <li><p>Either be enrolled now, or be enrolled within three months of
    registration in the Scholarship Sweepstakes, in a qualified high school,
    college or university within the United States.</p></li>
    </ol>
    <p>A winner is chosen at random once a month.</p>
    ==============================


    What makes ScholarshipOwl stand out from other online scholarship databases?
    ------------------------------
    <p>
    ScholarshipOwl can help get you applied to multiple scholarships easily and at
    once with ONE application.
    </p>
    ==============================


    Who can join ScholarshipOwl?
    ------------------------------
    <p>
    ANY individual looking for scholarship opportunities towards their college or
    professional education in the US.
    </p>
    ==============================


    Why do I have to enter personal information to register with ScholarshipOwl?
    ------------------------------
    <p>
    ScholarshipOwl will only ask for information that is needed for students to
    apply for scholarships. We use the information provided to check eligibility for
    potential scholarships and then apply to multiple scholarships on your behalf.
    We will ask you for the same information that the scholarship fund is asking
    for.
    </p>
    ==============================


    I want to avoid getting unwanted emails from scholarship providers. How can I
    unsubscribe?
    ------------------------------
    <p>
    ScholarshipOwl will provide you with a message box on its website. All emails
    will be displayed there and no unwanted emails will ever hit your private inbox.
    </p>
    ==============================


    I would like to unsubscribe from all the emails I get from ScholarshipOwl, but I
    do not want to miss out on important information.
    ------------------------------
    I would like to unsubscribe from all the emails I get from ScholarshipOwl, but I
    do not want to miss out on important information.
    ==============================


    Does ScholarshipOwl increase my chances?
    ------------------------------
    <p>
    Your winning chance depends on you and how well you have represented yourself
    either through your essay, grades, or other factors. However, ScholarshipOwl
    increases your chance in a way that students can apply to many scholarships in a
    shorter period of time. We help students find many scholarships that they are
    eligible for, and get you applied to a greater number of scholarships.
    </p>
    ==============================


    Is ScholarshipOwl legit?
    ------------------------------
    <p>Yes. Not only is ScholarshipOwl "legit", but it's also a useful service that can
    save you both time and money.
    All you have to do is fill out one application form, add necessary details if
    required by individual scholarship providers, and ScholarshipOwl will apply you
    to a multitude of scholarships.
    It's likely that you were unaware that many of these scholarships even existed -
    yet alone that you qualify for them!
    We currently have hundreds of scholarships in our database and are constantly
    adding more.
    To date, we have applied more than a hundred thousand students and sent out over
    half a million scholarship applications.
    Feel free to read what others have written about us <a href="/press">here</a>.</p>
    ==============================


    When should I start applying for scholarships?
    ------------------------------
    <p>
    Many scholarship applications will require you to already be accepted to a
    college or university, but there are some applications that do not even require
    that much out of their applicants. If you missed the deadline for some
    scholarships that you wanted to apply for, start looking for new ones. In other
    words, the time to start is - NOW!
    </p>
    ==============================


    How can I increase my own chances at winning scholarships?
    ------------------------------
    <p>
    The more scholarships you apply to, the greater the chance of winning a
    scholarship. Also, proofread your work and submit applications before the
    deadline. The more information you give us, the more potential scholarships we
    can find.
    </p>
    ==============================


    I forgot my login information and I cannot log in?
    ------------------------------
    <p>
    No worries. Send an email to <a
    href="mailto:contact@scholarshipowl.com">contact@scholarshipowl.com</a>
    and we will reset your login info.
    </p>
    ==============================


    It says that my profile is not a 100% complete. What do I need to do to complete it?
    ------------------------------
    <p>
    More information will increase the number of scholarships available to you.
    </p>
    ==============================


    How does ScholarshipOwl protect my privacy?
    ------------------------------
    <p>
    You can view our privacy policy <a href="/privacy">here</a>.
    </p>
    ==============================


    How do students get matched to scholarships?
    ------------------------------
    <p>
    ScholarshipOwl will let you choose from all the scholarships in our database
    that you are eligible for, based on the information you provide us when you
    register. All the questions in the application serve as the eligibility criteria
    and match students to scholarships that they can apply to. Information such as
    GPA, current class level and choice of major are examples of some of the
    criteria we use.
    </p>
    ==============================


    Can international students apply?
    ------------------------------
    <p>
    International students can apply as long as they are legally studying in the
    U.S. or Canada.
    </p>
    ==============================


    Can returning/adult/non­traditional students apply?
    ------------------------------
    <p>
    For sure! Although some scholarships require that students are of certain age or
    have certain qualifications, non­traditional students can still apply to
    numerous scholarships at ScholarshipOwl.
    </p>
    ==============================


    How do I delete my account?
    ------------------------------
    <p>
    You can close your account by contacting <a
    href="mailto:contact@scholarshipowl.com?subject=Close my account">contact@scholarshipowl.com</a>
    with the subject “Close my account”.
    </p>
    ==============================


    What do I need to do to apply for scholarships?
    ------------------------------
    <p>
    We will provide you with a list of scholarships that you are eligible for. All
    you need to do is to select the scholarships you like and we will apply for you.
    It is important that you fill out your application fully so you get matched up
    with as many scholarships as possible.
    </p>
    ==============================


    Do I have to put my valid email address and phone number on the application?
    ------------------------------
    <p>
    Yes. Most scholarship providers contact winners by phone or by e­mail. If chosen
    as a winner, students must provide valid information if they wish to be
    contacted. Providing false information can disqualify you from scholarships.
    </p>
    ==============================


    What makes me eligible for a scholarship?
    ------------------------------
    <p>
    Each scholarship has its own eligibility requirements and ScholarshipOwl will
    help match students to scholarships that they are eligible for. Criteria such as
    age, GPA, location, subjects of interest and ethnicity are common in finding
    scholarships suited for you.
    </p>
    ==============================


    How long will it take to apply for all the scholarships I am eligible for?
    ------------------------------
    <p>
    There may be additional information needed based on the number of scholarships
    and their requirements. Additional work such as essays might take more time.
    </p>
    ==============================

    Although the application part is easy, why are there so many essays to write?
    ------------------------------
    <p>
    Please keep the deadlines in mind so that you don’t lose your chance at
    scholarships you are eligible for. ScholarshipOwl is here to help students avoid
    filling out the same information over and over again. It is a faster process
    that helps get students applied to many scholarships in less time. Also, some
    scholarships have the same essay question and students can avoid writing the
    same essay twice.
    </p>
    ==============================


    Are there any scholarships that do not require me to write essays?
    ------------------------------
    <p>
    Yes, there should be eligible scholarships for you that do not require an essay.
    One simple application can get you applied to all the scholarships that do not
    require an essay.
    </p>
    ==============================


    What is the difference between scholarships, grants, and student loans?
    ------------------------------
    <p>Most grants are needs-based, which means they are given out to students who have
    financial need due to their household income.</p>
    <p>Student loans are debts that must be paid back to the government or the loan
    provider after you have finished college. You may defer your student loan
    payments for up to six months after leaving school, but then you will have to
    make some sort of monthly payment until the balance is paid in full. It is
    better to get scholarships and grants, if at all possible, so you can avoid
    heavy debt after college.
    </p>
    ==============================


    On my list of scholarships that I am eligible for I have a scholarship that I
    applied to before. How can I avoid applying to it again?
    ------------------------------
    <p>
    Students must unmark scholarships that they do not wish to apply to. By
    unmarking the scholarships that you have applied to before, students protect
    themselves from submitting multiple entries and from being disqualified.
    </p>
    ==============================


    How do I find out the status of my applications?
    ------------------------------
    <p>
    You can check the status of the applications you’ve already submitted by signing
    in and clicking on My Applications. <span style="color:#ff6a6a">Here, you can also see if you applied to all the scholarships you’re eligible for</span>.
    </p>
    ==============================


    Will I get an email confirmation that my scholarship application has been received?
    ------------------------------
    <p>
    Application confirmation depends on the scholarship provider.
    Some providers will send an email confirmation while others do not.
    We recommend that you keep an eye on your ScholarshipOwl mailbox so you do not miss out if a provider replies to you.
    </p>
    ==============================


    How long after the submission deadline do you usually find out if you have won?
    ------------------------------
    <p>
    Winner announcements depend on the scholarship provider - On average,
    it takes 2 weeks to a month after the deadline, but some scholarships may even take up to 6 months.
    To get more information on individual scholarship terms, simply go to your scholarship
    dashboard > read "About the Scholarship" and follow the link to the provider's website.
    </p>
    ==============================


    If I am chosen as a winner for a scholarship, how am I notified?
    Will I find out via the app, or will I be contacted directly by the scholarship provider?
    ------------------------------
    <p>
    If you are chosen as a winner, a scholarship provider will either notify you in
    your ScholarshipOwl mailbox OR they might contact you by phone or your personal email.
    We recommend you check your ScholarshipOwl mailbox once a week and that you answer
    your phone to unknown numbers. Do not miss a chance to accept an award simply because
    you did not notice their attempts to contact you.
    </p>
    ==============================


    If I win a scholarship, how are the funds transferred?
    Are they sent to my school or do I receive them directly?
    ------------------------------
    <p>
    Some scholarship providers send funds to your school’s Financial Aid office while
    others send it directly to you. They will ask you to provide the appropriate payout information.
    </p>
    ==============================


    Do I need to update my account if I moved or changed my phone number?
    ------------------------------
    <p>
    It is highly recommended that students change their personal information when
    necessary. Go to My Account to make updates on your personal information.
    </p>
    ==============================


    I haven’t been contacted by any of the scholarship providers, does that mean I
    haven’t won?
    ------------------------------
    <p>
    Every scholarship has its own deadline. Some are monthly, some are only open for
    a certain time period, and there are those scholarships with deadlines next
    year. It doesn’t necessarily mean that the scholarship deadline has expired or
    that you haven’t won a scholarship. If you haven’t received your winning call or
    email, there are still new scholarship opportunities constantly available. You
    can always apply to new scholarships in hope for greater chances at winning.
    </p>
    ==============================


    I registered but I haven’t finished the application process, what should I do?
    ------------------------------
    <p>
    Login to <a href="{!! url_builder('/') !!}">ScholarshipOwl.com</a> with the
    details provided in our welcome mail. The My Account section will guide you
    through what is needed to complete the application process.
    </p>
    ==============================


    What are the different packages for?
    ------------------------------
    <p>
    ScholarshipOwl provides a helpful service for students looking to save time on
    scholarship applications. Our full range of services is offered on a premium
    level and the price is based on the range of services and total scholarships you
    wish to access. We do offer introductory services free of charge.
    </p>
    ==============================


    What else should I do to prepare for college scholarships?
    ------------------------------
    <p>In addition to using ScholarshipOwl to find and apply for scholarships, you
    should fill out the Free Application for Federal Student Aid (<a
    href="https://fafsa.ed.gov/" target="_blank">FAFSA</a>).  You will need
    to complete this application once a year to see if you are eligible for any
    federal grants or student loans. Some scholarship committees and universities
    require a FAFSA on file before issuing award money to a student. The application
    takes about 15-40 minutes the first time around, but it should be much faster in
    the years to follow.
    </p>
    ==============================


    What is the ScholarshipOwl refund policy?
    ------------------------------
    <p>
        For ScholarshipOwl refund policy see <a href="{!! route('terms') !!}">Terms of Use</a>
    </p>
    ==============================


    How much money can I get in scholarships?
    ------------------------------
    <p>
    There is no limit to the amount of money you can earn in college scholarships.
    However, your school may put a cap on the number of awards you can receive based
    on the estimated cost of attendance. This is the amount of money that the school
    has approximated to compensate for your tuition, fees, books, and living
    expenses. The cost of attendance varies greatly from one school to another, and
    your school's estimated costs may not reflect your actual expenses. You may
    speak with your college's financial aid department to learn more about the cost
    of attendance for your school and potential caps on your awards.
    </p>
    ==============================


    How much does it cost to go to college?
    ------------------------------
    <p>
    The cost of your college degree will depend on several factors, such as the type
    of school you go to (public vs. private), the type of degree you earn (graduate
    vs. undergraduate), the status of your attendance (full time vs. part time), the
    type of student you are (in-state vs. out-of-state vs. online), and the cost of
    living in your area. For example, the average cost of tuition, fees, room, and
    board for an in-state college student at a four-year public university is
    $18,943, according to <a
    href="http://trends.collegeboard.org/college-pricing/figures-tables/average-published-undergraduate-charges-sector-2014-15"
    target="_blank">The College Board</a>. This does not account for books
    and other school supplies you may need.</p>
    <p> You can check with your school's financial aid department to learn about the
    estimated cost of attendance at your school. Some schools have calculators on
    their websites that estimate costs based on the types of classes you will be
    taking and where you plan to be living (dorms, family, apartment, etc.). Find
    out how much you will need to pay for college so you can get the right number of
    scholarships to cover your costs.
    </p>
    ==============================


    My GPA is low. Can I still get scholarships?
    ------------------------------
    <p>While many scholarship committees reward applicants with good grades, some don't
    even ask for your GPA on your application. That is because the award is more
    focused on your essays, skills, goals, and overall success, not just how your
    grades look on paper. If you don't do well with tests or homework, there’s still
    hope of getting financial aid for college. All you need to do is apply for the
    awards you qualify for and see how it goes.
    </p>
    ==============================


    Can my scholarships pay for past student loans?
    ------------------------------
    <p>Unfortunately, any student loans you already have are considered personal debts,
    and they have to be paid back when you get out of deferment. With that in mind,
    you can use scholarships to pay for college in the future so you do not have to
    take out even more loans.</p>
    ==============================


    Can I still use my scholarship money if I change schools?
    ------------------------------
    <p>Others may require you to work at technology centers, vocational schools, or
    junior colleges. You will have to research specific information about your
    approved scholarships to see what will happen with your aid during the
    transfer.</p>
    ==============================


    What happens to my scholarships if I drop out of college?
    ------------------------------
    <p>If you drop out of college, you will not receive any money that has been awarded
    to you for that semester. If you re-apply and get accepted, you may be able to
    get the approved funds for the next semester, depending on the rules of the
    scholarship. Note that there is a big difference between dropping out of school
    and taking a break. You can remove yourself from classes for a semester without
    withdrawing from the college entirely. If there is a chance you will return to
    school in the next few months or even the next year, do not withdraw from school
    altogether. This will preserve your admission into the school and maintain your
    active financial aid.</p>
    ==============================


    What if my scholarships exceed my cost of attendance?
    ------------------------------
    <p>The college may only allow you keep the awards in the amount of the costs of your
    attendance.</p>
    ==============================


    How do I cancel my subscription?
    ------------------------------
    <p>You can cancel your subscription any time. Navigate to the membership tab within
    <a href="{!! url_builder('/my-account#membership-tab') !!}">your account section </a>
    and follow the instructions displayed there.</p>

@php
    $faqList = array_filter(preg_split("/\={3,}/", ob_get_clean()));
@endphp

@section('content')
    <section role="region" aria-labelledby="page-title">
        <div class="section--faqs paleBlue-bg clearfix">
            <div class="container">
                <div class="row">
                    <div class="panel-group" id="accordion">
                        <h1 class="sr-only" id="page-title">ScholarshipOwl FAQ</h1>
                        @foreach($faqList as $k => $item)
                            @php
                                $data = array_filter(preg_split("/\-{3,}/", $item));
                                $question = trim($data[0]);
                                $answer = trim($data[1]);
                                $classQn = $k != 0 ? "collapse" : "";
                                $classAr = $k != 0 ? "collapse" : "collapse in"
                            @endphp
                            <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title text-semibold text-blue">
                                    <a class="{!!$classQn!!}" data-toggle="collapse" data-parent="#accordion" href="#collapse{!!$k!!}">
                                        {!!$question!!}
                                        <span class="plus-minus"></span>
                                    </a>
                                </h2>
                            </div>
                            <div style="" id="collapse{!!$k!!}" class="panel-collapse {!!$classAr!!}">
                                <div class="panel-body">
                                    {!!$answer!!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
