<section class="Section Section--primary Schedule-free-call" id="schedule-free-call">
    <div class="container">
        <h2 class="Section__main-title text-center">Book a Free Coaching Session</h2>
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <p class="text-center Util--text-light-secondary">
                    Due to high demand, we ask prospective students to complete
                    a short questionnaire in order to evaluate their need.
                </p>
            </div>
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <hr class="Util--spacer-trans-small">
                {!! Form::open([
                    'route'      => 'front.contact.post',
                    'class'      => 'form-inline text-center',
                    'id'         => 'eligibility-check',
                    'novalidate' => 'novalidate']) !!}

                    @include('front.components.form._book-free-coaching-session')

                {!! Form::close() !!}

                <div id="form-panel"></div>

                @if (url()->current() != route('front.contact.get'))
                    <hr class="Util--spacer-trans-micro">
                    <p class="text-center">
                        <a href="{{ route('front.contact.get') }}" class="small Util--link-blue underline"><u>Have a question? Contact us</u></a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</section>
