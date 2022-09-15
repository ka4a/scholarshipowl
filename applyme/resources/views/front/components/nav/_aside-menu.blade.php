<nav id="nav-menu" class="Menu">

    <ul>
        <li class="Menu--item {{ active_class(if_route(['front.index']), 'Selected') }}">
            <a
                href="{{ route('front.index') }}"
                title="">Home</a>
        </li>

        <li class="Menu--item">
            <a
                href="{{ route('front.features.index') }}"
                title="">Features</a>
            <ul>
                <li class="Menu--item {{ active_class(if_route(['front.features.courses']), 'Selected') }}">
                    <a
                        href="{{ route('front.features.courses') }}"
                        title="">Courses</a>
                </li>
                <li class="Menu--item {{ active_class(if_route(['front.features.admissions-coaching']), 'Selected') }}">
                    <a
                        href="{{ route('front.features.admissions-coaching') }}"
                        title="">Admissions Coaching</a>
                </li>
                <li class="Menu--item {{ active_class(if_route(['front.features.essay-assistance']), 'Selected') }}">
                    <a
                        href="{{ route('front.features.essay-assistance') }}"
                        title="">Essay Assistance</a>
                </li>
                <li class="Menu--item {{ active_class(if_route(['front.features.interview-preparation']), 'Selected') }}">
                    <a
                        href="{{ route('front.features.interview-preparation') }}"
                        title="">Interview Preparation</a>
                </li>
                <li class="Menu--item {{ active_class(if_route(['front.features.personalized-scholarships-list']), 'Selected') }}">
                    <a
                        href="{{ route('front.features.personalized-scholarships-list') }}"
                        title="">Personalized Scholarships List</a>
                </li>
                <li class="Menu--item {{ active_class(if_route(['front.features.guidance-for-parents']), 'Selected') }}">
                    <a
                        href="{{ route('front.features.guidance-for-parents') }}"
                        title="">Guidance For Parents</a>
                </li>
            </ul>
        </li>

        <li class="Menu--item">
            <a
                href="https://academy.apply.me"
                target="_blank"
                title="Apply.Me Academy"
                class="nav-item">Academy</a>
        </li>

        <li class="Menu--item {{ active_class(if_route(['front.pricing']), 'Selected') }}">
            <a
                href="{{ route('front.pricing') }}"
                title="">Pricing</a>
        </li>

        <li class="Menu--item {{ active_class(if_route(['front.about-us']), 'Selected') }}">
            <a
                href="{{ route('front.about-us') }}"
                title="">About Us</a>
        </li>

        <li class="Menu--item {{ active_class(if_route(['front.faq']), 'Selected') }}">
            <a
                href="{{ route('front.faq') }}"
                title="">FAQ</a>
        </li>

        <li class="Menu--item {{ active_class(if_route(['front.contact.get']), 'Selected') }}">
            <a
                href="{{ route('front.contact.get') }}"
                title="">Contact Us</a>
        </li>

    </ul>

</nav>
