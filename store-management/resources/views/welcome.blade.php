<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2B Inventory — Transfer Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:    #080F1E;
            --navy-2:  #0D1829;
            --navy-3:  #121F35;
            --navy-4:  #182640;
            --gold:    #C9A84C;
            --gold-lt: #E2C47A;
            --gold-dk: #9A7830;
            --cream:   #F5EDD8;
            --smoke:   #7A8FA8;
            --smoke-lt:#A8B8CC;
            --white:   #FFFFFF;
            --picker:  #4E9EF5;
            --qc:      #A78BFA;
            --lot:     #34D399;
            --rmgr:    #F59E0B;
            --runner:  #F87171;
            --store:   #C9A84C;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            color: var(--white);
            overflow-x: hidden;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(201,168,76,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,.03) 1px, transparent 1px);
            background-size: 52px 52px;
            pointer-events: none; z-index: 0;
        }

        .glow-a {
            position: fixed; width: 700px; height: 700px; border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,.11) 0%, transparent 65%);
            top: -250px; right: -200px;
            filter: blur(80px); pointer-events: none; z-index: 0;
            animation: driftA 14s ease-in-out infinite alternate;
        }
        .glow-b {
            position: fixed; width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(78,158,245,.07) 0%, transparent 65%);
            bottom: -150px; left: -150px;
            filter: blur(80px); pointer-events: none; z-index: 0;
        }
        @keyframes driftA { from{transform:translate(0,0)} to{transform:translate(-50px,60px)} }

        .wrap { position: relative; z-index: 1; max-width: 1240px; margin: 0 auto; padding: 0 40px; }

        /* NAV */
        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 26px 0;
            border-bottom: 1px solid rgba(201,168,76,.1);
        }
        .nav-logo { display: flex; align-items: center; gap: 14px; text-decoration: none; }
        .logo-gem {
            width: 44px; height: 44px; border-radius: 10px;
            background: linear-gradient(135deg, var(--gold-lt), var(--gold-dk));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif; font-weight: 800; font-size: 18px; color: var(--navy);
            box-shadow: 0 0 30px rgba(201,168,76,.4), inset 0 1px 0 rgba(255,255,255,.25);
        }
        .logo-name { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 19px;color: white; }
        .logo-sub  { font-size: 10px; color: var(--smoke); letter-spacing: .1em; text-transform: uppercase; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .btn-ghost {
            padding: 9px 20px; border-radius: 8px; font-size: 14px; font-weight: 500;
            text-decoration: none; color: var(--smoke-lt);
            border: 1px solid rgba(255,255,255,.08); transition: all .2s;
        }
        .btn-ghost:hover { color: var(--white); border-color: rgba(201,168,76,.3); background: rgba(201,168,76,.06); }
        .btn-gold {
            padding: 9px 22px; border-radius: 8px; font-size: 14px; font-weight: 600;
            text-decoration: none; color: var(--navy);
            background: linear-gradient(135deg, var(--gold-lt), var(--gold));
            box-shadow: 0 4px 20px rgba(201,168,76,.3); transition: all .2s;
        }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(201,168,76,.45); }

        /* HERO */
        .hero {
            padding: 80px 0 70px;
            display: grid; grid-template-columns: 1fr 1fr; gap: 72px; align-items: center;
        }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 5px 14px; border-radius: 100px;
            border: 1px solid rgba(201,168,76,.3); background: rgba(201,168,76,.06);
            font-size: 11px; font-weight: 500; letter-spacing: .08em; text-transform: uppercase;
            color: var(--gold-lt); margin-bottom: 24px;
        }
        .eyebrow-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--gold); animation: blink 2s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(38px, 4.5vw, 60px);
            font-weight: 800; line-height: 1.07; letter-spacing: -.025em; margin-bottom: 22px;
        }
        .h1-gold   { color: var(--gold); }
        .h1-stroke { -webkit-text-stroke: 1.5px var(--gold-lt); color: transparent; }

        .hero-desc {
            font-size: 16px; line-height: 1.75; color: var(--smoke-lt); max-width: 460px; margin-bottom: 36px;
        }
        .hero-desc b { color: var(--cream); font-weight: 500; }

        .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 36px; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 26px; border-radius: 10px; font-size: 15px; font-weight: 600;
            text-decoration: none; transition: all .25s;
        }
        .btn-primary { background: linear-gradient(135deg,var(--gold-lt),var(--gold)); color:var(--navy); box-shadow:0 6px 28px rgba(201,168,76,.35); }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 10px 36px rgba(201,168,76,.5); }
        .btn-outline { border:1px solid rgba(201,168,76,.3); color:var(--gold-lt); background:rgba(201,168,76,.04); }
        .btn-outline:hover { background:rgba(201,168,76,.1); border-color:var(--gold); }

        .role-chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 12px; border-radius: 100px; font-size: 12px; font-weight: 500; border: 1px solid;
        }
        .chip-dot { width: 6px; height: 6px; border-radius: 50%; }

        /* LIVE TRANSFER CARD */
        .live-card {
            background: var(--navy-2);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 20px; padding: 26px;
            box-shadow: 0 40px 100px rgba(0,0,0,.5);
            position: relative; overflow: hidden;
        }
        .live-card::before {
            content:''; position:absolute; top:0; left:0; right:0; height:1px;
            background: linear-gradient(90deg, transparent, rgba(201,168,76,.5), transparent);
        }
        .lc-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .lc-title { font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:var(--smoke-lt); text-transform:uppercase; letter-spacing:.07em; }
        .lc-live {
            display: flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 100px;
            background: rgba(248,113,113,.12); color: var(--runner);
            font-size: 11px; font-weight: 600;
        }
        .lc-live-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--runner); animation: blink 1.2s infinite; }
        .lc-ref { font-size: 11px; color: var(--smoke); margin-bottom: 18px; font-family: monospace; letter-spacing: .05em; }

        .ms { display: flex; align-items: center; gap: 12px; padding: 11px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,.05); background: var(--navy-3); margin-bottom: 6px; }
        .ms-ico { width: 34px; height: 34px; border-radius: 8px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
        .ms-body { flex: 1; }
        .ms-role  { font-size: 10px; font-weight: 700; letter-spacing:.06em; text-transform:uppercase; }
        .ms-act   { font-size: 12px; color: var(--smoke-lt); margin-top:1px; }
        .ms-badge { padding: 2px 9px; border-radius: 100px; font-size: 10px; font-weight: 700; flex-shrink: 0; }
        .connector { width: 1px; height: 8px; margin: 0 0 6px 22px; background: linear-gradient(180deg, rgba(201,168,76,.3), transparent); }

        /* DIVIDER */
        .hr { height:1px; background: linear-gradient(90deg, transparent, rgba(201,168,76,.18), transparent); margin: 70px 0; }

        /* STATS */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 56px; }
        .stat-box { background:var(--navy-2); border:1px solid rgba(255,255,255,.06); border-radius:14px; padding:24px; text-align:center; }
        .stat-big { font-family:'Syne',sans-serif; font-size:38px; font-weight:800; color:var(--gold); letter-spacing:-.02em; }
        .stat-lbl { font-size:13px; color:var(--smoke-lt); margin-top:4px; }

        /* ROLE LEGEND */
        .legend {
            display: grid; grid-template-columns: repeat(6,1fr); gap: 10px;
            background: var(--navy-2); border: 1px solid rgba(255,255,255,.06);
            border-radius: 20px; padding: 26px; margin-bottom: 80px;
        }
        .leg-item { text-align: center; }
        .leg-ico { width:46px; height:46px; border-radius:11px; margin:0 auto 10px; display:flex; align-items:center; justify-content:center; font-size:19px; }
        .leg-name { font-family:'Syne',sans-serif; font-size:12px; font-weight:700; margin-bottom:3px; }
        .leg-desc { font-size:10px; color:var(--smoke); line-height:1.5; }

        /* WORKFLOW */
        .wf-header { text-align:center; margin-bottom:72px; }
        h2 { font-family:'Syne',sans-serif; font-size:clamp(28px,3.5vw,44px); font-weight:800; letter-spacing:-.02em; line-height:1.15; margin-bottom:14px; }
        h2 em { font-style:normal; color:var(--gold); }
        .sec-tag { font-size:11px; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:var(--gold); display:flex; align-items:center; justify-content:center; gap:10px; margin-bottom:14px; }
        .sec-tag::before,.sec-tag::after { content:''; width:20px; height:1px; background:var(--gold); }
        .sec-sub { font-size:15px; color:var(--smoke-lt); line-height:1.7; max-width:580px; margin:0 auto; }

        /* Pipeline */
        .pipeline { position:relative; padding-bottom:10px; }
        .pipeline::before {
            content:''; position:absolute; left:50%; top:0; bottom:0; width:2px; transform:translateX(-50%);
            background: linear-gradient(180deg,
                var(--picker) 0%, var(--qc) 20%, var(--lot) 38%,
                var(--rmgr) 55%, var(--runner) 75%, var(--store) 100%);
            opacity: .2;
        }

        .pstep { display:grid; grid-template-columns:1fr 80px 1fr; align-items:center; margin-bottom:60px; }
        .pstep:last-child { margin-bottom:0; }
        .pstep:nth-child(even) .pcard { order:3; }
        .pstep:nth-child(even) .pcenter { order:2; }
        .pstep:nth-child(even) .pempty  { order:1; }

        .pcenter { display:flex; align-items:center; justify-content:center; }
        .pnode {
            width:62px; height:62px; border-radius:50%;
            display:flex; align-items:center; justify-content:center; font-size:22px;
            position:relative; z-index:2; flex-shrink:0;
        }
        .pnode::before { content:''; position:absolute; inset:-4px; border-radius:50%; border:1px solid; opacity:.4; }
        .pnode::after  { content:''; position:absolute; inset:-11px; border-radius:50%; border:1px dashed; opacity:.15; animation: spin 10s linear infinite; }
        @keyframes spin { to{transform:rotate(360deg)} }

        .pcard {
            background: var(--navy-2); border:1px solid rgba(255,255,255,.07); border-radius:16px; padding:28px;
            position:relative; overflow:hidden; transition: transform .3s, box-shadow .3s, border-color .3s;
        }
        .pcard:hover { transform:translateY(-4px); box-shadow:0 28px 60px rgba(0,0,0,.4); }
        .pcard::before { content:''; position:absolute; top:0; left:0; right:0; height:2px; }

        .step-num { font-family:'Syne',sans-serif; font-size:11px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; margin-bottom:10px; display:flex; align-items:center; gap:7px; }
        .step-num-badge { width:22px; height:22px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; color:var(--navy); }
        .role-lbl { font-size:11px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; margin-bottom:10px; display:flex; align-items:center; gap:6px; }
        .role-lbl::before { content:''; width:12px; height:1px; }
        .pcard h3 { font-family:'Syne',sans-serif; font-size:20px; font-weight:700; margin-bottom:10px; line-height:1.2; }
        .pcard p  { font-size:14px; color:var(--smoke-lt); line-height:1.7; }

        .tasks { margin-top:16px; display:flex; flex-direction:column; gap:7px; }
        .task  { display:flex; align-items:center; gap:8px; font-size:13px; color:var(--smoke-lt); }
        .tck   { width:18px; height:18px; border-radius:5px; display:flex; align-items:center; justify-content:center; font-size:9px; flex-shrink:0; }

        /* Color themes */
        .c-picker .pnode { background:rgba(78,158,245,.14); border:1px solid rgba(78,158,245,.3); }
        .c-picker .pnode::before,.c-picker .pnode::after { border-color:var(--picker); }
        .c-picker .pcard::before { background:linear-gradient(90deg,transparent,var(--picker),transparent); }
        .c-picker .pcard:hover   { border-color:rgba(78,158,245,.3); box-shadow:0 28px 60px rgba(78,158,245,.1); }
        .c-picker .step-num { color:var(--picker); } .c-picker .step-num-badge { background:var(--picker); }
        .c-picker .role-lbl { color:var(--picker); } .c-picker .role-lbl::before { background:var(--picker); }
        .c-picker .tck { background:rgba(78,158,245,.14); color:var(--picker); }

        .c-qc .pnode { background:rgba(167,139,250,.14); border:1px solid rgba(167,139,250,.3); }
        .c-qc .pnode::before,.c-qc .pnode::after { border-color:var(--qc); }
        .c-qc .pcard::before { background:linear-gradient(90deg,transparent,var(--qc),transparent); }
        .c-qc .pcard:hover   { border-color:rgba(167,139,250,.3); box-shadow:0 28px 60px rgba(167,139,250,.1); }
        .c-qc .step-num { color:var(--qc); } .c-qc .step-num-badge { background:var(--qc); }
        .c-qc .role-lbl { color:var(--qc); } .c-qc .role-lbl::before { background:var(--qc); }
        .c-qc .tck { background:rgba(167,139,250,.14); color:var(--qc); }

        .c-lot .pnode { background:rgba(52,211,153,.14); border:1px solid rgba(52,211,153,.3); }
        .c-lot .pnode::before,.c-lot .pnode::after { border-color:var(--lot); }
        .c-lot .pcard::before { background:linear-gradient(90deg,transparent,var(--lot),transparent); }
        .c-lot .pcard:hover   { border-color:rgba(52,211,153,.3); box-shadow:0 28px 60px rgba(52,211,153,.08); }
        .c-lot .step-num { color:var(--lot); } .c-lot .step-num-badge { background:var(--lot); color:var(--navy); }
        .c-lot .role-lbl { color:var(--lot); } .c-lot .role-lbl::before { background:var(--lot); }
        .c-lot .tck { background:rgba(52,211,153,.14); color:var(--lot); }

        .c-rmgr .pnode { background:rgba(245,158,11,.14); border:1px solid rgba(245,158,11,.3); }
        .c-rmgr .pnode::before,.c-rmgr .pnode::after { border-color:var(--rmgr); }
        .c-rmgr .pcard::before { background:linear-gradient(90deg,transparent,var(--rmgr),transparent); }
        .c-rmgr .pcard:hover   { border-color:rgba(245,158,11,.3); box-shadow:0 28px 60px rgba(245,158,11,.08); }
        .c-rmgr .step-num { color:var(--rmgr); } .c-rmgr .step-num-badge { background:var(--rmgr); color:var(--navy); }
        .c-rmgr .role-lbl { color:var(--rmgr); } .c-rmgr .role-lbl::before { background:var(--rmgr); }
        .c-rmgr .tck { background:rgba(245,158,11,.14); color:var(--rmgr); }

        .c-runner .pnode { background:rgba(248,113,113,.14); border:1px solid rgba(248,113,113,.3); }
        .c-runner .pnode::before,.c-runner .pnode::after { border-color:var(--runner); }
        .c-runner .pcard::before { background:linear-gradient(90deg,transparent,var(--runner),transparent); }
        .c-runner .pcard:hover   { border-color:rgba(248,113,113,.3); box-shadow:0 28px 60px rgba(248,113,113,.08); }
        .c-runner .step-num { color:var(--runner); } .c-runner .step-num-badge { background:var(--runner); }
        .c-runner .role-lbl { color:var(--runner); } .c-runner .role-lbl::before { background:var(--runner); }
        .c-runner .tck { background:rgba(248,113,113,.14); color:var(--runner); }

        .c-store .pnode { background:rgba(201,168,76,.14); border:1px solid rgba(201,168,76,.3); }
        .c-store .pnode::before,.c-store .pnode::after { border-color:var(--store); }
        .c-store .pcard::before { background:linear-gradient(90deg,transparent,var(--store),transparent); }
        .c-store .pcard:hover   { border-color:rgba(201,168,76,.3); box-shadow:0 28px 60px rgba(201,168,76,.08); }
        .c-store .step-num { color:var(--store); } .c-store .step-num-badge { background:var(--store); color:var(--navy); }
        .c-store .role-lbl { color:var(--store); } .c-store .role-lbl::before { background:var(--store); }
        .c-store .tck { background:rgba(201,168,76,.14); color:var(--store); }

        /* CTA */
        .cta {
            background:var(--navy-2); border:1px solid rgba(201,168,76,.2); border-radius:24px;
            padding:72px 60px; text-align:center; margin:70px 0 80px; position:relative; overflow:hidden;
        }
        .cta::before { content:''; position:absolute; top:0; left:15%; right:15%; height:2px; background:linear-gradient(90deg,transparent,var(--gold),transparent); }
        .cta::after  { content:''; position:absolute; inset:0; background:radial-gradient(ellipse at 50% 0%, rgba(201,168,76,.07),transparent 60%); pointer-events:none; }
        .cta h2 { max-width:500px; margin:10px auto; }
        .cta p  { font-size:16px; color:var(--smoke-lt); max-width:460px; margin:0 auto 36px; line-height:1.7; }
        .cta-btns { display:flex; align-items:center; justify-content:center; gap:14px; flex-wrap:wrap; position:relative; z-index:1; }

        /* FOOTER */
        footer { border-top:1px solid rgba(255,255,255,.05); padding:28px 0; display:flex; align-items:center; justify-content:space-between; }
        .foot-left { display:flex; align-items:center; gap:10px; }
        .foot-gem { width:30px; height:30px; border-radius:7px; background:linear-gradient(135deg,var(--gold-lt),var(--gold-dk)); display:flex; align-items:center; justify-content:center; font-family:'Syne',sans-serif; font-weight:800; font-size:12px; color:var(--navy); }
        .foot-name { font-size:13px; color:var(--smoke-lt); } .foot-name b { color:var(--gold-lt); }
        .foot-right { font-size:11px; color:rgba(120,140,160,.4); }

        /* ANIMATIONS */
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .a { opacity:0; animation:fadeUp .7s ease forwards; }
        .a1{animation-delay:.1s} .a2{animation-delay:.25s} .a3{animation-delay:.4s} .a4{animation-delay:.55s} .a5{animation-delay:.7s}

        /* RESPONSIVE */
        @media(max-width:980px){
            .hero { grid-template-columns:1fr; }
            .live-card-wrap { display:none; }
            .pipeline::before { left:28px; transform:none; }
            .pstep { grid-template-columns:62px 1fr; }
            .pstep .pempty  { display:none; }
            .pstep .pcenter { justify-content:flex-start; }
            .pstep:nth-child(even) .pcard,.pstep:nth-child(even) .pcenter,.pstep:nth-child(even) .pempty { order:unset; }
            .legend { grid-template-columns:repeat(3,1fr); }
            .stats-row { grid-template-columns:repeat(2,1fr); }
            .cta { padding:40px 24px; }
        }
        @media(max-width:600px){
            .wrap { padding:0 20px; }
            .legend { grid-template-columns:repeat(2,1fr); }
        }
    </style>
</head>
<body>
<div class="glow-a"></div>
<div class="glow-b"></div>

<div class="wrap">

    <!-- NAV -->
    <nav class="a a1">
        <a href="/" class="nav-logo">
    <img src="https://play-lh.googleusercontent.com/6dN3RXGBOzUnG1zLa2pIlgCMA3Hb0FFwnLc0An-DuL3QuSXjj1qeD3KMFR9jpXZtJDnrRiqVeE3oOIrhNdjvNw"
     alt="Company Logo"
     style="width:44px; height:44px; border-radius:10px; object-fit:cover;">
            <div>
                <div class="logo-name">2B Inventory</div>
                <div class="logo-sub">Transfer Management · Egypt</div>
            </div>
        </a>
        @if (Route::has('login'))
        <div class="nav-links">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-gold">Dashboard →</a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Log In</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-gold">Get Started →</a>
                @endif
            @endauth
        </div>
        @endif
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div>
            <div class="eyebrow a a2"><div class="eyebrow-dot"></div>6-Role Transfer Pipeline</div>
            <h1 class="a a2">
                Every Transfer.<br>
                <span class="h1-gold">Every Role.</span><br>
                <span class="h1-stroke">Zero Gaps.</span>
            </h1>
            <p class="hero-desc a a3">
                2B Inventory orchestrates your entire stock transfer journey — from the moment a <b>Picker</b> pulls an item off the shelf, through <b>Quality Control</b> verification, boxing &amp; lot creation, runner assignment by the <b>Runner Manager</b>, all the way to final confirmation at the <b>Store Manager</b>. Every hand-off is tracked. Nothing falls through the cracks.
            </p>
            <div class="hero-btns a a4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-cta btn-primary">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Open Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-cta btn-primary">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Start Free Trial
                        </a>
                        <a href="{{ route('login') }}" class="btn-cta btn-outline">Log In to Account</a>
                    @endauth
                @endif
            </div>
            <div class="role-chips a a5">
                <div class="chip" style="border-color:rgba(78,158,245,.3);color:var(--picker);background:rgba(78,158,245,.07)"><div class="chip-dot" style="background:var(--picker)"></div>Picker</div>
                <div class="chip" style="border-color:rgba(167,139,250,.3);color:var(--qc);background:rgba(167,139,250,.07)"><div class="chip-dot" style="background:var(--qc)"></div>Quality Control</div>
                <div class="chip" style="border-color:rgba(52,211,153,.3);color:var(--lot);background:rgba(52,211,153,.07)"><div class="chip-dot" style="background:var(--lot)"></div>Lot Creator</div>
                <div class="chip" style="border-color:rgba(245,158,11,.3);color:var(--rmgr);background:rgba(245,158,11,.07)"><div class="chip-dot" style="background:var(--rmgr)"></div>Runner Manager</div>
                <div class="chip" style="border-color:rgba(248,113,113,.3);color:var(--runner);background:rgba(248,113,113,.07)"><div class="chip-dot" style="background:var(--runner)"></div>Runner</div>
                <div class="chip" style="border-color:rgba(201,168,76,.3);color:var(--store);background:rgba(201,168,76,.07)"><div class="chip-dot" style="background:var(--store)"></div>Store Manager</div>
            </div>
        </div>

        <!-- LIVE TRANSFER CARD -->
        <div class="live-card-wrap a a3">
            <div class="live-card">
                <div class="lc-head">
                    <div class="lc-title">Live Transfer</div>
                    <div class="lc-live"><div class="lc-live-dot"></div>Active</div>
                </div>
                <div class="lc-ref">TRF-20240331-084 &nbsp;·&nbsp; Cairo → Branch #7</div>

                <div class="ms" style="border-color:rgba(78,158,245,.2)">
                    <div class="ms-ico" style="background:rgba(78,158,245,.12)">🧺</div>
                    <div class="ms-body">
                        <div class="ms-role" style="color:var(--picker)">Picker — Ahmed K.</div>
                        <div class="ms-act">Prepared &amp; dropped transfer</div>
                    </div>
                    <div class="ms-badge" style="background:rgba(52,211,153,.12);color:var(--lot)">✓ Done</div>
                </div>
                <div class="connector"></div>

                <div class="ms" style="border-color:rgba(167,139,250,.2)">
                    <div class="ms-ico" style="background:rgba(167,139,250,.12)">🔍</div>
                    <div class="ms-body">
                        <div class="ms-role" style="color:var(--qc)">Quality Control — Sara M.</div>
                        <div class="ms-act">Verified transfer · 3 discrepancies cleared</div>
                    </div>
                    <div class="ms-badge" style="background:rgba(52,211,153,.12);color:var(--lot)">✓ Done</div>
                </div>
                <div class="connector"></div>

                <div class="ms" style="border-color:rgba(52,211,153,.2)">
                    <div class="ms-ico" style="background:rgba(52,211,153,.12)">📦</div>
                    <div class="ms-body">
                        <div class="ms-role" style="color:var(--lot)">QC — Boxing &amp; Lot</div>
                        <div class="ms-act">4 boxes → LOT-2024-019 created</div>
                    </div>
                    <div class="ms-badge" style="background:rgba(52,211,153,.12);color:var(--lot)">✓ Done</div>
                </div>
                <div class="connector"></div>

                <div class="ms" style="border-color:rgba(245,158,11,.2)">
                    <div class="ms-ico" style="background:rgba(245,158,11,.12)">📋</div>
                    <div class="ms-body">
                        <div class="ms-role" style="color:var(--rmgr)">Runner Manager — Omar F.</div>
                        <div class="ms-act">Assigned Runner → Khalid T.</div>
                    </div>
                    <div class="ms-badge" style="background:rgba(52,211,153,.12);color:var(--lot)">✓ Done</div>
                </div>
                <div class="connector"></div>

                <div class="ms" style="border-color:rgba(248,113,113,.35); animation:pulseBorder 2s infinite;">
                    <div class="ms-ico" style="background:rgba(248,113,113,.12)">🏃</div>
                    <div class="ms-body">
                        <div class="ms-role" style="color:var(--runner)">Runner — Khalid T.</div>
                        <div class="ms-act">In transit · ETA 14 min</div>
                    </div>
                    <div class="ms-badge" style="background:rgba(248,113,113,.12);color:var(--runner)">● Live</div>
                </div>
                <div class="connector"></div>

                <div class="ms" style="border-color:rgba(255,255,255,.04); opacity:.45">
                    <div class="ms-ico" style="background:rgba(201,168,76,.07)">🏪</div>
                    <div class="ms-body">
                        <div class="ms-role" style="color:var(--smoke)">Store Manager — Pending</div>
                        <div class="ms-act">Awaiting delivery &amp; confirmation</div>
                    </div>
                    <div class="ms-badge" style="background:rgba(120,140,160,.1);color:var(--smoke)">Waiting</div>
                </div>
            </div>
        </div>
    </section>

    <div class="hr"></div>

    <!-- STATS -->
    <div class="stats-row a a3">
        <div class="stat-box"><div class="stat-big">6</div><div class="stat-lbl">Defined Roles</div></div>
        <div class="stat-box"><div class="stat-big">100%</div><div class="stat-lbl">Chain of Custody</div></div>
        <div class="stat-box"><div class="stat-big">0</div><div class="stat-lbl">Lost Transfers</div></div>
        <div class="stat-box"><div class="stat-big">Live</div><div class="stat-lbl">Real-Time Tracking</div></div>
    </div>

    <!-- ROLE LEGEND -->
    <div class="legend a a3">
        <div class="leg-item">
            <div class="leg-ico" style="background:rgba(78,158,245,.12);border:1px solid rgba(78,158,245,.2)">🧺</div>
            <div class="leg-name" style="color:var(--picker)">Picker</div>
            <div class="leg-desc">Prepares &amp; drops the transfer</div>
        </div>
        <div class="leg-item">
            <div class="leg-ico" style="background:rgba(167,139,250,.12);border:1px solid rgba(167,139,250,.2)">🔍</div>
            <div class="leg-name" style="color:var(--qc)">Quality Control</div>
            <div class="leg-desc">Verifies the transfer items</div>
        </div>
        <div class="leg-item">
            <div class="leg-ico" style="background:rgba(52,211,153,.12);border:1px solid rgba(52,211,153,.2)">📦</div>
            <div class="leg-name" style="color:var(--lot)">Box &amp; Lot</div>
            <div class="leg-desc">Packages into lots for dispatch</div>
        </div>
        <div class="leg-item">
            <div class="leg-ico" style="background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.2)">📋</div>
            <div class="leg-name" style="color:var(--rmgr)">Runner Manager</div>
            <div class="leg-desc">Reviews lots &amp; assigns runners</div>
        </div>
        <div class="leg-item">
            <div class="leg-ico" style="background:rgba(248,113,113,.12);border:1px solid rgba(248,113,113,.2)">🏃</div>
            <div class="leg-name" style="color:var(--runner)">Runner</div>
            <div class="leg-desc">Delivers lot to the store</div>
        </div>
        <div class="leg-item">
            <div class="leg-ico" style="background:rgba(201,168,76,.12);border:1px solid rgba(201,168,76,.2)">🏪</div>
            <div class="leg-name" style="color:var(--store)">Store Manager</div>
            <div class="leg-desc">Receives &amp; confirms delivery</div>
        </div>
    </div>

    <!-- WORKFLOW PIPELINE -->
    <section style="padding-bottom:80px">
        <div class="wf-header">
            <div class="sec-tag">The Transfer Journey</div>
            <h2>From Pick to <em>Receipt</em> — Every Step Matters</h2>
            <p class="sec-sub">Each transfer in 2B Inventory passes through a strict 6-stage chain of custody. No stage can be skipped. Every person is accountable. Everything is logged.</p>
        </div>

        <div class="pipeline">

            <!-- 1. PICKER -->
            <div class="pstep c-picker">
                <div class="pcard">
                    <div class="step-num"><div class="step-num-badge">01</div> Step One</div>
                    <div class="role-lbl">🧺 The Picker</div>
                    <h3>Prepare &amp; Drop the Transfer</h3>
                    <p>The journey begins with the Picker. They walk the warehouse floor, locate the required products, and compile a transfer request in the system. Once everything is verified on their end, they "drop" the transfer — formally submitting it to the Quality Control queue and marking the transfer as Prepared.</p>
                    <div class="tasks">
                        <div class="task"><div class="tck">✓</div>Locate and pull required items from shelves</div>
                        <div class="task"><div class="tck">✓</div>Build and submit the transfer request</div>
                        <div class="task"><div class="tck">✓</div>Set status to "Prepared" — handoff to QC</div>
                    </div>
                </div>
                <div class="pcenter"><div class="pnode">🧺</div></div>
                <div class="pempty"></div>
            </div>

            <!-- 2. QC VERIFY -->
            <div class="pstep c-qc">
                <div class="pempty"></div>
                <div class="pcenter"><div class="pnode">🔍</div></div>
                <div class="pcard">
                    <div class="step-num"><div class="step-num-badge">02</div> Step Two</div>
                    <div class="role-lbl">🔍 Quality Control — Verification</div>
                    <h3>Inspect &amp; Verify the Transfer</h3>
                    <p>The Quality Control officer receives the prepared transfer and begins their inspection. They compare the actual items against the transfer manifest — checking SKUs, quantities, and product condition. Any mismatch is flagged and resolved before the transfer can move forward. Only a verified transfer proceeds to the next stage.</p>
                    <div class="tasks">
                        <div class="task"><div class="tck">✓</div>Review transfer manifest item by item</div>
                        <div class="task"><div class="tck">✓</div>Physically inspect quantities and condition</div>
                        <div class="task"><div class="tck">✓</div>Approve the transfer to proceed</div>
                    </div>
                </div>
            </div>

            <!-- 3. BOX + LOT -->
            <div class="pstep c-lot">
                <div class="pcard">
                    <div class="step-num"><div class="step-num-badge">03</div> Step Three</div>
                    <div class="role-lbl">📦 Quality Control — Boxing &amp; Lot Creation</div>
                    <h3>Create Boxes &amp; Build the Lot</h3>
                    <p>With the transfer verified, the QC officer now organizes the items into physical shipping boxes and records them in the system. Multiple boxes are then grouped into a single Lot — a trackable dispatch unit that represents the full transfer. The Lot is now ready and waiting for the Runner Manager to pick it up.</p>
                    <div class="tasks">
                        <div class="task"><div class="tck">✓</div>Pack verified items into labeled boxes</div>
                        <div class="task"><div class="tck">✓</div>Create Lot record and assign box IDs</div>
                        <div class="task"><div class="tck">✓</div>Lot status set to "Ready for Runner"</div>
                    </div>
                </div>
                <div class="pcenter"><div class="pnode">📦</div></div>
                <div class="pempty"></div>
            </div>

            <!-- 4. RUNNER MANAGER -->
            <div class="pstep c-rmgr">
                <div class="pempty"></div>
                <div class="pcenter"><div class="pnode">📋</div></div>
                <div class="pcard">
                    <div class="step-num"><div class="step-num-badge">04</div> Step Four</div>
                    <div class="role-lbl">📋 Runner Manager</div>
                    <h3>Review Lots &amp; Assign Runners</h3>
                    <p>The Runner Manager is the logistics coordinator. They see a live list of all Lots waiting for delivery — with destination, weight, and urgency. They review runner availability and workloads, then assign one or more Runners to each Lot. Once assigned, the Runner receives a notification and the Lot enters "In Transit" status.</p>
                    <div class="tasks">
                        <div class="task"><div class="tck">✓</div>View all pending Lots from Quality Control</div>
                        <div class="task"><div class="tck">✓</div>Review runner availability and capacity</div>
                        <div class="task"><div class="tck">✓</div>Assign runner — Lot moves to "In Transit"</div>
                    </div>
                </div>
            </div>

            <!-- 5. RUNNER -->
            <div class="pstep c-runner">
                <div class="pcard">
                    <div class="step-num"><div class="step-num-badge">05</div> Step Five</div>
                    <div class="role-lbl">🏃 The Runner</div>
                    <h3>Collect &amp; Deliver the Lot</h3>
                    <p>The Runner gets their assignment and physically collects the boxed Lot from the QC area. They then transport it to the designated store or branch. Throughout the journey, their status is tracked live in the system — from "Collected" to "On the Way" to "Arrived." The transfer chain is nearly complete.</p>
                    <div class="tasks">
                        <div class="task"><div class="tck">✓</div>Collect the assigned Lot from QC area</div>
                        <div class="task"><div class="tck">✓</div>Update status to "On the Way" in app</div>
                        <div class="task"><div class="tck">✓</div>Arrive at store — hand off to Store Manager</div>
                    </div>
                </div>
                <div class="pcenter"><div class="pnode">🏃</div></div>
                <div class="pempty"></div>
            </div>

            <!-- 6. STORE MANAGER -->
            <div class="pstep c-store">
                <div class="pempty"></div>
                <div class="pcenter"><div class="pnode">🏪</div></div>
                <div class="pcard">
                    <div class="step-num"><div class="step-num-badge">06</div> Step Six — Final</div>
                    <div class="role-lbl">🏪 Store Manager</div>
                    <h3>Receive, Confirm &amp; Close the Transfer</h3>
                    <p>The Store Manager is the final gatekeeper. They receive the Runner, inspect the delivered boxes against the Lot record, confirm the quantities and condition, and officially close the transfer in the system. The moment they confirm receipt, the store's live inventory is updated automatically — the cycle is complete.</p>
                    <div class="tasks">
                        <div class="task"><div class="tck">✓</div>Receive boxes from Runner at the store</div>
                        <div class="task"><div class="tck">✓</div>Verify contents against the Lot manifest</div>
                        <div class="task"><div class="tck">✓</div>Confirm receipt — inventory updated live ✦</div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <div class="cta a a3">
        <div class="sec-tag">Your Team, Fully Coordinated</div>
        <h2>Ready to Run Your First <em>Transfer?</em></h2>
        <p>Give each of your 6 roles the right tools. No more WhatsApp chains, paper trails, or guesswork — just a clean, auditable pipeline from the first pick to the last receipt.</p>
        <div class="cta-btns">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-cta btn-primary">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Start Free — No Credit Card
                </a>
            @endif
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn-cta btn-outline">Log In to Account</a>
            @endif
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="foot-left">
            <img src="https://play-lh.googleusercontent.com/6dN3RXGBOzUnG1zLa2pIlgCMA3Hb0FFwnLc0An-DuL3QuSXjj1qeD3KMFR9jpXZtJDnrRiqVeE3oOIrhNdjvNw"
     alt="Company Logo"
     style="width:44px; height:44px; border-radius:10px; object-fit:cover;">
            <div class="foot-name"><b style="color: white;">2B Inventory</b> — Built for Egypt</div>
        </div>
        <div class="foot-right">Laravel v{{ Illuminate\Foundation\Application::VERSION }} · PHP v{{ PHP_VERSION }}</div>
    </footer>

</div>

<style>
@keyframes pulseBorder {
    0%,100%{box-shadow:0 0 0 0 rgba(248,113,113,0)}
    50%{box-shadow:0 0 0 4px rgba(248,113,113,.15)}
}
</style>
</body>
</html>