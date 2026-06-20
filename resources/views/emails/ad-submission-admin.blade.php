<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: -apple-system, sans-serif; background: #f8fafc; margin: 0; padding: 40px 20px; }
    .card { background: #fff; border-radius: 16px; max-width: 560px; margin: 0 auto; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
    .header { background: #dc2626; padding: 24px 32px; display: flex; align-items: center; gap: 12px; }
    .header-title { color: #fff; font-size: 1rem; font-weight: 700; margin: 0; }
    .header-sub { color: rgba(255,255,255,.7); font-size: .78rem; margin: 2px 0 0; }
    .body { padding: 28px 32px; }
    .section-label { font-size: .65rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #94a3b8; margin: 0 0 12px; }
    .detail-row { display: flex; gap: 12px; padding: 9px 0; border-bottom: 1px solid #f1f5f9; font-size: .88rem; }
    .detail-row:last-child { border-bottom: none; }
    .dl { color: #94a3b8; min-width: 120px; font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    .dv { color: #0d0d0d; font-weight: 500; word-break: break-word; }
    .desc-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; font-size: .88rem; color: #374151; line-height: 1.7; margin-top: 16px; }
    .cta { margin-top: 24px; text-align: center; }
    .cta a { display: inline-block; background: #0f172a; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 100px; font-size: .85rem; font-weight: 600; }
    .footer { background: #f8fafc; padding: 16px 32px; font-size: .75rem; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <div>
            <p class="header-title">🔔 New Ad Submission</p>
            <p class="header-sub">Submitted {{ $submission->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>
    <div class="body">
        <p class="section-label">Contact Info</p>
        <div class="detail-row"><span class="dl">Name</span><span class="dv">{{ $submission->name }}</span></div>
        <div class="detail-row"><span class="dl">Email</span><span class="dv">{{ $submission->email }}</span></div>
        @if($submission->phone)
        <div class="detail-row"><span class="dl">Phone</span><span class="dv">{{ $submission->phone }}</span></div>
        @endif
        @if($submission->whatsapp)
        <div class="detail-row"><span class="dl">WhatsApp</span><span class="dv">{{ $submission->whatsapp }}</span></div>
        @endif

        <p class="section-label" style="margin-top:20px;">Ad Details</p>
        <div class="detail-row"><span class="dl">Title</span><span class="dv">{{ $submission->title }}</span></div>
        @if($submission->category)
        <div class="detail-row"><span class="dl">Category</span><span class="dv">{{ $submission->category->name }}</span></div>
        @endif
        @if($submission->locality)
        <div class="detail-row"><span class="dl">Locality</span><span class="dv">{{ $submission->locality->name }}</span></div>
        @endif
        @if($submission->company_name)
        <div class="detail-row"><span class="dl">Company</span><span class="dv">{{ $submission->company_name }}</span></div>
        @endif
        @if($submission->location)
        <div class="detail-row"><span class="dl">Location</span><span class="dv">{{ $submission->location }}</span></div>
        @endif
        @if($submission->offer_percentage)
        <div class="detail-row"><span class="dl">Offer</span><span class="dv">{{ $submission->offer_percentage }}% OFF</span></div>
        @endif
        @if($submission->expiry_date)
        <div class="detail-row"><span class="dl">Expiry</span><span class="dv">{{ $submission->expiry_date->format('d M Y') }}</span></div>
        @endif

        @if($submission->description)
        <div class="desc-box">{{ $submission->description }}</div>
        @endif

        <div class="cta">
            <a href="{{ url('/admin/posts') }}">Review in Admin Panel</a>
        </div>
    </div>
    <div class="footer">{{ setting('site_name','DealsHood') }} Admin Notification</div>
</div>
</body>
</html>