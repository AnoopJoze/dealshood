<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us — DealsHood</title>
    <link rel="icon" type="image/png" href="{{ site_favicon_url() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">
    <style>
    :root{--ink:#0f172a;--ink-2:#374151;--ink-3:#64748b;
          --surf:#f8fafc;--surf-2:#f1f5f9;--surf-3:#e2e8f0;
          --white:#fff;--accent:#0f3f7e;--r:12px;--nav-h:60px;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
         background:var(--surf);color:var(--ink);}

    .nav{position:sticky;top:0;height:var(--nav-h);
         background:rgba(255,255,255,.95);backdrop-filter:blur(16px);
         border-bottom:1px solid var(--surf-3);z-index:800;
         display:flex;align-items:center;}
    .nav-inner{display:flex;align-items:center;justify-content:space-between;
               width:100%;max-width:1100px;margin:0 auto;padding:0 20px;}
    .nav-logo img{height:38px;}
    .nav-back{display:flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;
              color:var(--ink);text-decoration:none;padding:7px 14px;border-radius:100px;
              background:var(--surf-2);border:1px solid var(--surf-3);}
    .nav-back:hover{background:var(--surf-3);}

    .page-hero{background:linear-gradient(135deg,#0f172a,#0f3f7e);
               padding:48px 20px 56px;text-align:center;position:relative;overflow:hidden;}
    .page-hero::before{content:'';position:absolute;inset:0;
                       background:url('/frontend/img/illustrations/IMG_4871.png') center/cover;opacity:.12;}
    .hero-content{position:relative;z-index:1;}
    .hero-icon{width:60px;height:60px;border-radius:50%;margin:0 auto 16px;
               background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.2);
               display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
    .page-hero h1{font-size:clamp(1.6rem,3vw,2rem);font-weight:800;color:#fff;
                  letter-spacing:-.02em;margin-bottom:6px;}
    .page-hero p{font-size:.88rem;color:rgba(255,255,255,.55);}
    .hero-wave{position:absolute;bottom:-1px;left:0;right:0;line-height:0;}
    .hero-wave svg{display:block;width:100%;}

    .page-body{max-width:1000px;margin:0 auto;padding:40px 20px 100px;}
    .grid{display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;}
    @media(max-width:860px){.grid{grid-template-columns:1fr;}}

    .card{background:var(--white);border-radius:18px;border:1px solid var(--surf-3);
          box-shadow:0 1px 4px rgba(0,0,0,.06),0 4px 16px rgba(0,0,0,.07);overflow:hidden;}
    .card-hd{padding:18px 22px;border-bottom:1px solid var(--surf-2);
             display:flex;align-items:center;gap:10px;}
    .card-hd-icon{width:32px;height:32px;border-radius:9px;flex-shrink:0;
                  display:flex;align-items:center;justify-content:center;font-size:.82rem;}
    .card-hd-title{font-size:.82rem;font-weight:700;color:var(--ink);}
    .card-body{padding:22px;}

    .f{margin-bottom:16px;}
    .f label{display:block;font-size:.76rem;font-weight:600;color:var(--ink-2);margin-bottom:5px;}
    .f-wrap{position:relative;}
    .f-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);
            color:#94a3b8;font-size:.76rem;pointer-events:none;}
    .f-icon-top{position:absolute;left:12px;top:13px;color:#94a3b8;font-size:.76rem;pointer-events:none;}
    .f-input{width:100%;padding:11px 14px 11px 36px;
             border:1.5px solid var(--surf-3);border-radius:var(--r);
             font-size:.87rem;color:var(--ink);background:#fff;outline:none;
             transition:border-color .15s,box-shadow .15s;font-family:inherit;}
    .f-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(15,63,126,.1);}
    .f-select{padding-left:36px;}
    textarea.f-input{padding-left:36px;resize:vertical;min-height:130px;}

    .btn-submit{width:100%;padding:13px;border:none;border-radius:var(--r);
                background:var(--ink);color:#fff;font-size:.9rem;font-weight:700;
                cursor:pointer;display:flex;align-items:center;justify-content:center;
                gap:8px;transition:background .15s,transform .15s;font-family:inherit;}
    .btn-submit:hover{background:var(--accent);transform:translateY(-1px);}

    .contact-row{display:flex;align-items:center;gap:14px;padding:14px 0;
                 border-bottom:1px solid var(--surf-2);}
    .contact-row:last-child{border-bottom:none;}
    .c-icon{width:40px;height:40px;border-radius:11px;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;font-size:.9rem;}
    .c-label{font-size:.74rem;font-weight:600;color:var(--ink-3);}
    .c-val{font-size:.88rem;font-weight:500;color:var(--ink);}
    .c-val a{color:var(--ink);text-decoration:none;}
    .c-val a:hover{color:var(--accent);}

    .alert-ok{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:var(--r);
              padding:12px 16px;font-size:.83rem;color:#15803d;
              display:flex;align-items:center;gap:8px;margin-bottom:16px;}
    .alert-err{background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r);
               padding:12px 16px;font-size:.83rem;color:#b91c1c;
               display:flex;align-items:center;gap:8px;margin-bottom:16px;}

    .spin{width:15px;height:15px;border-radius:50%;display:none;
          border:2px solid rgba(255,255,255,.35);border-top-color:#fff;
          animation:spin .6s linear infinite;}
    @keyframes spin{to{transform:rotate(360deg);}}
    @media(max-width:768px){.page-body{padding:20px 14px 120px;}}
    </style>
</head>
<body>

<nav class="nav">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-back">
            <i class="fas fa-chevron-left" style="font-size:.76rem;"></i> Home
        </a>
        <a href="{{ route('home') }}" class="nav-logo">
            <img src="{{ site_logo_url() }}" alt="{{ setting('site_name', 'DealsHood') }}">
        </a>
        <div style="width:80px;"></div>
    </div>
</nav>

<div class="page-hero">
    <div class="hero-content">
        <div class="hero-icon"><i class="fas fa-envelope-open-text"></i></div>
        <h1>Get in Touch</h1>
        <p>We usually respond within a few hours.</p>
    </div>
    <div class="hero-wave">
        <svg viewBox="0 0 1440 48" fill="none">
            <path d="M0 48H1440V24C1200 48 960 0 720 0C480 0 240 48 0 24V48Z" fill="#f8fafc"/>
        </svg>
    </div>
</div>

<div class="page-body">
<div class="grid">

    {{-- Form --}}
    <div class="card">
        <div class="card-hd">
            <span class="card-hd-icon" style="background:rgba(15,63,126,.08);color:var(--accent);">
                <i class="fas fa-paper-plane"></i>
            </span>
            <span class="card-hd-title">Send us a Message</span>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert-ok">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert-err">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}" id="contactForm">
                @csrf

                <div class="f">
                    <label>Your Name</label>
                    <div class="f-wrap">
                        <i class="fas fa-user f-icon"></i>
                        <input type="text" name="name" class="f-input"
                               value="{{ old('name', auth()->user()?->name) }}"
                               placeholder="John Smith" required>
                    </div>
                </div>

                <div class="f">
                    <label>Email Address</label>
                    <div class="f-wrap">
                        <i class="fas fa-envelope f-icon"></i>
                        <input type="email" name="email" class="f-input"
                               value="{{ old('email', auth()->user()?->email) }}"
                               placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="f">
                    <label>Subject</label>
                    <div class="f-wrap">
                        <i class="fas fa-tag f-icon"></i>
                        <select name="subject" class="f-input f-select" required>
                            <option value="">Select a topic…</option>
                            <option value="General Inquiry" {{ old('subject')=='General Inquiry'?'selected':'' }}>General Inquiry</option>
                            <option value="Advertise with Us" {{ old('subject')=='Advertise with Us'?'selected':'' }}>Advertise with Us</option>
                            <option value="Report a Deal" {{ old('subject')=='Report a Deal'?'selected':'' }}>Report a Deal</option>
                            <option value="Account Issue" {{ old('subject')=='Account Issue'?'selected':'' }}>Account Issue</option>
                            <option value="Partnership" {{ old('subject')=='Partnership'?'selected':'' }}>Partnership</option>
                            <option value="Other" {{ old('subject')=='Other'?'selected':'' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="f">
                    <label>Message</label>
                    <div class="f-wrap">
                        <i class="fas fa-comment f-icon-top"></i>
                        <textarea name="message" class="f-input"
                                  placeholder="Tell us how we can help…"
                                  required>{{ old('message') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="sendBtn">
                    <span id="sendTxt"><i class="fas fa-paper-plane"></i> &nbsp;Send Message</span>
                    <span class="spin" id="sendSpin"></span>
                </button>
            </form>
        </div>
    </div>

    {{-- Contact Info --}}
    <div>
        <div class="card">
            <div class="card-hd">
                <span class="card-hd-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="fas fa-address-card"></i>
                </span>
                <span class="card-hd-title">Contact Information</span>
            </div>
            <div class="card-body" style="padding:8px 20px 16px;">
                @if(setting('contact_email'))
                <div class="contact-row">
                    <span class="c-icon" style="background:#eff6ff;color:#3b82f6;">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <div>
                        <div class="c-label">Email</div>
                        <div class="c-val">
                            <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if(setting('contact_phone'))
                <div class="contact-row">
                    <span class="c-icon" style="background:#f0fdf4;color:#16a34a;">
                        <i class="fas fa-phone"></i>
                    </span>
                    <div>
                        <div class="c-label">Phone</div>
                        <div class="c-val">
                            <a href="tel:{{ setting('contact_phone') }}">{{ setting('contact_phone') }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if(setting('whatsapp_number'))
                <div class="contact-row">
                    <span class="c-icon" style="background:#f0fdf4;color:#25d366;">
                        <i class="fab fa-whatsapp"></i>
                    </span>
                    <div>
                        <div class="c-label">WhatsApp</div>
                        <div class="c-val">
                            <a href="https://wa.me/{{ setting('whatsapp_number') }}"
                               target="_blank">Chat on WhatsApp</a>
                        </div>
                    </div>
                </div>
                @endif

                @if(setting('address'))
                <div class="contact-row">
                    <span class="c-icon" style="background:#fffbeb;color:#d97706;">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <div>
                        <div class="c-label">Address</div>
                        <div class="c-val">{{ setting('address') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Social links --}}
        <div class="card" style="margin-top:16px;padding:18px 20px;">
            <div style="font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
                        color:var(--ink-3);margin-bottom:14px;">Follow Us</div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                @if(setting('instagram_url'))
                    <a href="{{ setting('instagram_url') }}" target="_blank"
                       style="width:38px;height:38px;border-radius:10px;background:#fce7f3;color:#db2777;
                              display:flex;align-items:center;justify-content:center;font-size:.95rem;text-decoration:none;">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                @if(setting('facebook_url'))
                    <a href="{{ setting('facebook_url') }}" target="_blank"
                       style="width:38px;height:38px;border-radius:10px;background:#eff6ff;color:#1877f2;
                              display:flex;align-items:center;justify-content:center;font-size:.95rem;text-decoration:none;">
                        <i class="fab fa-facebook"></i>
                    </a>
                @endif
                @if(setting('whatsapp_number'))
                    <a href="https://wa.me/{{ setting('whatsapp_number') }}" target="_blank"
                       style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;color:#25d366;
                              display:flex;align-items:center;justify-content:center;font-size:.95rem;text-decoration:none;">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>
</div>

@include('frontend.frontend-mobile')

<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script>
document.getElementById('contactForm').addEventListener('submit', function(){
    document.getElementById('sendTxt').style.display = 'none';
    document.getElementById('sendSpin').style.display = 'block';
    document.getElementById('sendBtn').disabled = true;
});
</script>
</body>
</html>