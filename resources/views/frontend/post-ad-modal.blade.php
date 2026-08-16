@php
    $adCategories = \App\Models\Category::orderBy('name')->get(['id','name']);
    $adLocalities = \App\Models\Locality::orderBy('name')->get(['id','name']);
@endphp

{{-- ── Styles loaded BEFORE the markup below ──────────
     The sheet/backdrop must be off-screen (transform/display)
     the instant they're parsed. If the <style> tag came after
     this HTML (as it used to), the browser can paint the sheet
     in its untransformed, at-the-bottom-of-the-viewport state
     for a frame before the rules apply — a visible flash of the
     full "Post Your Ad" form before it slides away. Defining the
     rules first means they're already in effect when these
     elements are created, so there's nothing to flash. ── --}}
<style>
/* ── FAB ── */
.post-ad-fab {
    position: fixed;
    bottom: calc(var(--bot-nav-h, 60px) + env(safe-area-inset-bottom, 0px) + 14px);
    right: 16px;
    z-index: 850;
    background: linear-gradient(135deg, #0f3f7e, #1d6fd8);
    color: #fff;
    border: none;
    border-radius: 100px;
    padding: 13px 20px;
    font-size: .82rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 6px 24px rgba(15,63,126,.4), 0 2px 8px rgba(0,0,0,.15);
    cursor: pointer;
    transition: transform .18s, box-shadow .18s;
    -webkit-tap-highlight-color: transparent;
}
.post-ad-fab:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(15,63,126,.5); }
.post-ad-fab:active { transform: scale(.96); }
@media (max-width: 768px) {
    /* Add Post moved into the bottom nav bar on mobile — this FAB
       would be a duplicate trigger there, so hide it. Desktop (no
       bottom nav) keeps it as the only Add Post entry point. */
    .post-ad-fab { display: none; }
}

/* ── Backdrop ── */
.post-ad-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 1040;
    display: none;
    backdrop-filter: blur(2px);
}
.post-ad-backdrop.open { display: block; }

/* ── Sheet ── */
.post-ad-sheet {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: #fff;
    border-radius: 24px 24px 0 0;
    z-index: 1050;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    transform: translateY(100%);
    transition: transform .32s cubic-bezier(.4,0,.2,1);
    max-width: 640px;
    margin: 0 auto;
}
.post-ad-sheet.open { transform: translateY(0); }

.post-ad-handle {
    width: 40px; height: 4px;
    background: #e2e8f0; border-radius: 2px;
    margin: 12px auto 0;
    flex-shrink: 0;
}
.post-ad-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 16px 20px 12px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.post-ad-title { font-size: 1rem; font-weight: 800; color: #0d0d0d; }
.post-ad-sub   { font-size: .74rem; color: #94a3b8; margin-top: 2px; }
.post-ad-close {
    width: 32px; height: 32px; border-radius: 50%;
    border: 1px solid #f1f5f9; background: #f8fafc;
    color: #64748b; font-size: .85rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0;
}

.post-ad-body {
    flex: 1; overflow-y: auto;
    padding: 16px 20px;
    -webkit-overflow-scrolling: touch;
}

/* Steps */
.post-ad-steps {
    display: flex; align-items: center;
    margin-bottom: 20px; gap: 0;
}
.pad-step {
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; font-weight: 600; color: #cbd5e1;
    white-space: nowrap;
}
.pad-step.active { color: #0f3f7e; }
.pad-step.done   { color: #22c55e; }
.pad-step-num {
    width: 22px; height: 22px; border-radius: 50%;
    background: #e2e8f0; color: #94a3b8;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; font-weight: 800; flex-shrink: 0;
    transition: all .2s;
}
.pad-step.active .pad-step-num { background: #0f3f7e; color: #fff; }
.pad-step.done   .pad-step-num { background: #22c55e; color: #fff; }
.pad-step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 6px; }

/* Form fields */
.pad-row   { margin-bottom: 14px; }
.pad-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.pad-label {
    display: block; font-size: .75rem; font-weight: 600;
    color: #374151; margin-bottom: 5px;
}
.pad-input {
    width: 100%; padding: 10px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: .88rem; color: #0d0d0d;
    background: #fff; outline: none;
    transition: border-color .15s, box-shadow .15s;
    -webkit-appearance: none;
}
.pad-input:focus {
    border-color: #0f3f7e;
    box-shadow: 0 0 0 3px rgba(15,63,126,.1);
}
.pad-input.is-invalid { border-color: #dc2626; }
.pad-select { cursor: pointer; }
.pad-textarea { resize: vertical; min-height: 90px; }
.pad-input-group { position: relative; }
.pad-input-group .pad-input { padding-right: 36px; }
.pad-input-suffix {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%);
    font-size: .8rem; font-weight: 700; color: #94a3b8;
    pointer-events: none;
}
.pad-err { display: block; font-size: .72rem; color: #dc2626; margin-top: 4px; }

/* Review box */
.pad-review {
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 14px; padding: 16px;
}
.pad-review-row {
    display: flex; gap: 10px; padding: 8px 0;
    border-bottom: 1px solid #f1f5f9; font-size: .84rem;
}
.pad-review-row:last-child { border-bottom: none; }
.pad-rl { color: #94a3b8; font-size: .72rem; font-weight: 700;
           text-transform: uppercase; letter-spacing: .04em; min-width: 90px; }
.pad-rv { color: #0d0d0d; font-weight: 500; word-break: break-word; }

/* Footer */
.post-ad-footer {
    padding: 12px 20px calc(12px + env(safe-area-inset-bottom, 0px));
    border-top: 1px solid #f1f5f9;
    display: flex; gap: 10px;
    flex-shrink: 0;
}
.pad-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    font-size: .82rem; font-weight: 700; border-radius: 100px;
    padding: 12px 20px; border: none; cursor: pointer;
    transition: all .15s; -webkit-tap-highlight-color: transparent;
}
.pad-btn-primary { background: #0f172a; color: #fff; flex: 1; }
.pad-btn-primary:hover { background: #0f3f7e; }
.pad-btn-primary:active { transform: scale(.97); }
.pad-btn-ghost {
    background: #f8fafc; color: #64748b;
    border: 1.5px solid #e2e8f0;
}

/* Success */
.post-ad-success {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: #fff;
    border-radius: 24px 24px 0 0;
    z-index: 1051;
    padding: 40px 24px calc(40px + env(safe-area-inset-bottom, 0px));
    text-align: center;
    max-width: 640px;
    margin: 0 auto;
}
.pas-icon  { font-size: 3rem; margin-bottom: 12px; }
.pas-title { font-size: 1.3rem; font-weight: 800; color: #0d0d0d; margin-bottom: 8px; }
.pas-sub   { font-size: .88rem; color: #6b7280; line-height: 1.65; }
.pad-upload-zone {
    border: 2px dashed #e2e8f0; border-radius: 12px;
    padding: 20px; text-align: center; cursor: pointer;
    background: #f8fafc; transition: border-color .15s;
}
.pad-upload-zone:hover { border-color: #0f3f7e; }
.pad-upload-zone i { font-size: 1.4rem; color: #94a3b8; display: block; margin-bottom: 6px; }
.pad-upload-zone span { display: block; font-size: .82rem; font-weight: 600; color: #374151; }
.pad-upload-zone small { display: block; font-size: .68rem; color: #94a3b8; margin-top: 3px; }
.pad-image-strip { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
.pad-image-strip .img-wrap { position: relative; width: 64px; height: 64px; }
.pad-image-strip img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1.5px solid #e2e8f0; }
.pad-image-strip .img-remove {
    position: absolute; top: -6px; right: -6px;
    width: 18px; height: 18px; border-radius: 50%;
    background: #dc2626; color: #fff; border: none;
    font-size: 9px; cursor: pointer; display: flex; align-items: center; justify-content: center;
}
</style>

{{-- ── Floating Post Ad Button ───────────────────────── --}}
<button class="post-ad-fab" id="postAdFab" onclick="openPostAdModal()">
    <i class="fas fa-plus"></i>
    <span>Post Free Ad</span>
</button>

{{-- ── Backdrop ─────────────────────────────────────── --}}
<div class="post-ad-backdrop" id="postAdBackdrop" onclick="closePostAdModal()"></div>

{{-- ── Slide-up Modal ───────────────────────────────── --}}
<div class="post-ad-sheet" id="postAdSheet">

    <div class="post-ad-handle"></div>

    <div class="post-ad-header">
        <div>
            <div class="post-ad-title">📢 Post Your Ad</div>
            <div class="post-ad-sub">Free listing — reviewed within 24 hours</div>
        </div>
        <button class="post-ad-close" onclick="closePostAdModal()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="post-ad-body" id="postAdBody">

        {{-- Step indicators --}}
        <div class="post-ad-steps">
            <div class="pad-step active" id="padStep1">
                <span class="pad-step-num">1</span> Your Details
            </div>
            <div class="pad-step-line"></div>
            <div class="pad-step" id="padStep2">
                <span class="pad-step-num">2</span> Ad Info
            </div>
            <div class="pad-step-line"></div>
            <div class="pad-step" id="padStep3">
                <span class="pad-step-num">3</span> Submit
            </div>
        </div>

        <form id="postAdForm" novalidate enctype="multipart/form-data">
            @csrf

            {{-- ── Step 1: Contact ── --}}
            <div class="pad-pane" id="padPane1">
                <div class="pad-row">
                    <label class="pad-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="pad-input" placeholder="Your name">
                    <small class="pad-err" id="err_name"></small>
                </div>
                <div class="pad-row">
                    <label class="pad-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="pad-input" placeholder="you@example.com">
                    <small class="pad-err" id="err_email"></small>
                </div>
                <div class="pad-row-2">
                    <div class="pad-row">
                        <label class="pad-label">Phone</label>
                        <input type="text" name="phone" class="pad-input" placeholder="+91 98765 43210">
                    </div>
                    <div class="pad-row">
                        <label class="pad-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="pad-input" placeholder="+91 98765 43210">
                    </div>
                </div>
                <div class="pad-row">
                    <label class="pad-label">Company / Business Name</label>
                    <input type="text" name="company_name" class="pad-input" placeholder="e.g. Acme Traders">
                </div>
            </div>

            {{-- ── Step 2: Ad Info ── --}}
            <div class="pad-pane d-none" id="padPane2">
                <div class="pad-row">
                    <label class="pad-label">Ad Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="pad-input" placeholder="e.g. 50% Off on All Electronics">
                    <small class="pad-err" id="err_title"></small>
                </div>
                <div class="pad-row-2">
                    <div class="pad-row">
                        <label class="pad-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="padCategorySelect" class="pad-input pad-select">
                            <option value="">Select…</option>
                            @foreach($adCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <small class="pad-err" id="err_category_id"></small>
                    </div>
                    <div class="pad-row">
                        <label class="pad-label">Subcategory</label>
                        <select name="subcategory_id" id="padSubcategorySelect" class="pad-input pad-select" disabled>
                            <option value="">Select a category first…</option>
                        </select>
                    </div>
                </div>
                <div class="pad-row d-none" id="padCustomSubcategoryRow">
                    <label class="pad-label">Enter your Subcategory</label>
                    <input type="text" name="custom_subcategory" id="padCustomSubcategory" class="pad-input"
                           placeholder="e.g. Car Wash">
                </div>
                <div class="pad-row">
                    <label class="pad-label">Locality</label>
                    <select name="locality_id" id="padLocalitySelect" class="pad-input pad-select">
                        <option value="">Select…</option>
                        @foreach($adLocalities as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                        <option value="__other__">Other (not listed)…</option>
                    </select>
                </div>
                <div class="pad-row d-none" id="padCustomLocalityRow">
                    <label class="pad-label">Enter your Locality</label>
                    <input type="text" name="custom_locality" id="padCustomLocality" class="pad-input"
                           placeholder="e.g. Vytilla">
                </div>
                <div class="pad-row">
                    <label class="pad-label">Description</label>
                    <textarea name="description" class="pad-input pad-textarea"
                              placeholder="Describe your offer, product or service…" rows="4"></textarea>
                </div>
                <div class="pad-row-2">
                    <div class="pad-row">
                        <label class="pad-label">Offer %</label>
                        <div class="pad-input-group">
                            <input type="number" name="offer_percentage" class="pad-input"
                                   placeholder="e.g. 20" min="0" max="100" step="0.01">
                            <span class="pad-input-suffix">%</span>
                        </div>
                    </div>
                    <div class="pad-row">
                        <label class="pad-label">Valid Until</label>
                        <input type="date" name="expiry_date" class="pad-input"
                               min="{{ now()->addDay()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="pad-row">
                    <label class="pad-label">Location / Area</label>
                    <input type="text" name="location" class="pad-input"
                           placeholder="e.g. Near City Mall, Downtown">
                </div>
                <div class="pad-row">
                    <label class="pad-label">Upload Images <span style="color:#94a3b8;font-weight:400;">(up to 5)</span></label>
                    <div class="pad-upload-zone" id="padUploadZone" onclick="document.getElementById('padImageInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Tap to add photos</span>
                        <small>JPG, PNG, WEBP — Max 5MB each</small>
                    </div>
                    <input type="file" id="padImageInput" name="images[]" accept="image/*" multiple style="display:none;">
                    <div class="pad-image-strip" id="padImageStrip"></div>
                    <small class="pad-err" id="err_images"></small>
                </div>
            </div>

            {{-- ── Step 3: Review & Submit ── --}}
            <div class="pad-pane d-none" id="padPane3">
                <div class="pad-review" id="padReview"></div>
                <p style="font-size:.75rem;color:#94a3b8;margin-top:12px;line-height:1.6;">
                    <i class="fas fa-shield-alt me-1" style="color:#22c55e;"></i>
                    By submitting, you agree that your ad will be reviewed before being published.
                    We'll email you at the address provided.
                </p>
            </div>

        </form>

    </div>

    {{-- Footer nav --}}
    <div class="post-ad-footer">
        <button class="pad-btn pad-btn-ghost" id="padBtnBack" onclick="padGoBack()" style="display:none;">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <button class="pad-btn pad-btn-primary" id="padBtnNext" onclick="padGoNext()">
            Next <i class="fas fa-arrow-right"></i>
        </button>
        <button class="pad-btn pad-btn-primary d-none" id="padBtnSubmit" onclick="padSubmit()">
            <span id="padBtnSubmitText"><i class="fas fa-paper-plane"></i> Submit Ad</span>
            <span id="padBtnSubmitSpinner" class="d-none">
                <span class="spinner-border spinner-border-sm"></span> Submitting…
            </span>
        </button>
    </div>

</div>

{{-- ── Success screen (replaces sheet content) ── --}}
<div class="post-ad-success" id="postAdSuccess" style="display:none;">
    <div class="pas-icon">🎉</div>
    <div class="pas-title">Ad Submitted!</div>
    <div class="pas-sub">
        We've received your ad and will review it within 24 hours.<br>
        Check your email for a confirmation.
    </div>
    <button class="pad-btn pad-btn-primary" onclick="closePostAdModal()" style="margin-top:24px;width:100%;">
        Done
    </button>
</div>

<script>
let padCurrentStep = 1;

/* ── Guard against bfcache restore flashing the modal open ──
   If the user opened this modal, then navigated away and hit
   Back, the browser can restore the page from cache with the
   .open classes still applied — causing a visible pop-then-hide
   flash before other JS finishes running. Force-reset instantly,
   with transitions disabled, on every page load/bfcache restore. ── */
window.addEventListener('pageshow', function (e) {
    const sheet    = document.getElementById('postAdSheet');
    const backdrop = document.getElementById('postAdBackdrop');
    const success  = document.getElementById('postAdSuccess');
    if (!sheet || !backdrop) return;

    sheet.style.transition = 'none';
    sheet.classList.remove('open');
    backdrop.classList.remove('open');
    if (success) success.style.display = 'none';
    document.body.style.overflow = '';

    // Re-enable the transition on the next frame so future opens animate normally
    requestAnimationFrame(() => {
        sheet.style.transition = '';
    });
});

function openPostAdModal() {
    document.getElementById('postAdSheet').classList.add('open');
    document.getElementById('postAdBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closePostAdModal() {
    document.getElementById('postAdSheet').classList.remove('open');
    document.getElementById('postAdBackdrop').classList.remove('open');
    document.getElementById('postAdSuccess').style.display = 'none';
    document.body.style.overflow = '';
    // Reset form after close animation
    setTimeout(resetPadForm, 350);
}

function resetPadForm() {
    padCurrentStep = 1;
    document.getElementById('postAdForm').reset();
    padSelectedFiles = [];
    document.getElementById('padImageStrip').innerHTML = '';
    padShowPane(1);
    clearPadErrors();
    document.getElementById('padBtnBack').style.display = 'none';
    document.getElementById('padBtnNext').classList.remove('d-none');
    document.getElementById('padBtnSubmit').classList.add('d-none');
    document.getElementById('padBtnNext').innerHTML = 'Next <i class="fas fa-arrow-right"></i>';

    const subSel = document.getElementById('padSubcategorySelect');
    subSel.innerHTML = '<option value="">Select a category first…</option>';
    subSel.disabled = true;
    document.getElementById('padCustomSubcategoryRow').classList.add('d-none');
    document.getElementById('padCustomLocalityRow').classList.add('d-none');
}

/* ── Category → Subcategory cascade ──────────────────────── */
document.getElementById('padCategorySelect').addEventListener('change', function () {
    const categoryId = this.value;
    const subSel = document.getElementById('padSubcategorySelect');
    document.getElementById('padCustomSubcategoryRow').classList.add('d-none');

    if (!categoryId) {
        subSel.innerHTML = '<option value="">Select a category first…</option>';
        subSel.disabled = true;
        return;
    }

    subSel.innerHTML = '<option value="">Loading…</option>';
    subSel.disabled = true;

    fetch('/ad-subcategories/' + categoryId)
        .then(r => r.json())
        .then(list => {
            let html = '<option value="">Select…</option>';
            list.forEach(s => html += `<option value="${s.id}">${s.name}</option>`);
            html += '<option value="__other__">Other (not listed)…</option>';
            subSel.innerHTML = html;
            subSel.disabled = false;
        })
        .catch(() => {
            subSel.innerHTML = '<option value="">Select…</option><option value="__other__">Other (not listed)…</option>';
            subSel.disabled = false;
        });
});

/* ── "Other" toggles ──────────────────────────────────────── */
document.getElementById('padSubcategorySelect').addEventListener('change', function () {
    const row = document.getElementById('padCustomSubcategoryRow');
    row.classList.toggle('d-none', this.value !== '__other__');
    if (this.value !== '__other__') document.getElementById('padCustomSubcategory').value = '';
});

document.getElementById('padLocalitySelect').addEventListener('change', function () {
    const row = document.getElementById('padCustomLocalityRow');
    row.classList.toggle('d-none', this.value !== '__other__');
    if (this.value !== '__other__') document.getElementById('padCustomLocality').value = '';
});

function padShowPane(step) {
    [1,2,3].forEach(i => {
        document.getElementById('padPane'+i)?.classList.add('d-none');
        const s = document.getElementById('padStep'+i);
        s.classList.remove('active','done');
        if (i < step) s.classList.add('done');
        if (i === step) s.classList.add('active');
    });
    document.getElementById('padPane'+step)?.classList.remove('d-none');
    document.getElementById('postAdBody').scrollTop = 0;
}

function clearPadErrors() {
    document.querySelectorAll('.pad-err').forEach(el => el.textContent = '');
    document.querySelectorAll('.pad-input.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

function padValidateStep(step) {
    clearPadErrors();
    let valid = true;

    function fail(name, msg) {
        const el = document.querySelector('[name="'+name+'"]');
        const err = document.getElementById('err_'+name);
        if (el)  el.classList.add('is-invalid');
        if (err) err.textContent = msg;
        valid = false;
    }

    if (step === 1) {
        const name  = document.querySelector('[name="name"]').value.trim();
        const email = document.querySelector('[name="email"]').value.trim();
        if (!name)  fail('name',  'Name is required.');
        if (!email) fail('email', 'Email is required.');
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) fail('email', 'Enter a valid email.');
    }

    if (step === 2) {
        const title = document.querySelector('[name="title"]').value.trim();
        const cat   = document.querySelector('[name="category_id"]').value;
        if (!title) fail('title',       'Ad title is required.');
        if (!cat)   fail('category_id', 'Please select a category.');
    }

    return valid;
}

function buildReview() {
    const fd = new FormData(document.getElementById('postAdForm'));
    const get = k => fd.get(k) || '';

    const catSel = document.querySelector('[name="category_id"]');
    const subSel = document.getElementById('padSubcategorySelect');
    const locSel = document.getElementById('padLocalitySelect');
    const catName = catSel.options[catSel.selectedIndex]?.text || '';
    const subName = subSel.value === '__other__' ? get('custom_subcategory') : (subSel.options[subSel.selectedIndex]?.text || '');
    const locName = locSel.value === '__other__' ? get('custom_locality')    : (locSel.options[locSel.selectedIndex]?.text || '');

    const rows = [
        ['Name',     get('name')],
        ['Email',    get('email')],
        ['Phone',    get('phone')],
        ['WhatsApp', get('whatsapp')],
        ['Company',  get('company_name')],
        ['Ad Title', get('title')],
        ['Category', catName],
        ['Subcategory', subName],
        ['Locality', locName],
        ['Location', get('location')],
        ['Offer',    get('offer_percentage') ? get('offer_percentage') + '% OFF' : ''],
        ['Valid Until', get('expiry_date')],
        ['Description', get('description')],
    ].filter(([,v]) => v);

    document.getElementById('padReview').innerHTML = rows.map(([l,v]) =>
        `<div class="pad-review-row">
            <span class="pad-rl">${l}</span>
            <span class="pad-rv">${v}</span>
        </div>`
    ).join('');
}

function padGoNext() {
    if (!padValidateStep(padCurrentStep)) return;

    if (padCurrentStep < 3) {
        padCurrentStep++;
        padShowPane(padCurrentStep);

        if (padCurrentStep === 3) {
            buildReview();
            document.getElementById('padBtnNext').classList.add('d-none');
            document.getElementById('padBtnSubmit').classList.remove('d-none');
        }

        document.getElementById('padBtnBack').style.display = padCurrentStep > 1 ? 'flex' : 'none';
    }
}

function padGoBack() {
    if (padCurrentStep > 1) {
        padCurrentStep--;
        padShowPane(padCurrentStep);
        document.getElementById('padBtnNext').classList.remove('d-none');
        document.getElementById('padBtnSubmit').classList.add('d-none');
        document.getElementById('padBtnBack').style.display = padCurrentStep > 1 ? 'flex' : 'none';
    }
}

function padSubmit() {
    const btn     = document.getElementById('padBtnSubmit');
    const btnText = document.getElementById('padBtnSubmitText');
    const spinner = document.getElementById('padBtnSubmitSpinner');

    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');
    btn.disabled = true;

    const fd = new FormData(document.getElementById('postAdForm'));
    // "__other__" is a UI sentinel, not a real ID — swap it for an
    // empty subcategory/locality plus whatever was typed in the
    // matching free-text field.
    if (fd.get('subcategory_id') === '__other__') fd.set('subcategory_id', '');
    if (fd.get('locality_id') === '__other__') fd.set('locality_id', '');
    padSelectedFiles.forEach(file => fd.append('images[]', file));
    fetch('{{ route("ad.submit") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: fd,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            document.getElementById('postAdBody').style.display = 'none';
            document.getElementById('post-ad-footer') && (document.getElementById('post-ad-footer').style.display = 'none');
            document.querySelector('.post-ad-footer').style.display = 'none';
            document.querySelector('.post-ad-handle').style.display = 'none';
            document.querySelector('.post-ad-header').style.display = 'none';
            document.getElementById('postAdSuccess').style.display = 'block';
        } else {
            // Show server-side validation errors
            if (res.errors) {
                Object.entries(res.errors).forEach(([field, msgs]) => {
                    const el  = document.querySelector('[name="'+field+'"]');
                    const err = document.getElementById('err_'+field);
                    if (el)  el.classList.add('is-invalid');
                    if (err) err.textContent = msgs[0];
                });
                // Go back to step where error occurred
                const step1Fields = ['name','email','phone','whatsapp','company_name'];
                const hasStep1Err = Object.keys(res.errors).some(f => step1Fields.includes(f));
                padCurrentStep = hasStep1Err ? 1 : 2;
                padShowPane(padCurrentStep);
                document.getElementById('padBtnNext').classList.remove('d-none');
                document.getElementById('padBtnSubmit').classList.add('d-none');
            }
        }
    })
    .catch(() => {
        alert('Something went wrong. Please try again.');
    })
    .finally(() => {
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
        btn.disabled = false;
    });
}
let padSelectedFiles = [];

document.getElementById('padImageInput').addEventListener('change', function (e) {
    const newFiles = Array.from(e.target.files);
    const total = padSelectedFiles.length + newFiles.length;

    if (total > 5) {
        document.getElementById('err_images').textContent = 'Maximum 5 images allowed.';
        return;
    }
    document.getElementById('err_images').textContent = '';

    newFiles.forEach(file => {
        if (file.size > 5 * 1024 * 1024) {
            document.getElementById('err_images').textContent = file.name + ' is too large (max 5MB).';
            return;
        }
        padSelectedFiles.push(file);
    });

    renderImageStrip();
    this.value = ''; // reset so same file can be re-selected if removed
});

function renderImageStrip() {
    const strip = document.getElementById('padImageStrip');
    strip.innerHTML = '';
    padSelectedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.createElement('div');
            wrap.className = 'img-wrap';
            wrap.innerHTML = `<img src="${e.target.result}" alt="">
                <button type="button" class="img-remove" data-idx="${idx}"><i class="fas fa-times"></i></button>`;
            strip.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}

document.getElementById('padImageStrip').addEventListener('click', function (e) {
    const btn = e.target.closest('.img-remove');
    if (!btn) return;
    const idx = parseInt(btn.dataset.idx);
    padSelectedFiles.splice(idx, 1);
    renderImageStrip();
});
</script>
