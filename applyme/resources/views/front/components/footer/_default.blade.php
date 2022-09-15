<footer class="Section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <p class="text-center">
                    <a
                        href="{{ route('front.index') }}"
                        title=""
                        class="text-center">
                        <img src="/imgs/am-logo.png" alt="" height="40">
                    </a>
                </p>
                <h2 class="text-center">Good luck Applying!</h2>
                <hr class="Util--spacer-trans-micro">
                <ul class="list-inline text-center footer-nav">
                    <li><a href="{{ route('front.index') }}" title="">Home</a></li>
                    <li><a href="{{ route('front.pricing') }}" title="">Pricing</a></li>
                    <li><a href="{{ route('front.about-us') }}" title="">About Us</a></li>
                    <li><a href="{{ route('front.contact.get') }}" title="">Contact</a></li>
                    <li><a href="{{ route('front.faq') }}" title="">FAQ</a></li>
                    <li><a href="{{ route('front.privacy-policy') }}" title="">Privacy Policy</a></li>
                    <li><a href="{{ route('front.terms-of-use') }}" title="">Terms of Use</a></li>
                    <li><a href="{{ route('front.sitemap') }}" title="">Sitemap</a></li>
                </ul>
                <hr class="Util--spacer-trans-small">
                <ul class="list-inline text-center social-icons">
                    <li><a href="https://www.facebook.com/applyme" target="_blank"><i class="fab fa-facebook-square fa-3x"></i></a></li>
                    <li><a href="https://www.pinterest.com/applyme" target="_blank"><i class="fab fa-pinterest-square fa-3x"></i></a></li>
                    <li><a href="https://twitter.com/applymeapp" target="_blank"><i class="fab fa-twitter-square fa-3x"></i></a></li>
                    <li><a href="https://www.instagram.com/applyme" target="_blank"><i class="fab fa-instagram fa-3x"></i></a></li>
                </ul>
                <hr class="Util--spacer-trans-small">
                <p class="text-center copyrights">
                    <span class="Util--text-light-primary Util--text-smaller Util--block">© {{ date('Y') }} Apply.me <br>All Rights Reserved by Apply Me Inc.</span>
                </p>
            </div>
        </div>
    </div>
</footer>
