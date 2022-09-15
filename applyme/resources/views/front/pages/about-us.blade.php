@extends('layouts.front')

@section('meta-title', "About Us | Apply.me")

@section('meta-description', "Our mission is to simpliy the process of applying to college & to ease the burden of rising college costs. Our goal is to help you succeed!")

@section('page-name', 'page-about-us')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="imgs/icons/am.svg" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">About Us</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        We are a team of dedicated professionals looking to ease
                        the burden of rising college costs. We are here to
                        ensure that students have a helping hand toward a
                        successful education by providing direction for those
                        unfamiliar with the entire college and scholarship
                        process.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">About Us</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <p class="visible-xs">
                        <img src="/imgs/photos/kenny-sandorffy.jpg" alt="Kenny Sandorffy">
                    </p>

                    <div class="media">
                        <div class="media-left hidden-xs">
                            <img class="media-object" src="/imgs/photos/kenny-sandorffy.jpg" alt="Kenny Sandorffy">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading Util--text-primary">Kenny Sandorffy</h2>
                            <h3>CMO and co-founder of Apply.Me</h3>
                            <p class="Util--text-dark-secondary">
                                Kenny Sandorffy is a scholarship visionary. After
                                his own experiences applying for scholarships while
                                he was in college, he came up with the idea to
                                create a platform to fix the problems he faced with
                                the scholarship system. This included trouble
                                identifying the scholarships he qualified for and
                                the endless hours he spent filling out applications
                                he found all by himself. After years as a technology
                                entrepreneur, he put together a team to create
                                Apply.Me. The site provides a streamlined way
                                to find likely, multiple scholarships, and automates
                                a large part of the application process.
                            </p>
                            <p class="Util--text-dark-secondary">
                                Kenny graduated from the London School of Economics
                                and Political Science (LSE) with a Bachelor of
                                Science in Employment Relations and HR Management.
                                He earned his Master of Arts degree in the field of
                                Image and Communication from Goldsmiths College.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="Util--spacer-trans-medium">

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <p class="visible-xs">
                        <img src="/imgs/photos/david-tabachnikov.jpg" alt="David Tabachnikov">
                    </p>

                    <div class="media">
                        <div class="media-left hidden-xs">
                            <img class="media-object" src="/imgs/photos/david-tabachnikov.jpg" alt="David Tabachnikov">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading Util--text-primary">David Tabachnikov</h2>
                            <h3>CTO of Apply.Me</h3>
                            <p class="Util--text-dark-secondary">
                                David Tabachnikov is an expert in research and
                                technological development with over 10 years of
                                experience of leading tech teams. He is
                                accomplished in remote team managements,
                                distributed teams, and remote-by default
                                companies. David has lived and worked across the
                                globe developing trusted partnerships with
                                counterparts in Europe including Ukraine,
                                Russia, Serbia, Latvia and parts of the Middle
                                East. David is a dedicated professional always
                                willing to help others achieve their goals.
                            </p>
                            <p class="Util--text-dark-secondary">
                                Organizer of global tech events. He is on a
                                constant quest to adapt and apply new
                                technologies to his projects and features.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="Util--spacer-trans-medium">

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <p class="visible-xs">
                        <img src="/imgs/photos/jordan-schanda.jpg" alt="Jordan Schanda">
                    </p>

                    <div class="media">
                        <div class="media-left hidden-xs">
                            <img class="media-object" src="/imgs/photos/jordan-schanda.jpg" alt="Jordan Schanda">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading Util--text-primary">Jordan Schanda</h2>
                            <h3>College Preparation Expert</h3>
                            <p class="Util--text-dark-secondary">
                                Jordan Schanda had to learn everything about college
                                and scholarships the hard way. After a lot of work
                                and a little bit of trial and error, she was
                                fortunate to be awarded nearly $50,000 for college.
                                Knowing her brother was not the same type of
                                student, she and her mom carefully outlined and
                                documented the process that had worked so
                                successfully. They’ve helped thousands of families
                                across  the country and the world navigate this
                                process so that they can get into their top choice
                                school and win scholarships to pay for their
                                education.
                            </p>
                            <p class="Util--text-dark-secondary">
                                Jordan graduated with honors from University of
                                Arkansas, where she received her Bachelor of Arts in
                                Psychology, minoring in statistics and
                                sustainability. She received her Master of Science
                                in Administrative Studies at Missouri State
                                University.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="Util--spacer-trans-medium">

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <p class="visible-xs">
                        <img src="/imgs/photos/jennifer-finetti.jpg" alt="Jennifer Finetti">
                    </p>

                    <div class="media">
                        <div class="media-left">
                            <img class="media-object hidden-xs" src="/imgs/photos/jennifer-finetti.jpg" alt="Jennifer Finetti">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading Util--text-primary">Jennifer Finetti</h2>
                            <h3>Admissions Coach</h3>
                            <p class="Util--text-dark-secondary">
                                As a parent of a son who recently graduated college,
                                and a daughter who is a current college freshman
                                embarking on her own college journey, Jennifer
                                approaches the transition from high school to
                                college from a unique perspective. She truly enjoys
                                engaging with students – helping them to build the
                                confidence, knowledge, and insight needed to pursue
                                their educational and career goals, while also
                                empowering them with the strategies and skills
                                needed to access scholarships and financial aid that
                                can help limit college costs.
                            </p>
                            <p class="Util--text-dark-secondary">
                                Jennifer earned her Bachelor’s in Psychology from
                                University of California, Santa Cruz, and her
                                Master’s in Counseling Psychology from National
                                University.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="Util--spacer-trans-medium">

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <p class="visible-xs">
                        <img src="/imgs/photos/matthew-lesko.jpg" alt="Matthew Lesko">
                    </p>

                    <div class="media">
                        <div class="media-left">
                            <img class="media-object hidden-xs" src="/imgs/photos/matthew-lesko.jpg" alt="Matthew Lesko">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading Util--text-primary">Matthew Lesko</h2>
                            <h3>Free Money Expert</h3>
                            <p class="Util--text-dark-secondary">
                                Armed with a MBA in computerized management
                                information systems paid for thanks to
                                government money because of service as a Naval
                                Officer in the Vietnam War, Lesko became a
                                professor in computer science and started a
                                career as an entrepreneur.
                            </p>
                            <p class="Util--text-dark-secondary">
                                His first successful company was a consulting
                                business that helped Fortune 500 companies tap
                                government programs to finance mergers and
                                acquisitions and enter new markets. In a few
                                short years, this business grew from himself
                                with a phone and a desk in a one-room apartment
                                to 30 researchers in downtown Washington, DC.
                            </p>
                            <p class="Util--text-dark-secondary">
                                He then left the corporate world to educate the
                                average consumer how they also can use the same
                                government programs to help their lives. He has
                                written over 100 books on the subject of free
                                money. Two became New York Times best-sellers.
                            </p>
                            <p class="Util--text-dark-secondary">
                                He was also a columnist for the New York Times
                                Syndicate and Good Housekeeping magazine. He has
                                appeared on hundreds of radio and TV shows and
                                made regular appearances on Letterman, Larry
                                King, Leno, CNN, Fox, The Today Show, Good
                                Morning America, and Oprah. Moreover, he been
                                featured on Vice and hosted a Tedx Talk. His
                                crazy TV infomercials were among the most
                                popular in the industry and he is often
                                recognized on the street because Lesko always
                                wears Question Mark Suits.
                            </p>
                            <p class="Util--text-dark-secondary">
                                Now he is involved with what is happening on the
                                Internet with Crowdfunding, Peer-To-Peer and the
                                Shared Economy. He has been studying this
                                industry for the last 3 years and has amassed a
                                collection of over 1,000 interviews of people
                                who have used crowdfunding money or began
                                crowdfunding websites.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="Util--spacer-trans-medium">

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <p class="visible-xs">
                        <img src="/imgs/photos/luis-trujillo.jpg" alt="Luis Trujillo">
                    </p>

                    <div class="media">
                        <div class="media-left">
                            <img class="media-object hidden-xs" src="/imgs/photos/luis-trujillo.jpg" alt="Luis Trujillo">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading Util--text-primary">Luis Trujillo</h2>
                            <h3>Scholarship Expert</h3>
                            <p class="Util--text-dark-secondary">
                                Luis Trujillo has written for Fox News Latino, Money
                                Talks News and Grockit on the topic of financial
                                aid. He has been recognized by the former governor
                                of Texas, Rick Perry for his volunteer efforts. He
                                has videos on financial aid that have garnered tens
                                of thousands of views on Youtube and have helped
                                thousands of students be financially savvy and
                                successful through his digital mentorship products.
                                Luis has spoken to and worked with thousands of
                                youth for the past 7 years on different topics. His
                                heart is to mentor and coach today's youth. He is
                                passionate about impacting this generation. He
                                resonates with students of every kind and will
                                inspire them to reach new heights.
                            </p>
                            <p class="Util--text-dark-secondary">
                                Luis is a graduate of Wayland Baptist University
                                where he received his Bachelor of Business
                                Administration with a specialization in accounting.
                                He received his Master’s in Business Administration
                                from West Texas A&M University.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
