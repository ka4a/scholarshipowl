@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('mainStyle') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('tips') !!}
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

<!-- Tips on How to Apply header -->
<section role="region" aria-labelledby="page-title">
			<div class="blue-bg clearfix">
				<div class="container">
					<div class="row">
						<div class="text-container text-white text-center">

							<h1 class="h2 text-light" id="page-title">
								Tips on <span class="linebreak-xxs">How to Apply</span>
							</h1>
							<p class="lead mod-top-header">
								<strong>Getting a scholarship isn't hard to do if you have a good application to turn in.</strong> <br />You can't change the way the scholarship committee reviews your application, but there are some things you can do to increase your chances of being accepted. Here are some quick tips on how to apply for a scholarship so you can start paying for school.
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>

<!-- Tips on How to Apply -->
<section role="region" aria-labelledby="true-purpose">
			<div class="section--tips paleBlue-bg clearfix">
				<div class="container">
					<div class="row">
						<div class="text-container center-block mod-text-container">
							<h2 class="h4 text-semibold" id="true-purpose">
			              		Understand the True Purpose of the Scholarship
			            	</h2>
			            	<p class="divider-dashed">
                                Why has this organization decided to create this scholarship? What are their goals? What do they stand to gain from helping people like you through college? By understanding the true purpose of a scholarship, you can adjust your application to respond best to the questions at hand. This also gives you a chance to convey specific information that you feel will appeal to the scholarship committee, showing that you deserve financial reward without question.
						    </p>

						    <h2 class="h4 text-semibold">
						    	Write down Your Accomplishments
						    </h2>
					    	<p class="divider-dashed">
					    		Before you fill out your application details, write down a list of all your major accomplishments. This may take a little time to do, but having that information will allow you to create the most appealing application possible. If you have a resume, you may want to refer to your resume to complete your scholarship application because it should already have all your accomplishments listed.
							</p>

							<h2 class="h4 text-semibold">Make It Personal</h2>
							<p>
					    		A lot of aspiring college students focus more on being conventional than they do on creating a connection with the people reading their application. Yes, your essay questions and short answers should be academic and professional, but that doesn't mean you have to keep the subject matter rigid and formal. The key to a good scholarship application is to make the committee feel as if they truly know who you are and who you are going to become. You have to make them feel confident that you are going to put your scholarship funding to good use.
							</p>
						    <p class="divider-dashed">
						   		When you get an essay prompt that asks for a personal story, really think about an event that has changed your life for the better. It could be something as simple as a compliment someone gave you that inspired you to create something amazing. Just make sure that it reflects who you are as a person and the review board is sure to connect with you.
							</p>

							<h2 class="h4 text-semibold">
								Don't Get Sappy
							</h2>
						    <p class="divider-dashed">
						    	Don't assume that a sad story is going to make the review committee feel sorry for you. They read countless sob stories every year, so chances are they have already heard of someone worse off than you. Rather than seeking out sympathy from them, consider writing your application in a way that shows strength through struggle. You can remark on a hardship you have had to get through, but don't make it overly depressing. The committee will admire you more for your perseverance and determination than your grief.
						    </p>

						    <h2 class="h4 text-semibold">
						    	Support Your Statements with Facts
						    </h2>
						    <p class="divider-dashed">
						    	If a part of your essay includes assertions or generalizations about a certain topic, make sure you back it up with solid proof. For instance, if you are referring to statistical data that emphasizes the importance of an event, cite the source from where you obtained the data. Even if you just make a footnote with a link in the bottom of the essay, you will show the scholarship committee that you put effort into writing your essay.
						    </p>
                <h2 class="h4 text-semibold">
                  Apply Early and Often
                </h2>
                <p class="divider-dashed">
                  Applying early may not improve your chances of winning a scholarship, but it will ensure that you get to as many awards as possible. Making this part of your weekly, monthly, or even daily routine will boost your aid opportunities tremendously.
                </p>

                            <h2 class="h4 text-semibold">
                            	Apply Early and Often
                            </h2>
						    <p class="divider-dashed">
						    	Applying early may not improve your chances of winning a scholarship, but it will ensure that you get to as many awards as possible. Making this part of your weekly, monthly, or even daily routine will boost your aid opportunities tremendously.
						    </p>

                            <h2 class="h4 text-semibold">
                            	Explore All Your Options
                            </h2>
						    <p class="divider-dashed">
						    	Don't just focus on one type of scholarship. Apply for anything that you may qualify for. An award that may not seem like it is right for you may have low competition, making you a stand out contender.
						    </p>

                            <h2 class="h4 text-semibold">
                            	Record Your Answers out Loud
                            </h2>
						    <p class="divider-dashed">
                                If you have a hard time writing essays, you should think about creating a video as a response to an essay question. Then you can transcribe the information from the recording into an actual application. If you use the speech-to-text function on your smartphone to create a voice recording, you may be even able to do less work than that. Just make sure that you read over your answers thoroughly before submitting them. You know AutoCorrect has a mind of its own!
						    </p>

                            <h2 class="h4 text-semibold">
                            	Write Answers in a Separate Program
                            </h2>
						    <p class="divider-dashed">
                                Write down your short answers and essays using a different program, such as Microsoft Word, Google Drive, or a simple email. These programs will save drafts for you, in case something goes wrong while you are writing. Get in the habit of saving your essays as you write, so you do not lose much if your computer suddenly crashes. Also make sure to use the spelling and grammar checks available through those programs so that your application is mistake-free.
						    </p>

						    <h2 class="h4 text-semibold">
						    	Think outside the Box
						    </h2>
						    <p class="divider-dashed">
						    	Scholarship committees love reading applications that are out of the ordinary. If you catch them by surprise, your application will be noticed. One of our users recently told us a story of a scholarship she won for her creativity. She was asked to talk about a person in her life that has made her a better person, and she had chosen her father. Why? Because he walked out of her life when she was a child and forced her to fend for herself. You weren't expecting that, right? Come up with a way to make your story stand out in the crowd, and you'll get a much better reception.
                            </p>

						    <h2 class="h4 text-semibold">
						    	Tell the Truth
						    </h2>
						    <p class="divider-dashed">
						    	It may be tempting for you to lie about your accomplishments and experiences, but that is not a good idea. Many scholarship committees will run background checks to see if the information you provided is accurate. If they catch you in a lie, they'll immediately dismiss your application. Be honest about your achievements, even if they aren't as outstanding as you'd like them to be.
                            </p>

							<h2 class="h4 text-semibold">Don't Miss Deadlines</h2>
						    <p class="mod-text divider-dashed">
						    	Check all deadlines carefully before you miss out on an important award. ScholarshipOwl makes it easy to complete applications, so there is no reason to skip out on potential funding for your education. There is no such thing as sending out too many scholarship applications as long as you get them all in on time.
						    </p>

						    <h2 class="h4 text-semibold">
						    	Review, Review, Review!
						    </h2>
						    <p>
						    	Before you complete your application information, re-read it a few times. If you need to let it sit a day so you can come back to it, do so. The people looking over your scholarship don't know you. Your application is their first impression of you and will reflect badly on the scholarship committee if you have tons of typos and incomplete sentences in it. To be on the safe side, ask an instructor, parent or a friend to look over your information to make sure it reads well.
						      </p>
						    <p class="divider-dashed">
                  Also make sure to double-check all the personal information you entered, such as your name, email address, and telephone number. If this information is incorrect, the scholarship board may have no way of reaching you. Check all the details carefully.
						    </p>
						</div>
					</div>
				</div>
			</div>
		</section>

  @include('includes/refer')


@stop
