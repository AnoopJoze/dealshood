<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Ad Submitted — DealsHood</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            background: #f1f5f9; color: #0f172a; line-height: 1.6;
        }
        .wrapper { max-width: 600px; margin: 32px auto; padding: 0 16px 48px; }

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #0f3f7e 100%);
            border-radius: 16px 16px 0 0;
            padding: 32px 36px;
            text-align: center;
        }
        .header img { height: 36px; filter: brightness(0) invert(1); opacity: .9; }
        .header h1 {
            font-size: 1.25rem; font-weight: 800; color: #fff;
            margin-top: 16px; letter-spacing: -.015em;
        }
        .header p { font-size: .85rem; color: rgba(255,255,255,.55); margin-top: 4px; }

        /* ── Body card ── */
        .body {
            background: #fff;
            padding: 32px 36px;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }

        .greeting {
            font-size: .95rem; color: #334155; margin-bottom: 20px;
        }

        /* ── Post preview card ── */
        .post-card {
            border: 1.5px solid #e2e8f0; border-radius: 12px;
            overflow: hidden; margin-bottom: 24px;
        }
        @if($post->getFirstMediaUrl('posts'))
        .post-img {
            width: 100%; height: 200px; object-fit: cover; display: block;
        }
        @endif
        .post-body { padding: 18px 20px; }
        .post-category {
            display: inline-block;
            font-size: .65rem; font-weight: 700; letter-spacing: .09em;
            text-transform: uppercase; color: #0f3f7e;
            background: rgba(15,63,126,.08); border-radius: 100px;
            padding: 3px 10px; margin-bottom: 10px;
        }
        .post-title {
            font-size: 1.1rem; font-weight: 800; color: #0f172a;
            margin-bottom: 10px; line-height: 1.3;
        }
        .post-meta {
            display: flex; flex-wrap: wrap; gap: 12px;
            font-size: .78rem; color: #64748b; margin-bottom: 12px;
        }
        .post-meta span { display: flex; align-items: center; gap: 4px; }
        .post-desc {
            font-size: .84rem; color: #475569; line-height: 1.65;
            border-top: 1px solid #f1f5f9; padding-top: 12px; margin-top: 4px;
        }

        /* ── Info table ── */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .info-table td { padding: 9px 12px; font-size: .84rem; border-bottom: 1px solid #f1f5f9; }
        .info-table td:first-child { color: #64748b; font-weight: 600; width: 130px; white-space: nowrap; }
        .info-table td:last-child { color: #0f172a; }
        .info-table tr:last-child td { border-bottom: none; }

        /* ── Action buttons ── */
        .actions { text-align: center; margin: 28px 0 8px; }
        .btn {
            display: inline-block;
            padding: 13px 28px; border-radius: 100px;
            font-size: .88rem; font-weight: 700;
            text-decoration: none; margin: 0 6px 10px;
            letter-spacing: .01em;
        }
        .btn-publish {
            background: #0f3f7e; color: #fff;
        }
        .btn-view {
            background: #f1f5f9; color: #0f172a;
            border: 1.5px solid #e2e8f0;
        }

        /* ── Alert box ── */
        .alert {
            background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px;
            padding: 12px 16px; font-size: .82rem; color: #92400e;
            margin-bottom: 20px; display: flex; align-items: flex-start; gap: 8px;
        }
        .alert-icon { flex-shrink: 0; margin-top: 1px; }

        /* ── Footer ── */
        .footer {
            background: #f8fafc;
            border: 1px solid #e2e8f0; border-top: none;
            border-radius: 0 0 16px 16px;
            padding: 20px 36px; text-align: center;
            font-size: .75rem; color: #94a3b8;
        }
        .footer a { color: #0f3f7e; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <img src="{{ asset('frontend/img/dealshood.png') }}" alt="DealsHood">
        <h1>New Ad Submitted for Review</h1>
        <p>Action required — review and publish or reject</p>
    </div>

    {{-- Body --}}
    <div class="body">

        <p class="greeting">
            Hi Admin,<br><br>
            A new deal has been submitted by
            <strong>{{ $post->user?->name ?? 'Unknown user' }}</strong>
            and is waiting for your review before it goes live.
        </p>

        {{-- Post preview card --}}
        <div class="post-card">
            @if($post->getFirstMediaUrl('posts'))
                <img src="{{ $post->getFirstMediaUrl('posts') }}" class="post-img" alt="{{ $post->title }}">
            @endif
            <div class="post-body">
                @if($post->category)
                    <div class="post-category">{{ $post->category->name }}</div>
                @endif
                <div class="post-title">{{ $post->title }}</div>
                <div class="post-meta">
                    @if($post->locality)
                        <span>📍 {{ $post->locality->name }}</span>
                    @endif
                    @if($post->expiry_date)
                        <span>📅 Expires {{ \Carbon\Carbon::parse($post->expiry_date)->format('d M Y') }}</span>
                    @endif
                    <span>🕒 {{ $post->created_at->format('d M Y, H:i') }}</span>
                </div>
                @if($post->description)
                    <div class="post-desc">
                        {{ Str::limit(strip_tags($post->description), 200) }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Submitted by info --}}
        <table class="info-table">
            <tr>
                <td>Submitted by</td>
                <td><strong>{{ $post->user?->name ?? '—' }}</strong></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $post->user?->email ?? '—' }}</td>
            </tr>
            @if($post->phone_number)
            <tr>
                <td>Phone</td>
                <td>{{ $post->phone_number }}</td>
            </tr>
            @endif
            @if($post->company_name)
            <tr>
                <td>Company</td>
                <td>{{ $post->company_name }}</td>
            </tr>
            @endif
            <tr>
                <td>Category</td>
                <td>{{ $post->category?->name ?? '—' }}</td>
            </tr>
            @if($post->subcategory)
            <tr>
                <td>Subcategory</td>
                <td>{{ $post->subcategory->name }}</td>
            </tr>
            @endif
            @if($post->locality)
            <tr>
                <td>Locality</td>
                <td>{{ $post->locality->name }}</td>
            </tr>
            @endif
            <tr>
                <td>Current Status</td>
                <td>
                    <span style="background:#fef3c7;color:#92400e;padding:2px 9px;border-radius:100px;font-size:.75rem;font-weight:700;">
                        DRAFT — Pending Review
                    </span>
                </td>
            </tr>
            <tr>
                <td>Post ID</td>
                <td style="color:#94a3b8;">#{{ $post->id }}</td>
            </tr>
        </table>

        {{-- Alert --}}
        <div class="alert">
            <span class="alert-icon">⚠️</span>
            <div>
                This ad is currently saved as <strong>Draft</strong> and is not visible to the public.
                Please review the content and publish it if it meets your guidelines.
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="actions">
            <a href="{{ url('/admin/posts/' . $post->id) }}" class="btn btn-publish">
                📋 &nbsp;Review &amp; Publish
            </a>
            <a href="{{ url('/admin/posts') }}" class="btn btn-view">
                View All Pending Ads
            </a>
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>This email was sent automatically by <a href="{{ url('/') }}">DealsHood</a>.</p>
        <p style="margin-top:6px;">You are receiving this because you are an admin of DealsHood.</p>
    </div>

</div>
</body>
</html>