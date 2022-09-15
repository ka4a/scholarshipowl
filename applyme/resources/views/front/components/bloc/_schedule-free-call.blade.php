<section class="Section Section--primary Schedule-free-call" id="schedule-free-call">
    <div class="container">
        <h2 class="Section__main-title text-center">Schedule a Free Call</h2>
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <p class="text-center Util--text-light-secondary">
                    To find out more, schedule a free phone consultation.<br>
                    Simply book a time at your convenience and we'll contact
                    you.
                </p>
            </div>
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <hr class="Util--spacer-trans-small">
                <p class="text-center">
                    {!! Form::open([
                        'route'      => 'front.contact.post',
                        'class'      => 'form',
                        'novalidate' => 'novalidate']) !!}

                        @include('front.components.form._schedule-free-call')

                    {!! Form::close() !!}
                </p>
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
