<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: -apple-system, sans-serif; background: #f8fafc; margin: 0; padding: 40px 20px; color: #0d0d0d; }
    .card { background: #fff; border-radius: 16px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
    .header { background: #0f172a; padding: 32px; text-align: center; }
    .header img { height: 36px; }
    .body { padding: 32px; }
    .title { font-size: 1.2rem; font-weight: 700; margin: 0 0 8px; }
    .sub { color: #6b7280; font-size: .9rem; margin: 0 0 24px; }
    .detail-row { display: flex; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: .88rem; }
    .detail-row:last-child { border-bottom: none; }
    .dl { color: #94a3b8; min-width: 110px; font-weight: 600; font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; }
    .dv { color: #0d0d0d; font-weight: 500; }
    .badge { display: inline-block; background: #fef3c7; color: #d97706; font-size: .72rem; font-weight: 700; padding: 4px 12px; border-radius: 100px; margin-bottom: 20px; }
    .footer { background: #f8fafc; padding: 20px 32px; font-size: .78rem; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <img src="{{ url('/frontend/img/dealshood.png') }}" alt="{{ setting('site_name','DealsHood') }}"
             style="filter:brightness(0) invert(1);">
    </div>
    <div class="body">
        <span class="badge">⏳ Under Review</span>
        <p class="title">We've received your ad, {{ $submission->name }}!</p>
        <p class="sub">Our team will review your submission and get back to you within 24 hours.</p>

        <div class="detail-row">
            <span class="dl">Ad Title</span>
            <span class="dv">{{ $submission->title }}</span>
        </div>
        @if($submission->category)
        <div class="detail-row">
            <span class="dl">Category</span>
            <span class="dv">{{ $submission->category->name }}</span>
        </div>
        @endif
        @if($submission->locality)
        <div class="detail-row">
            <span class="dl">Locality</span>
            <span class="dv">{{ $submission->locality->name }}</span>
        </div>
        @endif
        @if($submission->company_name)
        <div class="detail-row">
            <span class="dl">Company</span>
            <span class="dv">{{ $submission->company_name }}</span>
        </div>
        @endif
        @if($submission->offer_percentage)
        <div class="detail-row">
            <span class="dl">Offer</span>
            <span class="dv">{{ $submission->offer_percentage }}% OFF</span>
        </div>
        @endif
    </div>
    <div class="footer">
        © {{ date('Y') }} {{ setting('site_name','DealsHood') }}. You received this because you submitted an ad on our platform.
    </div>
</div>
</body>
</html>