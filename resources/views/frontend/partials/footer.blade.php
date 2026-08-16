{{--
    Shared site footer — identical on every public-facing page.
    Include with: @include('frontend.partials.footer')

    Optional variable:
      $categories   Collection of categories (falls back to empty)
--}}
@php
    $categories   = $categories ?? collect();
    $footerSiteName = setting('site_name', 'DealsHood');
@endphp

<footer class="dh-footer">
    <div class="wrap">
        <div class="dh-footer-grid">
            <div>
                <div class="dh-footer-logo"><img src="{{ site_logo_url() }}" alt="{{ $footerSiteName }}"></div>
                <p class="dh-footer-tag">Your most trusted local deals, offers and services platform. Save smarter, shop happier.</p>
                <div class="dh-footer-social">
                    <a href="https://www.instagram.com/dealshood" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/share/1DA56kRCJp" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://wa.me/918086087050" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div>
                <p class="dh-footer-col-title">Company</p>
                <ul class="dh-footer-links">
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('home') }}#categories">Categories</a></li>
                    <li><a href="{{ route('home') }}#trending">Trending</a></li>
                    <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                </ul>
            </div>
            <div>
                <p class="dh-footer-col-title">Categories</p>
                <ul class="dh-footer-links">
                    @forelse($categories->take(5) as $cat)
                        <li><a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                    @empty
                        <li><a href="{{ route('posts.listing') }}">Browse all</a></li>
                    @endforelse
                </ul>
            </div>
            <div>
                <p class="dh-footer-col-title">Resources</p>
                <ul class="dh-footer-links">
                    <li><a href="{{ route('posts.listing') }}">All Deals</a></li>
                    <li><a href="{{ route('contact') }}">Help Center</a></li>
                    <li><a href="{{ route('home') }}#faq">Privacy</a></li>
                    <li><a href="{{ route('home') }}#faq">Terms</a></li>
                </ul>
            </div>
        </div>
        <div class="dh-footer-bottom">
            <span>&copy; <span id="footerYear"></span> {{ $footerSiteName }}. All rights reserved.</span>
            <span>Made with ♥ locally</span>
        </div>
    </div>
</footer>
<script>document.getElementById('footerYear').textContent = new Date().getFullYear();</script>
