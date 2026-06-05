@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4 px-4">

{{-- ── Header ── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-1 fw-bold" style="font-size:1.25rem;">
            <i class="fas fa-cog me-2" style="color:#0f3f7e;"></i> Site Settings
        </h4>
        <p class="text-muted mb-0" style="font-size:.83rem;">
            Manage your website identity, SEO, contact details and more.
        </p>
    </div>
    <button form="settingsForm" type="submit" class="btn btn-dark btn-sm px-4" style="border-radius:100px;font-weight:600;">
        <i class="fas fa-save me-1"></i> Save Changes
    </button>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:12px;">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
@endif

<form id="settingsForm"
      method="POST"
      action="{{ route('admin.settings.update') }}"
      enctype="multipart/form-data">
@csrf

<div class="row g-4">

{{-- ════════════════════════════════════
     LEFT COLUMN
════════════════════════════════════ --}}
<div class="col-lg-8">

    {{-- ── Brand & Identity ── --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width:30px;height:30px;border-radius:8px;background:rgba(15,63,126,.08);
                             color:#0f3f7e;display:flex;align-items:center;justify-content:center;font-size:.8rem;">
                    <i class="fas fa-paint-brush"></i>
                </span>
                <h6 class="mb-0 fw-bold">Brand & Identity</h6>
            </div>
            <p class="text-muted mb-0" style="font-size:.78rem;padding-left:38px;">
                Your site name, tagline and visual assets.
            </p>
        </div>
        <div class="card-body px-4 pt-3 pb-4">

            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Site Name <span class="text-danger">*</span></label>
                    <input type="text" name="site_name" class="form-control form-control-sm"
                           value="{{ $settings['site_name'] ?? 'DealsHood' }}"
                           placeholder="e.g. DealsHood" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Tagline</label>
                    <input type="text" name="site_tagline" class="form-control form-control-sm"
                           value="{{ $settings['site_tagline'] ?? '' }}"
                           placeholder="Discover the best deals near you.">
                </div>
            </div>

            {{-- Logo --}}
            <div class="row g-3 mt-1">
                <div class="col-sm-4">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Site Logo</label>
                    @if(!empty($settings['site_logo']))
                        <div class="mb-2 p-2 border rounded-3 d-inline-flex align-items-center gap-2"
                             style="background:#f8fafc;">
                            <img src="{{ Storage::url($settings['site_logo']) }}"
                                 alt="Logo" style="height:36px;object-fit:contain;">
                            <form method="POST"
                                  action="{{ route('admin.settings.remove-file','site_logo') }}"
                                  class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0"
                                        title="Remove logo"
                                        onclick="return confirm('Remove logo?')">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </form>
                        </div><br>
                    @endif
                    <input type="file" name="site_logo" class="form-control form-control-sm"
                           accept="image/png,image/jpeg,image/svg+xml,image/webp">
                    <div class="form-text">PNG / SVG recommended. Max 2MB.</div>
                </div>

                <div class="col-sm-4">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Favicon</label>
                    @if(!empty($settings['site_favicon']))
                        <div class="mb-2 p-2 border rounded-3 d-inline-flex align-items-center gap-2"
                             style="background:#f8fafc;">
                            <img src="{{ Storage::url($settings['site_favicon']) }}"
                                 alt="Favicon" style="height:24px;object-fit:contain;">
                            <form method="POST"
                                  action="{{ route('admin.settings.remove-file','site_favicon') }}"
                                  class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0"
                                        onclick="return confirm('Remove favicon?')">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </form>
                        </div><br>
                    @endif
                    <input type="file" name="site_favicon" class="form-control form-control-sm"
                           accept="image/png,image/x-icon,image/svg+xml">
                    <div class="form-text">PNG / ICO. Max 512KB.</div>
                </div>

                <div class="col-sm-4">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Default OG Image</label>
                    @if(!empty($settings['og_image']))
                        <div class="mb-2 p-2 border rounded-3 d-inline-flex align-items-center gap-2"
                             style="background:#f8fafc;">
                            <img src="{{ Storage::url($settings['og_image']) }}"
                                 alt="OG Image" style="height:36px;object-fit:cover;border-radius:4px;">
                            <form method="POST"
                                  action="{{ route('admin.settings.remove-file','og_image') }}"
                                  class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0"
                                        onclick="return confirm('Remove OG image?')">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </form>
                        </div><br>
                    @endif
                    <input type="file" name="og_image" class="form-control form-control-sm"
                           accept="image/png,image/jpeg,image/webp">
                    <div class="form-text">1200×630px recommended. Max 3MB.</div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── SEO & Meta ── --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width:30px;height:30px;border-radius:8px;background:#f0fdf4;
                             color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:.8rem;">
                    <i class="fas fa-search"></i>
                </span>
                <h6 class="mb-0 fw-bold">SEO & Meta Tags</h6>
            </div>
            <p class="text-muted mb-0" style="font-size:.78rem;padding-left:38px;">
                Default values used on pages without custom meta.
            </p>
        </div>
        <div class="card-body px-4 pt-3 pb-4">

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.8rem;">
                    Meta Title
                    <span class="text-muted fw-normal ms-1" id="metaTitleCount" style="font-size:.72rem;"></span>
                </label>
                <input type="text" name="meta_title" id="metaTitle" class="form-control form-control-sm"
                       value="{{ $settings['meta_title'] ?? '' }}"
                       placeholder="DealsHood — Discover the Best Deals Near You"
                       maxlength="160">
                <div class="form-text">Recommended: 50–60 characters.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.8rem;">
                    Meta Description
                    <span class="text-muted fw-normal ms-1" id="metaDescCount" style="font-size:.72rem;"></span>
                </label>
                <textarea name="meta_description" id="metaDesc" class="form-control form-control-sm"
                          rows="3" maxlength="300"
                          placeholder="Find great offers from your neighbourhood, every day.">{{ $settings['meta_description'] ?? '' }}</textarea>
                <div class="form-text">Recommended: 150–160 characters.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control form-control-sm"
                       value="{{ $settings['meta_keywords'] ?? '' }}"
                       placeholder="deals, offers, local deals, discounts">
                <div class="form-text">Comma-separated keywords.</div>
            </div>

            <div class="mb-0">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Google Analytics ID</label>
                <input type="text" name="google_analytics_id" class="form-control form-control-sm"
                       value="{{ $settings['google_analytics_id'] ?? '' }}"
                       placeholder="G-XXXXXXXXXX or UA-XXXXXXXX-X">
            </div>

        </div>
    </div>

    {{-- ── Contact ── --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width:30px;height:30px;border-radius:8px;background:#eff6ff;
                             color:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:.8rem;">
                    <i class="fas fa-address-book"></i>
                </span>
                <h6 class="mb-0 fw-bold">Contact Details</h6>
            </div>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Contact Email</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="contact_email" class="form-control"
                               value="{{ $settings['contact_email'] ?? '' }}"
                               placeholder="admin@dealshood.com">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Contact Phone</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="contact_phone" class="form-control"
                               value="{{ $settings['contact_phone'] ?? '' }}"
                               placeholder="+91 80860 87050">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">WhatsApp Number</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="color:#25d366;"><i class="fab fa-whatsapp"></i></span>
                        <input type="text" name="whatsapp_number" class="form-control"
                               value="{{ $settings['whatsapp_number'] ?? '' }}"
                               placeholder="918086087050 (with country code, no +)">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Address</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="address" class="form-control"
                               value="{{ $settings['address'] ?? '' }}"
                               placeholder="City, Country">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Social Media ── --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width:30px;height:30px;border-radius:8px;background:#fdf4ff;
                             color:#a21caf;display:flex;align-items:center;justify-content:center;font-size:.8rem;">
                    <i class="fas fa-share-alt"></i>
                </span>
                <h6 class="mb-0 fw-bold">Social Media Links</h6>
            </div>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Instagram</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="color:#e1306c;"><i class="fab fa-instagram"></i></span>
                        <input type="url" name="instagram_url" class="form-control"
                               value="{{ $settings['instagram_url'] ?? '' }}"
                               placeholder="https://instagram.com/dealshood">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Facebook</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="color:#1877f2;"><i class="fab fa-facebook"></i></span>
                        <input type="url" name="facebook_url" class="form-control"
                               value="{{ $settings['facebook_url'] ?? '' }}"
                               placeholder="https://facebook.com/dealshood">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">Twitter / X</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                        <input type="url" name="twitter_url" class="form-control"
                               value="{{ $settings['twitter_url'] ?? '' }}"
                               placeholder="https://twitter.com/dealshood">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold" style="font-size:.8rem;">YouTube</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="color:#dc2626;"><i class="fab fa-youtube"></i></span>
                        <input type="url" name="youtube_url" class="form-control"
                               value="{{ $settings['youtube_url'] ?? '' }}"
                               placeholder="https://youtube.com/@dealshood">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Footer ── --}}
    <div class="card border-0 shadow-sm" style="border-radius:16px;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width:30px;height:30px;border-radius:8px;background:#f8fafc;
                             color:#475569;display:flex;align-items:center;justify-content:center;font-size:.8rem;">
                    <i class="fas fa-align-center"></i>
                </span>
                <h6 class="mb-0 fw-bold">Footer</h6>
            </div>
        </div>
        <div class="card-body px-4 pt-3 pb-4">
            <label class="form-label fw-semibold" style="font-size:.8rem;">Footer Copyright Text</label>
            <input type="text" name="footer_text" class="form-control form-control-sm"
                   value="{{ $settings['footer_text'] ?? '' }}"
                   placeholder="© 2025 DealsHood. All rights reserved.">
        </div>
    </div>

</div>{{-- /col-lg-8 --}}

{{-- ════════════════════════════════════
     RIGHT COLUMN — Sticky sidebar
════════════════════════════════════ --}}
<div class="col-lg-4">
<div style="position:sticky;top:80px;">

    {{-- ── Site Preview ── --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;overflow:hidden;">
        <div class="card-header border-0 px-4 pt-3 pb-2" style="background:#0f172a;">
            <span class="text-white fw-bold" style="font-size:.8rem;">
                <i class="fas fa-eye me-1"></i> Site Preview
            </span>
        </div>
        <div class="card-body p-0">
            {{-- Browser mockup --}}
            <div style="background:#1e293b;padding:8px 12px;display:flex;align-items:center;gap:6px;">
                <span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block;"></span>
                <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
                <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                <span style="flex:1;background:#334155;border-radius:4px;padding:3px 10px;
                             font-size:.7rem;color:#94a3b8;margin-left:6px;">
                    dealshood.com
                </span>
            </div>
            <div style="padding:20px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                {{-- Logo preview --}}
                <div class="mb-3">
                    @if(!empty($settings['site_logo']))
                        <img src="{{ Storage::url($settings['site_logo']) }}"
                             alt="Logo" style="height:32px;object-fit:contain;" id="logoPreview">
                    @else
                        <div id="logoPreview"
                             style="font-size:1.1rem;font-weight:800;color:#0f172a;">
                            {{ $settings['site_name'] ?? 'DealsHood' }}
                        </div>
                    @endif
                </div>
                {{-- SERP preview --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px 16px;">
                    <div style="font-size:.65rem;color:#94a3b8;margin-bottom:4px;">Google Search Preview</div>
                    <div id="serpTitle" style="font-size:.88rem;font-weight:600;color:#1a0dab;line-height:1.3;margin-bottom:3px;">
                        {{ $settings['meta_title'] ?? 'DealsHood' }}
                    </div>
                    <div style="font-size:.72rem;color:#006621;margin-bottom:3px;">dealshood.com</div>
                    <div id="serpDesc" style="font-size:.78rem;color:#545454;line-height:1.5;">
                        {{ Str::limit($settings['meta_description'] ?? '', 150) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── General Options ── --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width:30px;height:30px;border-radius:8px;background:#fffbeb;
                             color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.8rem;">
                    <i class="fas fa-sliders-h"></i>
                </span>
                <h6 class="mb-0 fw-bold">General Options</h6>
            </div>
        </div>
        <div class="card-body px-4 pt-3 pb-4">

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.8rem;">Posts Per Page</label>
                <input type="number" name="posts_per_page" class="form-control form-control-sm"
                       value="{{ $settings['posts_per_page'] ?? 12 }}"
                       min="1" max="100">
            </div>

            {{-- Toggles --}}
            <div class="d-flex align-items-center justify-content-between py-2"
                 style="border-top:1px solid #f1f5f9;">
                <div>
                    <div class="fw-semibold" style="font-size:.82rem;">Admin Email Notifications</div>
                    <div class="text-muted" style="font-size:.74rem;">Email when new ad is submitted</div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch"
                           name="admin_email_notify" id="adminNotify"
                           {{ ($settings['admin_email_notify'] ?? '1') === '1' ? 'checked' : '' }}>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2"
                 style="border-top:1px solid #f1f5f9;">
                <div>
                    <div class="fw-semibold" style="font-size:.82rem;">Maintenance Mode</div>
                    <div class="text-muted" style="font-size:.74rem;">Show maintenance page to visitors</div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch"
                           name="maintenance_mode" id="maintenanceMode"
                           style="accent-color:#ef4444;"
                           {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }}>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Save button (sidebar) ── --}}
    <button form="settingsForm" type="submit"
            class="btn btn-dark w-100 py-3 fw-bold"
            style="border-radius:14px;font-size:.9rem;">
        <i class="fas fa-save me-2"></i> Save All Settings
    </button>
    <p class="text-muted text-center mt-2" style="font-size:.72rem;">
        Changes take effect immediately.
    </p>

</div>
</div>{{-- /col-lg-4 --}}

</div>{{-- /row --}}
</form>

</div>{{-- /container --}}

<script>
/* ── Character counters ── */
function counter(inputId, displayId, warn) {
    const el  = document.getElementById(inputId);
    const out = document.getElementById(displayId);
    if (!el || !out) return;
    function update() {
        const len = el.value.length;
        out.textContent = len + ' / ' + el.getAttribute('maxlength');
        out.style.color = len > warn ? '#ef4444' : '#94a3b8';
    }
    el.addEventListener('input', update);
    update();
}
counter('metaTitle', 'metaTitleCount', 60);
counter('metaDesc',  'metaDescCount',  160);

/* ── Live SERP preview ── */
document.getElementById('metaTitle').addEventListener('input', function(){
    document.getElementById('serpTitle').textContent = this.value || 'DealsHood';
});
document.getElementById('metaDesc').addEventListener('input', function(){
    const txt = this.value.substring(0, 150);
    document.getElementById('serpDesc').textContent = txt || '';
});

/* ── Logo file preview ── */
document.querySelector('input[name="site_logo"]')?.addEventListener('change', function(){
    if (!this.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('logoPreview');
        if (prev.tagName === 'IMG') {
            prev.src = e.target.result;
        } else {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'height:32px;object-fit:contain;';
            img.id = 'logoPreview';
            prev.replaceWith(img);
        }
    };
    reader.readAsDataURL(this.files[0]);
});

/* ── Maintenance mode warning ── */
document.getElementById('maintenanceMode')?.addEventListener('change', function(){
    if (this.checked) {
        if (!confirm('⚠️ Enabling maintenance mode will hide the site from all visitors. Continue?')) {
            this.checked = false;
        }
    }
});
</script>

@endsection