<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/frontend/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.png">
    <title>Browse Deals — DealsHood</title>
    @php
        $ogImage = str_replace('http://', 'https://', url('/frontend/img/favicon.png'));
        $ogTitle = 'Browse Deals — DealsHood';
        $ogDesc  = 'Find the best deals near you.';
        $ogUrl   = url()->current();
        $activeCat = $categories->firstWhere('slug', request('category_id'));
        $activeLoc = $localities->firstWhere('slug', request('locality_id'));
    @endphp
    <meta name="description" content="{{ $ogDesc }}">
    <link rel="canonical" href="{{ $ogUrl }}">
    <meta property="og:title"       content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:image"       content="{{ $ogImage }}">
    <meta property="og:url"         content="{{ $ogUrl }}">
    <meta name="twitter:card"       content="summary_large_image">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">

    <style>
    :root {
        --ink:#0d0d0d; --ink-mid:#3a3a3a; --ink-muted:#6b6b6b;
        --surface:#faf9f7; --surface-2:#f2f1ef;
        --white:#ffffff; --accent:#0f3f7e;
        --r:14px; --rlg:20px;
        --sh-sm:0 2px 12px rgba(0,0,0,.07);
        --sh-md:0 6px 32px rgba(0,0,0,.10);
        --sh-lg:0 20px 60px rgba(0,0,0,.15);
        --nav-h:64px;
    }
    *,*::before,*::after{box-sizing:border-box;}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;
         background:var(--surface);color:var(--ink);margin:0;}

    /* ── Navbar ───────────────────────────────────────── */
    .dh-nav{position:fixed;top:0;left:0;right:0;height:var(--nav-h);
            background:#fff;border-bottom:1px solid rgba(0,0,0,.07);
            z-index:1000;display:flex;align-items:center;}
    .dh-nav-inner{display:flex;align-items:center;justify-content:space-between;
                  width:100%;max-width:1180px;margin:0 auto;padding:0 24px;}
    .dh-nav-logo img{height:45px;display:block;}
    .dh-nav-actions{display:flex;align-items:center;gap:10px;}
    .dh-btn-nav{display:inline-flex;align-items:center;gap:6px;font-size:.75rem;font-weight:500;
                letter-spacing:.04em;border:none;cursor:pointer;border-radius:100px;
                padding:9px 18px;text-decoration:none;transition:transform .15s;}
    .dh-btn-nav:hover{transform:translateY(-1px);}
    .dh-btn-ig{background:#e1306c;color:#fff;}
    .dh-btn-wa{background:#25d366;color:#fff;}
    .dh-nav-toggle{display:none;background:none;border:none;cursor:pointer;
                   flex-direction:column;gap:5px;padding:6px;}
    .dh-nav-toggle span{display:block;width:22px;height:2px;background:var(--ink);border-radius:2px;}
    @media(max-width:640px){
        .dh-nav-toggle{display:flex;}
        .dh-nav-actions{display:none;position:absolute;top:var(--nav-h);left:0;right:0;
                        background:#fff;border-bottom:1px solid rgba(0,0,0,.08);
                        padding:16px 24px;flex-direction:column;align-items:flex-start;gap:10px;}
        .dh-nav-actions.open{display:flex;}
    }

    /* ── Compact Hero ─────────────────────────────────── */
    .dh-hero{padding-top:var(--nav-h);position:relative;overflow:hidden;
             background:var(--ink);min-height:190px;display:flex;align-items:center;}
    .dh-hero-bg{position:absolute;inset:0;
                background:url('/frontend/img/office-dark.jpg') center/cover no-repeat;opacity:.35;}
    .dh-hero-overlay{position:absolute;inset:0;
                     background:linear-gradient(160deg,rgba(13,13,13,.82) 0%,rgba(13,13,13,.4) 60%,rgba(15,63,126,.2) 100%);}
    .dh-hero-body{position:relative;z-index:2;width:100%;max-width:1180px;
                  margin:0 auto;padding:22px 24px 36px;animation:fadeUp .45s .05s both;}
    .dh-hero-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.64rem;
                     font-weight:500;letter-spacing:.16em;text-transform:uppercase;
                     color:rgba(255,255,255,.42);margin-bottom:7px;}
    .dh-hero-eyebrow::before{content:'';display:inline-block;width:16px;height:1.5px;
                              background:rgba(255,255,255,.3);border-radius:2px;}
    .dh-hero-title{font-size:clamp(1.6rem,3.2vw,2.3rem);font-weight:800;color:#fff;
                   line-height:1.14;letter-spacing:-.02em;margin:0 0 5px;}
    .dh-hero-sub{font-size:.85rem;color:rgba(255,255,255,.48);font-weight:300;margin:0;}
    .dh-hero-wave{position:absolute;bottom:-1px;left:0;right:0;z-index:3;line-height:0;}
    .dh-hero-wave svg{display:block;width:100%;}

    /* ── Shared wrapper ────────────────────────────────── */
    .dh-wrap{max-width:1180px;margin:0 auto;padding:0 24px;}

    /* ═══════════════════════════════════════════════════
       CHIP FILTER ROWS  — wrap to next line, no horizontal scroll
    ═══════════════════════════════════════════════════ */
    .dh-chips-sec{background:var(--surface);padding:20px 0 4px;}
    .dh-chips-sec + .dh-chips-sec{padding-top:4px;}

    .dh-chips-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;}
    .dh-chips-label{font-size:.63rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase;
                     color:var(--ink-muted);display:flex;align-items:center;gap:5px;}
    .dh-chips-label i{color:var(--accent);font-size:.6rem;}
    .dh-chips-clear{font-size:.7rem;font-weight:500;color:var(--ink-muted);cursor:pointer;
                     background:none;border:none;display:inline-flex;align-items:center;gap:4px;
                     transition:color .15s;padding:0;}
    .dh-chips-clear:hover{color:var(--accent);}

    /* Category chips — wrap to next row, no horizontal scroll */
    .dh-chips-row{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        padding-bottom:4px;
    }

    /* Locality + Subcategory chips — scroll right, single line */
    .dh-chips-row.chips-scroll{
        flex-wrap:nowrap;
        overflow-x:auto;
        -ms-overflow-style:none;
        scrollbar-width:none;
        cursor:grab;
        padding-bottom:6px;
        padding-top:5px;
    }
    .dh-chips-row.chips-scroll::-webkit-scrollbar{ display:none; }
    .dh-chips-row.chips-scroll:active{ cursor:grabbing; }

    /* Individual chip */
    .dh-chip{
        display:inline-flex;align-items:center;gap:6px;
        padding:6px 14px;border-radius:100px;
        font-size:.73rem;font-weight:600;white-space:nowrap;
        color:var(--ink-muted);background:#fff;
        border:1.5px solid rgba(0,0,0,.1);
        cursor:pointer;transition:all .15s;user-select:none;
        text-decoration:none;
    }
    .dh-chip:hover{border-color:var(--accent);color:var(--accent);
                    background:rgba(15,63,126,.05);transform:translateY(-1px);}
    .dh-chip.active{background:var(--ink);color:#fff;border-color:var(--ink);
                     box-shadow:0 3px 12px rgba(0,0,0,.2);}
    .dh-chip.active .chip-icon{background:rgba(255,255,255,.18)!important;color:#fff!important;}
    .chip-icon{width:20px;height:20px;border-radius:5px;flex-shrink:0;
               display:flex;align-items:center;justify-content:center;font-size:.6rem;}

    /* ── Search bar ─────────────────────────────────────── */
    .dh-search-sec{background:var(--surface);padding:14px 0 4px;}
    .dh-search-row{display:flex;gap:10px;align-items:center;}
    .dh-search-input{flex:1;font-size:.87rem;color:var(--ink);background:#fff;
                      border:1.5px solid rgba(0,0,0,.1);border-radius:var(--r);
                      padding:9px 15px;outline:none;transition:border-color .15s,box-shadow .15s;}
    .dh-search-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(15,63,126,.08);}
    .dh-search-btn{flex-shrink:0;font-size:.79rem;font-weight:600;
                    background:var(--ink);color:#fff;border:none;border-radius:var(--r);
                    padding:9px 20px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;
                    transition:background .15s,transform .15s;}
    .dh-search-btn:hover{background:var(--accent);transform:translateY(-1px);}

    /* ── Toolbar ────────────────────────────────────────── */
    .dh-toolbar{display:flex;align-items:center;justify-content:space-between;
                flex-wrap:wrap;gap:10px;margin-bottom:22px;}
    .dh-result-info{font-size:.82rem;color:var(--ink-muted);font-weight:300;}
    .dh-result-info strong{color:var(--ink);font-weight:700;}
    .dh-sort-pills{display:flex;gap:6px;}
    .dh-sort-pill{font-size:.72rem;font-weight:500;padding:6px 14px;border-radius:100px;
                   cursor:pointer;border:1.5px solid rgba(0,0,0,.1);transition:all .15s;
                   color:var(--ink-muted);background:#fff;user-select:none;}
    .dh-sort-pill:hover{border-color:var(--accent);color:var(--accent);}
    .dh-sort-pill.active{background:var(--ink);color:#fff;border-color:var(--ink);}

    /* ── Posts grid ─────────────────────────────────────── */
    .dh-posts-sec{padding:0 0 88px;background:var(--surface);}

    /* ── Grid view (default) ────────────────────────────── */
    .dh-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
    @media(max-width:900px){.dh-grid{grid-template-columns:repeat(2,1fr);}}
    @media(max-width:560px){.dh-grid{grid-template-columns:1fr;}}

    /* ── List view ──────────────────────────────────────── */
    .dh-grid.list-view{ display:flex; flex-direction:column; gap:14px; }

    .dh-grid.list-view .dh-card{
        flex-direction:row;
        border-radius:var(--r);
        min-height:0;
        align-items:stretch;
    }
    /* Image — wider fixed width, fills full card height */
    .dh-grid.list-view .dh-card-media{
        flex:0 0 280px;
        position:relative;
        overflow:hidden;
    }
    .dh-grid.list-view .dh-card-media img,
    .dh-grid.list-view .dh-card-media video{
        position:absolute; inset:0;
        width:100%; height:100%; object-fit:cover;
    }
    .dh-grid.list-view .dh-card-media .ratio{
        position:absolute; inset:0; height:100%;
    }
    /* Body — compact padding, flex items tight */
    .dh-grid.list-view .dh-card-body{
        flex:1;
        padding:14px 18px;
        justify-content:space-between;
        min-width:0;
    }
    /* Description — 2 lines max */
    .dh-grid.list-view .dh-card-desc{
        display:-webkit-box; -webkit-line-clamp:2;
        -webkit-box-orient:vertical; overflow:hidden;
        margin-bottom:10px;
    }
    /* Meta row — tighter */
    .dh-grid.list-view .dh-card-meta{
        margin-bottom:10px;
    }
    /* Buttons — small, side by side, don't stretch */
    .dh-grid.list-view .dh-card-actions{ gap:6px; }
    .dh-grid.list-view .dh-card-actions .dh-btn{
        flex:0 0 auto;          /* don't grow to fill */
        padding:6px 14px;
        font-size:.72rem;
    }
    .dh-grid.list-view .dh-card-actions .dh-btn-primary{
        flex:0 0 auto;
    }
    /* Mobile — keep horizontal layout but narrower image */
    @media(max-width:640px){
        .dh-grid.list-view .dh-card{
            flex-direction:row;    /* stays horizontal on mobile */
            min-height:120px;
        }
        .dh-grid.list-view .dh-card-media{
            flex:0 0 110px;        /* compact image column */
        }
        .dh-grid.list-view .dh-card-body{
            padding:10px 12px;
        }
        /* Hide description + meta row on very small screens to save space */
        .dh-grid.list-view .dh-card-desc{
            display:none;
        }
        .dh-grid.list-view .dh-card-meta{
            display:none;
        }
        /* Smaller title */
        .dh-grid.list-view .dh-card-title{
            font-size:.82rem;
            -webkit-line-clamp:3;
            display:-webkit-box;
            -webkit-box-orient:vertical;
            overflow:hidden;
        }
        /* Compact action button */
        .dh-grid.list-view .dh-card-actions .dh-btn-primary{
            padding:5px 10px;
            font-size:.68rem;
        }
        .dh-grid.list-view .dh-card-actions .dh-btn-ghost{
            padding:5px 8px;
        }
    }

    /* ── View toggle buttons ─────────────────────────────── */
    .dh-view-toggle{display:flex;gap:4px;}
    .dh-vbtn{
        width:34px;height:34px;border-radius:8px;border:1.5px solid rgba(0,0,0,.1);
        background:#fff;cursor:pointer;color:var(--ink-muted);
        display:flex;align-items:center;justify-content:center;
        font-size:.9rem;transition:all .15s;
    }
    .dh-vbtn:hover{border-color:var(--accent);color:var(--accent);}
    .dh-vbtn.active{background:var(--ink);color:#fff;border-color:var(--ink);}
    .dh-card{background:#fff;border-radius:var(--rlg);overflow:hidden;box-shadow:var(--sh-sm);
              border:1px solid rgba(0,0,0,.05);display:flex;flex-direction:column;
              transition:transform .22s,box-shadow .22s;animation:fadeUp .4s both;}
    .dh-card:hover{transform:translateY(-5px);box-shadow:var(--sh-md);}
    .dh-card-media{position:relative;overflow:hidden;flex-shrink:0;}
    .dh-card-media a{display:block;}
    .dh-card-media img,.dh-card-media video{width:100%;height:220px;object-fit:cover;
                                             display:block;transition:transform .35s;}
    .dh-card:hover .dh-card-media img{transform:scale(1.04);}
    .dh-card-media .ratio{height:220px;}
    .badge-feat{position:absolute;top:12px;right:12px;background:#f59e0b;color:#fff;
                font-size:.6rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;
                padding:4px 10px;border-radius:100px;z-index:2;}
    .dh-card-body{padding:18px 20px 20px;display:flex;flex-direction:column;flex:1;}
    .dh-badges{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;}
    .dh-b{font-size:.6rem;font-weight:500;letter-spacing:.07em;text-transform:uppercase;
           padding:3px 9px;border-radius:100px;}
    .dh-b-loc{background:var(--surface-2);color:var(--ink-muted);}
    .dh-b-cat{background:rgba(15,63,126,.08);color:var(--accent);}
    .dh-b-sub{background:rgba(59,130,246,.08);color:#1d4ed8;}
    .dh-card-title{font-size:1rem;font-weight:700;color:var(--ink);line-height:1.35;
                    margin:0 0 8px;text-decoration:none;display:block;transition:color .15s;}
    .dh-card-title:hover{color:var(--accent);}
    .dh-card-desc{font-size:.81rem;line-height:1.65;color:var(--ink-muted);
                   font-weight:300;flex:1;margin-bottom:14px;}
    .dh-card-meta{display:flex;align-items:center;gap:4px;flex-wrap:wrap;
                   padding-top:10px;border-top:1px solid rgba(0,0,0,.06);margin-bottom:14px;}
    .dh-meta-btn,.dh-meta-box{display:flex;align-items:center;gap:7px;padding:4px 10px;
                               border-radius:14px;background:#fff;border:1px solid #edf0f5;
                               transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.04);}
    .dh-meta-btn{cursor:pointer;outline:none;}
    .dh-meta-btn:hover,.dh-meta-box:hover{transform:translateY(-2px);box-shadow:0 5px 16px rgba(0,0,0,.08);}
    .dh-meta-icon{font-size:12px;color:#6b7280;}
    .dh-meta-count{font-size:13px;font-weight:600;color:#1f2937;}
    .dh-meta-time{margin-left:auto;display:flex;align-items:center;gap:6px;font-size:12px;color:#6b7280;}
    .likeBtn.liked{background:rgba(255,77,109,.08);border-color:rgba(255,77,109,.18);}
    .likeBtn.liked .dh-meta-icon{color:#ff4d6d;}
    .likeBtn.liked .dh-meta-count{color:#ff4d6d;}
    .dh-card-actions{display:flex;gap:8px;}
    .dh-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;
             font-size:.75rem;font-weight:500;border-radius:100px;padding:9px 18px;
             text-decoration:none;border:1.5px solid;cursor:pointer;transition:all .15s;flex:1;}
    .dh-btn-primary{background:var(--ink);color:#fff;border-color:var(--ink);}
    .dh-btn-primary:hover{background:var(--accent);border-color:var(--accent);color:#fff;}
    .dh-btn-ghost{background:transparent;color:var(--ink-muted);
                   border-color:rgba(0,0,0,.12);flex:0 0 auto;padding:9px 14px;}
    .dh-btn-ghost:hover{background:var(--surface-2);color:var(--ink);}
    .dh-empty{grid-column:1/-1;text-align:center;padding:72px 24px;color:var(--ink-muted);}
    .dh-empty-icon{font-size:2.8rem;margin-bottom:14px;opacity:.32;}
    .dh-empty-title{font-size:1.15rem;font-weight:700;color:var(--ink);margin-bottom:8px;}
    .dh-empty-text{font-size:.85rem;font-weight:300;}

    /* Loading */
    .dh-loader{display:none;text-align:center;padding:40px 0;}
    .dh-dots{display:inline-flex;gap:7px;align-items:center;}
    .dh-dots span{width:8px;height:8px;border-radius:50%;background:var(--accent);
                   animation:dotPulse 1.2s infinite both;}
    .dh-dots span:nth-child(2){animation-delay:.2s;}
    .dh-dots span:nth-child(3){animation-delay:.4s;}
    .dh-end-msg{display:none;text-align:center;padding:28px 0;
                font-size:.77rem;color:var(--ink-muted);letter-spacing:.06em;}
    .dh-end-msg::before,.dh-end-msg::after{content:'';display:inline-block;width:32px;height:1px;
                                            background:rgba(0,0,0,.14);vertical-align:middle;margin:0 10px;}

    /* ── Footer ─────────────────────────────────────────── */
    .dh-footer{background:var(--ink);color:rgba(255,255,255,.7);padding:60px 0 0;font-size:.84rem;}
    .dh-footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr;gap:48px;padding-bottom:48px;}
    @media(max-width:720px){.dh-footer-grid{grid-template-columns:1fr 1fr;}}
    @media(max-width:440px){.dh-footer-grid{grid-template-columns:1fr;}}
    .dh-footer-brand{font-size:1.1rem;color:#fff;margin:12px 0 5px;}
    .dh-footer-tag{font-size:.77rem;color:rgba(255,255,255,.38);margin:0;}
    .dh-footer-social{display:flex;gap:8px;margin-top:18px;}
    .dh-footer-social a{width:34px;height:34px;border-radius:50%;border:1px solid rgba(255,255,255,.15);
                         display:flex;align-items:center;justify-content:center;
                         color:rgba(255,255,255,.6);font-size:.88rem;text-decoration:none;transition:.15s;}
    .dh-footer-social a:hover{border-color:rgba(255,255,255,.5);color:#fff;}
    .dh-footer-col-title{font-size:.64rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;
                          color:var(--accent);margin-bottom:14px;}
    .dh-footer-links{list-style:none;padding:0;margin:0;}
    .dh-footer-links li{margin-bottom:10px;}
    .dh-footer-links a{color:rgba(255,255,255,.52);text-decoration:none;transition:color .15s;}
    .dh-footer-links a:hover{color:#fff;}
    .dh-footer-bottom{border-top:1px solid rgba(255,255,255,.08);text-align:center;
                       padding:20px 0;font-size:.74rem;color:rgba(255,255,255,.3);}
    .dh-footer-bottom a{color:rgba(255,255,255,.48);text-decoration:none;}

    @keyframes fadeUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
    @keyframes dotPulse{0%,80%,100%{opacity:.2;transform:scale(.75);}40%{opacity:1;transform:scale(1);}}
/* ── PWA / app feel ── */
@media (max-width: 768px) {
 
    /* Scroll snap on hero category tiles */
    .dh-hero-panel .dh-glass-grid {
        flex-wrap: nowrap;
        overflow-x: auto;
        justify-content: flex-start;
        padding: 0 16px 12px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        scroll-snap-type: x proximity;
    }
    .dh-hero-panel .dh-glass-grid::-webkit-scrollbar { display: none; }
    .dh-gtile { scroll-snap-align: start; flex-shrink: 0; }
 
    /* Hero height tighter on mobile */
    .dh-hero-panel { padding: 20px 0 52px; }
    .dh-hero-text  { padding: 28px 24px 0; }
 
    /* Carousels scroll snap */
    .dh-track {
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding-left: 16px;
    }
    .dh-track .dh-card { scroll-snap-align: start; }
    .dh-track .dh-card:last-child { margin-right: 16px; }
 
    /* Latest deals — 2-col grid on small phones, 1-col on very small */
    .dh-grid {
        grid-template-columns: repeat(2,1fr);
        gap: 12px;
    }
    @media (max-width: 380px) {
        .dh-grid { grid-template-columns: 1fr; }
    }
 
    /* Card body tighter */
    .dh-card-body { padding: 12px 14px 14px; }
    .dh-card-title { font-size: .88rem; }
    .dh-card-desc  { display: none; } /* hide on mobile to save space */
 
    /* Section heading tighter */
    .dh-sec-head   { margin-bottom: 14px; }
    .dh-sec-title  { font-size: 1.15rem; }
 
    /* Listing chips label hidden */
    .dh-chips-label .label-text { display: none; }
 
    /* Listing toolbar — stack on tiny screens */
    .dh-toolbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
 
    /* Sort pills — scroll, don't wrap */
    .dh-sort-pills {
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 2px;
    }
    .dh-sort-pills::-webkit-scrollbar { display: none; }
 
    /* Post detail hero taller */
    .dh-hero.detail-hero { min-height: 60vw; }
 
    /* Post detail description font */
    .dh-content-body { font-size: .94rem; line-height: 1.78; }
 
    /* Reading progress bar */
    #reading-progress { height: 2px; }
 
    /* Load more button full width */
    .dh-more-btn { width: 100%; justify-content: center; }
 
    /* Footer — single column */
    .dh-footer-grid { grid-template-columns: 1fr !important; gap: 28px; }
    .dh-footer { padding: 36px 0 0; }
}
 
/* ── Smooth momentum scroll globally ── */
* { -webkit-overflow-scrolling: touch; }
 
/* ── Remove ugly mobile tap highlight everywhere ── */
* { -webkit-tap-highlight-color: transparent; }
 
/* ── Prevent zoom on input focus (iOS) ── */
@media (max-width: 768px) {
    input, select, textarea { font-size: 16px !important; }
}
</style>
</head>
<body>
{{-- <nav class="dh-nav">
    <div class="dh-nav-inner">
        <a href="{{ route('home') }}">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" style="height:45px;">
        </a>
        <button class="dh-nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="dh-nav-actions" id="navActions">
            <a href="{{ route('home') }}" class="dh-btn-nav" style="background:#f1f5f9;color:var(--ink);">
                <i class="bi bi-house"></i> Home
            </a>
            <a href="https://www.instagram.com/dealshood?igsh=NHJpdDhkYmJ2dTlj"
               target="_blank" class="dh-btn-nav dh-btn-ig">
                <i class="bi bi-instagram"></i> Follow
            </a>
            <a href="https://wa.me/918086087050?text=Hello%20I%20am%20interested%20in%20your%20listing"
               target="_blank" class="dh-btn-nav dh-btn-wa">
                <i class="bi bi-whatsapp"></i> Contact
            </a>
        </div>
    </div>
</nav> --}}
<nav class="dh-nav" style="position:sticky;top:0;">
    <div class="dh-nav-inner" style="position:relative;">
        <a href="{{ route('home') }}" class="dh-nav-back">
            <i class="fas fa-chevron-left"></i>
            <span class="d-none d-sm-inline">Home</span>
        </a>
 
        <span class="dh-nav-page-title">
            @if(request('category_id'))
                {{ optional($activeCat)->name ?? 'Deals' }}
            @else
                Browse Deals
            @endif
        </span>
 
        {{-- REPLACE WITH --}}
        <a href="{{ route('home') }}" class="d-none d-md-block">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" style="height:45px;">
        </a>
        <div class="dh-nav-actions" style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('home') }}" class="dh-btn-nav" style="background:#f1f5f9;color:var(--ink);">
                <i class="bi bi-house"></i> Home
            </a>
            <a href="https://www.instagram.com/dealshood?igsh=NHJpdDhkYmJ2dTlj"
               target="_blank" class="dh-btn-nav dh-btn-ig">
                <i class="bi bi-instagram"></i> Follow
            </a>
            <a href="https://wa.me/918086087050?text=Hello%20I%20am%20interested%20in%20your%20listing"
               target="_blank" class="dh-btn-nav dh-btn-wa">
                <i class="bi bi-whatsapp"></i> Contact
            </a>
        </div>
    </div>
</nav>

{{-- ─── Compact Hero ─── --}}
<header class="dh-hero">
    <div class="dh-hero-bg"></div>
    <div class="dh-hero-overlay"></div>
    <div class="dh-hero-body">
        <div class="dh-hero-eyebrow">DealsHood</div>
        <h1 class="dh-hero-title" id="heroTitle">
            @if ($activeCat && $activeLoc)
                {{ $activeCat->name }} Deals in {{ $activeLoc->name }}
            @elseif ($activeCat)
                {{ $activeCat->name }} Deals
            @elseif ($activeLoc)
                Deals in {{ $activeLoc->name }}
            @elseif (request('keyword'))
                Results for "{{ request('keyword') }}"
            @else
                Browse All Deals
            @endif
        </h1>
        <p class="dh-hero-sub" id="heroSub">
            <span id="totalCount">{{ number_format($posts->total()) }}</span> deals found
        </p>
    </div>
    <div class="dh-hero-wave">
        <svg viewBox="0 0 1440 56" fill="none">
            <path d="M0 56H1440V28C1200 56 960 8 720 8C480 8 240 56 0 28V56Z" fill="#faf9f7"/>
        </svg>
    </div>
</header>

@php
$palette = [
    ['bg'=>'#dbeafe','ic'=>'#1d4ed8','icon'=>'fa-tags'],
    ['bg'=>'#d1fae5','ic'=>'#059669','icon'=>'fa-leaf'],
    ['bg'=>'#fef3c7','ic'=>'#d97706','icon'=>'fa-fire'],
    ['bg'=>'#fce7f3','ic'=>'#db2777','icon'=>'fa-heart'],
    ['bg'=>'#ede9fe','ic'=>'#7c3aed','icon'=>'fa-gem'],
    ['bg'=>'#cffafe','ic'=>'#0891b2','icon'=>'fa-bolt'],
    ['bg'=>'#fef2f2','ic'=>'#dc2626','icon'=>'fa-percent'],
    ['bg'=>'#ecfdf5','ic'=>'#16a34a','icon'=>'fa-star'],
    ['bg'=>'#fff7ed','ic'=>'#ea580c','icon'=>'fa-house'],
    ['bg'=>'#f0f9ff','ic'=>'#0284c7','icon'=>'fa-car'],
    ['bg'=>'#fdf4ff','ic'=>'#a21caf','icon'=>'fa-shirt'],
    ['bg'=>'#f8fafc','ic'=>'#475569','icon'=>'fa-laptop'],
];
$paletteJson = json_encode($palette);
@endphp

{{-- ─── Locality chips ─── --}}
<section class="dh-chips-sec">
    <div class="dh-wrap">
        <div class="dh-chips-head">
            <span class="dh-chips-label">
                <i class="fas fa-map-marker-alt"></i> Localities
            </span>
            @if (request('locality_id'))
                <button class="dh-chips-clear" data-clear="locality_id">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            @endif
        </div>
        <div class="dh-chips-row chips-scroll" id="locChips">
            <span class="dh-chip {{ !request('locality_id') ? 'active':'' }}"
                  data-filter="locality_id" data-val="">
                <span class="chip-icon" style="background:rgba(0,0,0,.05);color:var(--accent);">
                    <i class="fas fa-globe"></i>
                </span>
                All Areas
            </span>
            @foreach ($localities as $i => $loc)
                @php $p = $palette[$i % count($palette)]; @endphp
                <span class="dh-chip {{ request('locality_id') == $loc->slug ? 'active':'' }}"
                      data-filter="locality_id" data-val="{{ $loc->slug }}">
                    <span class="chip-icon" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    {{ $loc->name }}
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── Subcategory chips (shown when category selected) ─── --}}
<section class="dh-chips-sec" id="subcatSec"
         style="{{ request('category_id') && $subcategories->isNotEmpty() ? '' : 'display:none;' }}">
    <div class="dh-wrap">
        <div class="dh-chips-head">
            <span class="dh-chips-label">
                <i class="fas fa-sitemap"></i>
                <span id="subcatLabel">{{ $activeCat?->name }} — Subcategories</span>
            </span>
            @if (request('subcategory_id'))
                <button class="dh-chips-clear" data-clear="subcategory_id">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            @endif
        </div>
        <div class="dh-chips-row chips-scroll" id="subcatChips">
            @if (request('category_id') && $subcategories->isNotEmpty())
                <span class="dh-chip {{ !request('subcategory_id') ? 'active':'' }}"
                      data-filter="subcategory_id" data-val="">
                    <span class="chip-icon" style="background:rgba(0,0,0,.05);color:var(--accent);">
                        <i class="fas fa-th"></i>
                    </span>
                    All
                </span>
                @foreach ($subcategories as $i => $sub)
                    @php $p = $palette[$i % count($palette)]; @endphp
                    <span class="dh-chip {{ request('subcategory_id') == $sub->slug ? 'active':'' }}"
                          data-filter="subcategory_id" data-val="{{ $sub->slug }}">
                        <span class="chip-icon" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                            <i class="fas {{ $p['icon'] }}"></i>
                        </span>
                        {{ $sub->name }}
                    </span>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- ─── Keyword search ─── --}}
<section class="dh-search-sec">
    <div class="dh-wrap">
        <div class="dh-search-row">
            <input class="dh-search-input" type="text" id="keywordInput"
                   placeholder="Search deals by keyword…"
                   value="{{ request('keyword') }}" autocomplete="off">
            <button class="dh-search-btn" id="searchBtn">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </div>
</section>

{{-- ─── Posts section ─── --}}
<section class="dh-posts-sec">
    <div class="dh-wrap" style="padding-top:22px;">

        <div class="dh-toolbar">
            <p class="dh-result-info mb-0">
                Showing <strong id="resultCount">{{ number_format($posts->total()) }}</strong> deals
                @if ($activeCat) in <strong>{{ $activeCat->name }}</strong>@endif
                @if ($activeLoc) · <strong>{{ $activeLoc->name }}</strong>@endif
            </p>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <div class="dh-sort-pills">
                    <span class="dh-sort-pill {{ request('sort','latest') === 'latest' ? 'active':'' }}"
                          data-sort="latest">
                        <i class="bi bi-clock"></i> Newest
                    </span>
                    <span class="dh-sort-pill {{ request('sort') === 'popular' ? 'active':'' }}"
                          data-sort="popular">
                        <i class="bi bi-eye"></i> Popular
                    </span>
                    <span class="dh-sort-pill {{ request('sort') === 'trending' ? 'active':'' }}"
                          data-sort="trending">
                        <i class="bi bi-fire"></i> Trending
                    </span>
                </div>
                {{-- Grid / List toggle --}}
                <div class="dh-view-toggle">
                    <button class="dh-vbtn active" id="btnGrid" title="Grid view">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                    <button class="dh-vbtn" id="btnList" title="List view">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="dh-grid" id="post-wrapper">
            @forelse($posts as $post)
                @include('frontend.post-single-card', ['post' => $post])
            @empty
                <div class="dh-empty" style="grid-column:1/-1;">
                    <div class="dh-empty-icon">🔍</div>
                    <p class="dh-empty-title">No Deals Found</p>
                    <p class="dh-empty-text">Try a different locality, category, or keyword.</p>
                </div>
            @endforelse
        </div>

        <div class="dh-loader" id="loading">
            <div class="dh-dots"><span></span><span></span><span></span></div>
        </div>
        <div class="dh-end-msg" id="endMsg">You've seen all the deals</div>
        <input type="hidden" id="next-page-url" value="{{ $posts->nextPageUrl() }}">

    </div>
</section>

<footer class="dh-footer">
    <div class="dh-wrap">
        <div class="dh-footer-grid">
            <div>
                <img src="/frontend/img/dealshood.png" alt="DealsHood"
                     style="height:32px;filter:brightness(0) invert(1);opacity:.8;">
                <p class="dh-footer-brand">DealsHood</p>
                <p class="dh-footer-tag">Discover the best deals around you.</p>
                <div class="dh-footer-social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/dealshood" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <p class="dh-footer-col-title">Company</p>
                <ul class="dh-footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Advertise</a></li>
                </ul>
            </div>
            <div>
                <p class="dh-footer-col-title">Support</p>
                <ul class="dh-footer-links">
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="dh-footer-bottom">
            <p>&copy; <span id="footerYear"></span> <a href="#">DealsHood</a>. All rights reserved.</p>
        </div>
    </div>
</footer>

@include('frontend.frontend-mobile')
<style>
@media(max-width: 768px) {
    #post-wrapper {
        grid-template-columns: 1fr !important;
        gap: 14px;
    }
}
@media(max-width: 768px) {
    #post-wrapper {
        grid-template-columns: 1fr !important;
        gap: 14px;
    }
    /* Re-show description in single-column list */
    #post-wrapper .dh-card-desc {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    #post-wrapper .dh-card-media img,
    #post-wrapper .dh-card-media video { height: 200px; }
}
</style>
<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
const PALETTE     = {!! $paletteJson !!};
const LISTING_URL = '{{ route("posts.listing") }}';
const CSRF        = '{{ csrf_token() }}';

document.getElementById('footerYear').textContent = new Date().getFullYear();
// document.getElementById('navToggle').addEventListener('click',function(){
//     document.getElementById('navActions').classList.toggle('open');
// });

/* ═══════════════════════════════════════════════════
   FILTER STATE — tracks current active filters
═══════════════════════════════════════════════════ */
const filters = {
    locality_id    : '{{ request("locality_id") }}',
    category_id    : '{{ request("category_id") }}',
    subcategory_id : '{{ request("subcategory_id") }}',
    keyword        : '{{ request("keyword") }}',
    sort           : '{{ request("sort", "latest") }}',
    page           : 1,
};

let isLoading = false;

/* Build URL from filters */
function buildUrl(page){
    const p = Object.assign({}, filters, {page: page||1});
    const params = new URLSearchParams();
    Object.entries(p).forEach(([k,v])=>{ if(v) params.set(k,v); });
    return LISTING_URL + (params.toString() ? '?'+params.toString() : '');
}

/* ─── Main AJAX load ────────────────────────────── */
function loadPosts(reset, nextUrl){
    if(isLoading) return;
    isLoading = true;

    const loader = document.getElementById('loading');
    const endMsg = document.getElementById('endMsg');
    loader.style.display = 'block';
    endMsg.style.display  = 'none';

    const url = nextUrl || buildUrl(1);

    if(reset) window.history.pushState({}, '', url);

    fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r=>r.json())
        .then(data=>{
            loader.style.display = 'none';
            const wrapper = document.getElementById('post-wrapper');
            reset ? wrapper.innerHTML = data.html
                  : wrapper.insertAdjacentHTML('beforeend', data.html);

            wrapper.querySelectorAll('.dh-card').forEach((c,i)=>
                c.style.animationDelay=(i*.03)+'s'
            );

            const next = data.next_page || '';
            document.getElementById('next-page-url').value = next;
            if(!next) endMsg.style.display = 'block';

            if(data.total !== undefined){
                document.getElementById('resultCount').textContent =
                    Number(data.total).toLocaleString();
            }
            isLoading = false;
        })
        .catch(()=>{ isLoading=false; loader.style.display='none'; });
}

/* ─── Chip click — AJAX filter ─────────────────── */
$(document).on('click','.dh-chip',function(){
    const key = $(this).data('filter');
    const val = $(this).data('val');

    // Update active state
    $('[data-filter="'+key+'"]').removeClass('active');
    $(this).addClass('active');
    filters[key] = val;
    filters.page = 1;

    loadPosts(true);
});

/* ─── Clear filter buttons ──────────────────────── */
$(document).on('click','.dh-chips-clear',function(){
    const key = $(this).data('clear');
    filters[key] = '';
    filters.page = 1;
    $('[data-filter="'+key+'"]').removeClass('active');
    $('[data-filter="'+key+'"][data-val=""]').addClass('active');

    loadPosts(true);
});

/* ─── Sort pills ────────────────────────────────── */
$(document).on('click','.dh-sort-pill',function(){
    $('.dh-sort-pill').removeClass('active');
    $(this).addClass('active');
    filters.sort = $(this).data('sort');
    filters.page = 1;
    loadPosts(true);
});

/* ─── Keyword search ────────────────────────────── */
$('#searchBtn').on('click',function(){
    filters.keyword = $('#keywordInput').val().trim();
    filters.page    = 1;
    loadPosts(true);
});
$('#keywordInput').on('keydown',function(e){
    if(e.key==='Enter') $('#searchBtn').trigger('click');
});

/* ─── Infinite scroll ───────────────────────────── */
window.addEventListener('scroll',function(){
    if(isLoading) return;
    if(document.body.offsetHeight - window.scrollY - window.innerHeight > 320) return;
    const next = document.getElementById('next-page-url').value;
    if(!next) return;
    loadPosts(false, next);
},{passive:true});

/* ── Like ─────────────────────────────────────────── */
$(document).on('click','.likeBtn',function(){
    const btn=$(this),id=btn.data('id');
    $.post('/posts/'+id+'/toggle-like',{_token:CSRF},function(res){
        $('#lc-'+id).text(res.likes);
        res.liked?btn.addClass('liked'):btn.removeClass('liked');
    });
});

/* ── Share ────────────────────────────────────────── */
$(document).on('click','.shareBtn',function(){
    const id=$(this).data('id'),url=$(this).data('url');
    navigator.share?navigator.share({url}):(navigator.clipboard.writeText(url),alert('Link copied!'));
    $.post('/posts/'+id+'/share',{_token:CSRF,platform:'web'});
});

/* ── Grid / List view toggle ──────────────────────── */
(function(){
    const STORAGE_KEY = 'dh_view_mode';
    const $grid   = $('#post-wrapper');
    const $btnG   = $('#btnGrid');
    const $btnL   = $('#btnList');

    function setView(mode){
        if(mode === 'list'){
            $grid.addClass('list-view');
            $btnL.addClass('active');
            $btnG.removeClass('active');
        } else {
            $grid.removeClass('list-view');
            $btnG.addClass('active');
            $btnL.removeClass('active');
        }
        try{ localStorage.setItem(STORAGE_KEY, mode); }catch(e){}
    }

    // Restore saved preference
    try{
        const saved = localStorage.getItem(STORAGE_KEY);
        if(saved) setView(saved);
    }catch(e){}

    $btnG.on('click', () => setView('grid'));
    $btnL.on('click', () => setView('list'));
})();
</script>

</body>
</html>