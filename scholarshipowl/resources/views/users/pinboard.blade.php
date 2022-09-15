@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle10') !!}
@endsection


@section("scripts")
    {!! HTML::script('https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.9/jquery.mCustomScrollbar.concat.min.js') !!}
    {!! HTML::script('assets/plugins/mousewheel/jquery.mousewheel.min.js') !!}
    {!! HTML::script('assets/js/mailbox/jplist-core.min.js') !!}
    {!! HTML::script('assets/js/mailbox/jplist.sort-buttons.min.js') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle8') !!}
@endsection

@section('content')


<section id="pinboard-head">
    <div class="blue-bg">
        <div class="container">
            <div class="row">
                <div class="text clearfix">
                    <div class="col-xs-12 col-ms-8 col-sm-8">
                        <h2 class="title text-left text-smallcaps">Title goes here</h2>
                    </div>
                    <div class="col-xs-12 col-ms-4 col-sm-4 text-left">
                        <ul class="nav nav-tabs nav-pills" id="myTab">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pinboard
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#textbooks" data-toggle="tab">Book exchange</a>
                                    </li>
                                    <li>
                                        <a href="#loans" data-toggle="tab">Student loans</a>
                                    </li>
                                    <li>
                                        <a href="#college" data-toggle="tab">College recommendations</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Tab panes -->
<div class="tab-content">
    <section role="tabpanel" class="tab-pane fade in active active" id="pinboard-main">
        <div id="pinboard" class="blueBg">
            <div class="container-fluid skyBlue-bg"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4 textbook">
                    <img src="{{ asset("assets/img/pinboard/textbooks.png") }}" />
                    <h2>
                        <a href="#textbooks" data-toggle="tab">The Cheapest Textbooks</a>
                    </h2>
                    <div class="semi-sub">
                        <p>
                            <em>Save up to 90%</em><br />
                            on millions of titles
                        </p>
                    </div>
                    <hr />
                    <p>
                        <em>Lorem ipsum dolor sit amet, consectetur<br />
                        adipiscing elit, sed do eiusmod<br />
                        tempor incididunt.</em>
                    </p>
                </div>
                <div class="col-xs-12 col-md-4 loans">
                    <img src="{{ asset("assets/img/pinboard/loans.png") }}" />
                    <h2>
                        <a href="#loans" data-toggle="tab">Compare Loans and Save Big!</a>
                    </h2>
                    <div class="semi-sub">
                        <p>
                            Find a<br />
                            <em>student loan</em>
                        </p>
                    </div>
                    <hr />
                    <p>
                        <em>Lorem ipsum dolor sit amet, consectetur<br />
	                    adipiscing elit, sed do eiusmod<br />
	                    tempor incididunt.</em>
                    </p>
                </div>
                <div class="col-xs-12 col-md-4 college">
                    <img src="{{ asset("assets/img/pinboard/college.png") }}" />
                    <h2>
                        <a href="#college" data-toggle="tab">College Recommendation</a>
                    </h2>
                    <div class="semi-sub">
                        <p>
                            Find a <em>find best college</em><br />
                            for you
                        </p>
                    </div>
                    <hr />
                    <p>
                        <em>Lorem ipsum dolor sit amet, consectetur<br />
                        adipiscing elit, sed do eiusmod<br />
                        tempor incididunt.</em>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section role="tabpanel" class="tab-pane" id="textbooks">
        <div id="pinboard" class="blueBg">
            <div class="container-fluid skyBlue-bg">
                <div class="container">
                    <ul class="list-inline">
                        <li>
                            <a href="#">Rent Textbooks</a>
                        </li>
                        <li>
                            <a href="#">Buy Textbooks</a>
                        </li>
                        <li>
                            <a href="#">Buy More</a>
                        </li>
                        <li>
                            <a href="#">Sell Textbooks</a>
                        </li>
                        <li>
                            <a href="#">Sell iPhone</a>
                        </li>
                        <li>
                            <a href="#">Sell More</a>
                        </li>
                        <li>
                            <a href="#">Return rental</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <center>
                        <h3>
                            <a href="#">The Cheapest Textbooks</a>
                        </h3>
                        <div class="semi-sub">
                            <p>
                                <em>Save up to 90%</em> on millions of titles

                            </p>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter ISBN, Title or Author">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </div>
                            <p data-container="body" data-toggle="popover" data-placement="bottom" data-original-title=" " data-content="The ISBN is a 10 or 13 digit number that is unique to a particular title, author, edition, and publisher. It can be found on the back cover of each book.">
							What is ISBN?
                        </p>
                            <img src="{{ asset("assets/img/pinboard/textbooks-lg.png") }}" />
                        </center>
                    </div>
                </div>
            </div>
        </section>
        <section role="tabpanel" class="tab-pane fade" id="loans">
            <div class="container">
                <div class="row">
                    <center>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label>Are you a parent or a student?</label>
                                <select placeholder="" class="selectpicker" data-width="100%">
                                    <option value="">--- Select best discription ---</option>
                                    <option value="1">I'm a Collage Student</option>
                                    <option value="2">I'm a Graduate Student</option>
                                    <option value="3">Parent of College Student</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                What college are you attending?
                                </label>
                                <input class="form-control" name="university" type="text">
                            </div>
                            <div class="form-group">
                                <label>
                                    How much do you need to borrow?
                                </label>
                            <input class="form-control" type="number" name="amount" min="0" max="1000000" step="1000" value="1000">
                            </div>
                            <div class="form-group">
                                <label>When will your child graduate college?</label>
                                <select class="selectpicker" data-width="100%" name="enrollment_year">
                                    <option value="">YYYY</option>
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>What is your home ZIP code?</label>
                                <input class="form-control" type="number">
                            </div>
                            <div class="form-group">
                                <label>What is your email addres?</label>
                                <input class="form-control" type="email">
                            </div>
                            <div class="form-group-check">
                                <input name="apply[]" type="checkbox" value="232">
                                <span class="lbl padding-0"></span>
                                <input type="hidden" name="status[]" value="Incomplete">
                                <span class="send-me">Send me loan results and other ways to save</span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-warning btn-lg btn-block center-block text-uppercase">
                                    Find Loans
                                </button>
                            </div>
                        </div>
					</center>
                  <div class="col-sm-7 text-center find-loan">
                        <div class="over-title">
                            <p>Find a student loan</p>
                        </div>
                        <h3>
                            Compare loans and save big!
                        </h3>
                        <div class="under-title">
                            <p>
                                Comparing student loans is the best way to save money on the cost of college
                            </p>
                        </div>
                        <img src="{{ asset("assets/img/pinboard/loans-lg.png") }}" />
                    </div>
                </div>
            </div>
        </section>
        <section role="tabpanel" class="tab-pane fade" id="college">
            <div class="container">
                <div class="row">
                    <center>
                        <div class="col-xs-12">
                            <h3>
                                Nulla facilisis at vero<br />
								eros et accumsan
                            </h3>
                            <div class="semi-sub">
                                <p>
                                    <em>Check out new recommended</em><br />
                                    scholarships for you...
                                </p>
                            </div>
                            <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-lg btn-block center-block text-uppercase">
                                Button
                            </button>
                            </div>
                        </div>
                     </center>
                </div>
            </div>
        </section>
    </div>


<!--No messages-
<div class="col-xs-12">No messages found.<br>Please check back in a few minutes.</div>
END No messages-->

@include('includes/popup')
@stop
