<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Apex League — Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800;900&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --pitch:#0a3d0a;--gold:#f0c040;--gold2:#c8930a;
  --accent:#00e5ff;--red:#ff3b3b;--green:#22c55e;
  --surface:#0d1117;--surface2:#161b24;--surface3:#1e2530;
  --border:#2a3340;--text:#e8edf4;--muted:#7a8899;
  --font-head:'Barlow Condensed',sans-serif;--font-body:'Barlow',sans-serif;
}
html,body{height:100%;overflow:hidden;background:var(--surface);color:var(--text);font-family:var(--font-body);font-size:11px}
a{color:inherit;text-decoration:none}
button{font-family:var(--font-body);cursor:pointer}

/* ── LAYOUT ── */
.shell{display:flex;height:100vh;overflow:hidden}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0}
.content{flex:1;overflow-y:auto;padding:16px 20px;display:flex;flex-direction:column;gap:14px}
.content::-webkit-scrollbar{width:4px}
.content::-webkit-scrollbar-thumb{background:var(--border);border-radius:2px}

/* ── SIDEBAR ── */
.sidebar{width:204px;min-width:204px;background:var(--surface2);border-right:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto;overflow-x:hidden}
.sidebar::-webkit-scrollbar{width:3px}
.sidebar::-webkit-scrollbar-thumb{background:var(--border)}
.sidebar-logo{padding:16px 14px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:9px;flex-shrink:0}
.logo-badge{width:34px;height:34px;background:linear-gradient(135deg,var(--gold),var(--gold2));border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-text{font-family:var(--font-head);font-size:15px;font-weight:900;letter-spacing:.5px;line-height:1.1}
.logo-sub{font-size:9px;color:var(--gold);letter-spacing:1.5px;font-weight:600;text-transform:uppercase}
.nav-section{padding:10px 0 4px;border-bottom:1px solid var(--border)}
.nav-label{font-size:9px;font-weight:700;letter-spacing:1.5px;color:var(--muted);text-transform:uppercase;padding:0 14px 6px}
.nav-item{display:flex;align-items:center;gap:8px;padding:8px 14px;cursor:pointer;border-left:2px solid transparent;transition:all .15s;font-size:11px;color:var(--muted);font-weight:500;user-select:none}
.nav-item:hover{background:rgba(255,255,255,.04);color:var(--text)}
.nav-item.active{background:rgba(0,229,255,.07);border-left-color:var(--accent);color:var(--accent)}
.nav-item svg{width:14px;height:14px;flex-shrink:0}
.badge-count{margin-left:auto;background:var(--red);color:#fff;font-size:9px;font-weight:700;padding:1px 5px;border-radius:10px;line-height:1.4}
.sidebar-footer{margin-top:auto;padding:12px 14px;border-top:1px solid var(--border);display:flex;align-items:center;gap:9px;flex-shrink:0}
.user-avatar{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#000;flex-shrink:0}
.user-name{font-size:11px;font-weight:600;color:var(--text)}
.user-role{font-size:9px;color:var(--gold);font-weight:700;letter-spacing:.3px}

/* ── TOPBAR ── */
.topbar{background:var(--surface2);border-bottom:1px solid var(--border);padding:0 20px;height:54px;display:flex;align-items:center;gap:12px;flex-shrink:0}
.page-title{font-family:var(--font-head);font-size:20px;font-weight:800;letter-spacing:.3px;color:var(--text)}
.page-sub{font-size:9px;color:var(--muted);margin-top:1px}
.tb-actions{display:flex;align-items:center;gap:8px;margin-left:auto}
.tb-btn{display:flex;align-items:center;gap:5px;padding:6px 11px;border-radius:5px;font-size:10px;font-weight:600;cursor:pointer;border:none;transition:all .15s;letter-spacing:.3px;white-space:nowrap}
.tb-btn.secondary{background:var(--surface3);color:var(--text);border:1px solid var(--border)}
.tb-btn.secondary:hover{background:var(--border)}
.tb-btn.primary{background:var(--accent);color:#000;border:none}
.tb-btn.primary:hover{background:#00cfee}
.tb-btn.danger{background:rgba(255,59,59,.15);color:var(--red);border:1px solid rgba(255,59,59,.3)}
.tb-btn svg{width:12px;height:12px}
.mw-badge{background:rgba(0,229,255,.1);border:1px solid rgba(0,229,255,.25);border-radius:5px;padding:4px 10px;font-size:9px;font-weight:700;color:var(--accent);letter-spacing:.5px}
.notif-btn{width:34px;height:34px;background:var(--surface3);border:1px solid var(--border);border-radius:5px;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative}
.notif-btn svg{width:15px;height:15px;color:var(--text)}
.notif-dot{position:absolute;top:7px;right:7px;width:7px;height:7px;background:var(--red);border-radius:50%;border:1.5px solid var(--surface2)}

/* ── CARDS ── */
.card{background:var(--surface2);border:1px solid var(--border);border-radius:8px;overflow:hidden}
.card-head{padding:10px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
.card-title{font-family:var(--font-head);font-size:13px;font-weight:700;letter-spacing:.3px;color:var(--text);display:flex;align-items:center;gap:6px}
.card-title svg{width:13px;height:13px;color:var(--accent)}
.card-action{font-size:10px;color:var(--accent);cursor:pointer;font-weight:600;white-space:nowrap}
.card-action:hover{text-decoration:underline}

/* ── STAT CARDS ── */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.stat-card{background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:13px;position:relative;overflow:hidden;cursor:pointer;transition:border-color .2s,transform .15s}
.stat-card:hover{border-color:var(--accent);transform:translateY(-1px)}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--sc,var(--accent))}
.stat-card.g{--sc:var(--green)}
.stat-card.r{--sc:var(--red)}
.stat-card.y{--sc:var(--gold)}
.stat-label{font-size:9px;font-weight:700;letter-spacing:1px;color:var(--muted);text-transform:uppercase;margin-bottom:7px}
.stat-val{font-family:var(--font-head);font-size:28px;font-weight:900;color:var(--text);line-height:1}
.stat-change{font-size:9px;margin-top:5px;display:flex;align-items:center;gap:3px}
.stat-change.up{color:var(--green)}
.stat-change.dn{color:var(--red)}
.stat-change.nu{color:var(--muted)}
.stat-icon{position:absolute;right:12px;top:12px;opacity:.1}
.stat-icon svg{width:30px;height:30px}

/* ── GRIDS ── */
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.grid3{display:grid;grid-template-columns:2fr 1fr;gap:12px}
.grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:0}

/* ── MATCH ── */
.match-item{padding:10px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;cursor:pointer;transition:background .15s}
.match-item:last-child{border-bottom:none}
.match-item:hover{background:rgba(255,255,255,.025)}
.ms{font-size:8px;font-weight:800;letter-spacing:.6px;padding:3px 7px;border-radius:3px;text-transform:uppercase;flex-shrink:0;min-width:48px;text-align:center}
.ms.live{background:rgba(255,59,59,.15);color:var(--red);animation:blink 1.5s infinite}
.ms.ft{background:rgba(122,136,153,.12);color:var(--muted)}
.ms.ns{background:rgba(0,229,255,.1);color:var(--accent)}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.45}}
.match-teams{flex:1;display:flex;align-items:center;justify-content:space-between}
.match-team{display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600}
.team-badge{border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;flex-shrink:0;text-align:center;line-height:1}
.score-box{font-family:var(--font-head);font-size:16px;font-weight:900;padding:0 10px;text-align:center;min-width:40px}

/* ── LEAGUE TABLE ── */
.league-table{width:100%;border-collapse:collapse}
.league-table th{font-size:9px;font-weight:700;letter-spacing:.8px;color:var(--muted);text-transform:uppercase;padding:8px 10px;border-bottom:1px solid var(--border);text-align:center;white-space:nowrap}
.league-table th:nth-child(2){text-align:left}
.league-table td{padding:7px 10px;border-bottom:1px solid rgba(42,51,64,.5);font-size:10px;text-align:center;color:var(--text);white-space:nowrap}
.league-table td:nth-child(2){text-align:left}
.league-table tr:last-child td{border-bottom:none}
.league-table tbody tr:hover td{background:rgba(255,255,255,.02)}
.pos-num{font-family:var(--font-head);font-weight:800;font-size:13px;width:22px;display:inline-block;text-align:center}
.pos-num.cl{color:var(--accent)}
.pos-num.eur{color:var(--green)}
.pos-num.rel{color:var(--red)}
.pos-num.norm{color:var(--muted)}
.pts-col{font-family:var(--font-head);font-weight:900;font-size:14px}
.team-row{display:flex;align-items:center;gap:7px}
.form-dots{display:flex;gap:2px;align-items:center}
.form-dot{width:8px;height:8px;border-radius:50%}
.form-dot.w{background:var(--green)}
.form-dot.d{background:var(--gold)}
.form-dot.l{background:var(--red)}

/* ── SCORER ── */
.scorer-item{padding:9px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;cursor:pointer;transition:background .15s}
.scorer-item:last-child{border-bottom:none}
.scorer-item:hover{background:rgba(255,255,255,.025)}
.scorer-rank{font-family:var(--font-head);font-weight:900;font-size:15px;color:var(--muted);width:18px;text-align:center;flex-shrink:0}
.scorer-rank.gold{color:var(--gold)}
.scorer-rank.silver{color:#aab4be}
.scorer-rank.bronze{color:#c8860a}
.scorer-info{flex:1}
.scorer-name{font-size:11px;font-weight:600;color:var(--text)}
.scorer-club{font-size:9px;color:var(--muted)}
.goal-bar{width:60px;height:3px;background:var(--surface3);border-radius:2px;overflow:hidden;flex-shrink:0}
.goal-fill{height:100%;background:var(--accent);border-radius:2px}
.scorer-goals{font-family:var(--font-head);font-weight:900;font-size:17px;color:var(--accent);min-width:24px;text-align:right}

/* ── NEWS ── */
.news-item{padding:10px 14px;border-bottom:1px solid var(--border);display:flex;gap:10px;cursor:pointer;transition:background .15s}
.news-item:last-child{border-bottom:none}
.news-item:hover{background:rgba(255,255,255,.025)}
.news-thumb{width:44px;height:44px;border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:20px}
.news-tag{font-size:8px;font-weight:800;letter-spacing:.8px;text-transform:uppercase;color:var(--accent);margin-bottom:3px}
.news-title{font-size:10px;font-weight:600;color:var(--text);line-height:1.4;margin-bottom:2px}
.news-time{font-size:9px;color:var(--muted)}

/* ── TRANSFER ── */
.transfer-item{padding:9px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;font-size:10px;cursor:pointer;transition:background .15s}
.transfer-item:hover{background:rgba(255,255,255,.025)}
.transfer-item:last-child{border-bottom:none}
.t-status{font-size:8px;font-weight:800;letter-spacing:.4px;padding:2px 7px;border-radius:3px;text-transform:uppercase;flex-shrink:0}
.t-status.done{background:rgba(34,197,94,.15);color:var(--green)}
.t-status.pending{background:rgba(240,192,64,.15);color:var(--gold)}
.t-status.override{background:rgba(255,59,59,.15);color:var(--red)}

/* ── AWARDS ── */
.award-item{padding:10px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;cursor:pointer;transition:background .15s}
.award-item:last-child{border-bottom:none}
.award-item:hover{background:rgba(255,255,255,.025)}
.award-icon{width:34px;height:34px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.award-name{font-size:11px;font-weight:600;color:var(--text)}
.award-winner{font-size:9px;color:var(--muted);margin-top:1px}
.award-val{font-family:var(--font-head);font-weight:800;font-size:12px;color:var(--gold);margin-left:auto;flex-shrink:0}

/* ── USERS ── */
.user-row{padding:9px 14px;border-bottom:1px solid var(--border);display:grid;align-items:center;cursor:pointer;transition:background .15s}
.user-row:hover{background:rgba(255,255,255,.025)}
.role-badge{font-size:8px;font-weight:800;padding:2px 7px;border-radius:3px;text-transform:uppercase;letter-spacing:.3px}
.role-badge.superadmin{background:rgba(240,192,64,.15);color:var(--gold)}
.role-badge.admin{background:rgba(0,229,255,.1);color:var(--accent)}
.role-badge.editor{background:rgba(34,197,94,.1);color:var(--green)}
.role-badge.viewer{background:rgba(122,136,153,.12);color:var(--muted)}
.online-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}
.online-dot.on{background:var(--green)}
.online-dot.off{background:var(--muted)}

/* ── STATUS BADGES ── */
.status-badge{font-size:8px;font-weight:800;padding:2px 7px;border-radius:3px;text-transform:uppercase;letter-spacing:.3px}
.status-badge.active{background:rgba(34,197,94,.12);color:var(--green)}
.status-badge.injured{background:rgba(240,192,64,.15);color:var(--gold)}
.status-badge.suspended{background:rgba(255,59,59,.12);color:var(--red)}
.status-badge.published{background:rgba(34,197,94,.12);color:var(--green)}
.status-badge.draft{background:rgba(240,192,64,.15);color:var(--gold)}
.status-badge.flagged{background:rgba(255,59,59,.12);color:var(--red)}

/* ── TABS ── */
.tab-bar{display:flex;border-bottom:1px solid var(--border)}
.tab{padding:9px 14px;font-size:10px;font-weight:700;cursor:pointer;color:var(--muted);border-bottom:2px solid transparent;transition:all .15s;letter-spacing:.3px;user-select:none}
.tab.active{color:var(--accent);border-bottom-color:var(--accent)}
.tab:hover:not(.active){color:var(--text)}

/* ── SCROLLABLE PANEL ── */
.panel-scroll{overflow-y:auto;max-height:260px}
.panel-scroll::-webkit-scrollbar{width:3px}
.panel-scroll::-webkit-scrollbar-thumb{background:var(--border);border-radius:2px}

/* ── QUICK ACTIONS ── */
.qa-grid{padding:10px;display:grid;grid-template-columns:1fr 1fr;gap:6px}
.qa-btn{background:var(--surface3);border:1px solid var(--border);color:var(--text);border-radius:5px;padding:9px 6px;font-size:10px;font-weight:600;cursor:pointer;transition:all .15s;text-align:center;font-family:var(--font-body)}
.qa-btn:hover{background:var(--border);border-color:var(--accent);color:var(--accent)}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.fu{animation:fadeUp .35s ease both}
.d1{animation-delay:.04s}.d2{animation-delay:.09s}.d3{animation-delay:.14s}.d4{animation-delay:.19s}

/* ── PITCH ── */
.pitch-wrap{background:var(--surface3);padding:12px;display:flex;justify-content:center}
.pitch{width:160px;height:220px;background:#0d4f0d;border-radius:5px;border:1px solid rgba(255,255,255,.08);position:relative;overflow:hidden;flex-shrink:0}
.pl{position:absolute;border:1px solid rgba(255,255,255,.12)}
.pl.outer{inset:8px}
.pl.mid{left:8px;right:8px;top:50%;height:0}
.pl.cc{width:46px;height:46px;border-radius:50%;top:50%;left:50%;transform:translate(-50%,-50%)}
.pl.bt{left:50%;top:8px;width:70px;height:36px;transform:translateX(-50%)}
.pl.bb{left:50%;bottom:8px;width:70px;height:36px;transform:translateX(-50%)}
.pl.gt{left:50%;top:8px;width:30px;height:10px;transform:translateX(-50%)}
.pl.gb{left:50%;bottom:8px;width:30px;height:10px;transform:translateX(-50%)}
.pdot{position:absolute;width:9px;height:9px;border-radius:50%;transform:translate(-50%,-50%);cursor:pointer;transition:transform .2s}
.pdot:hover{transform:translate(-50%,-50%) scale(1.6)}
.pdot.home{background:#00e5ff;box-shadow:0 0 8px #00e5ff88}
.pdot.away{background:#ff3b3b;box-shadow:0 0 8px #ff3b3b88}

/* ── TABLE HEADER ── */
.tbl-header{padding:8px 14px;border-bottom:1px solid var(--border);display:flex;font-size:9px;font-weight:700;letter-spacing:.8px;color:var(--muted);text-transform:uppercase;gap:8px}

/* ── DIVIDER ── */
.section-divider{display:flex;align-items:center;gap:8px;margin:-4px 0}
.section-divider span{font-size:9px;font-weight:700;letter-spacing:1.5px;color:var(--muted);text-transform:uppercase;white-space:nowrap}
.section-divider::before,.section-divider::after{content:'';flex:1;height:1px;background:var(--border)}

/* ── LIVE TICKER ── */
.ticker-bar{background:linear-gradient(90deg,var(--surface2),var(--surface3));border-bottom:1px solid var(--border);height:28px;overflow:hidden;display:flex;align-items:center;flex-shrink:0}
.ticker-label{background:var(--red);color:#fff;font-size:9px;font-weight:800;padding:0 12px;height:100%;display:flex;align-items:center;letter-spacing:1px;flex-shrink:0;animation:blink 1.5s infinite}
.ticker-track{display:flex;animation:ticker 40s linear infinite;white-space:nowrap;align-items:center}
.ticker-track:hover{animation-play-state:paused}
@keyframes ticker{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
.ticker-item{padding:0 28px;font-size:9px;color:var(--muted);display:flex;align-items:center;gap:6px;flex-shrink:0}
.ticker-item strong{color:var(--text);font-weight:600}
.ticker-sep{color:var(--border);font-size:14px}

/* ── PAGES ── */
.page{display:none}
.page.active{display:flex;flex-direction:column;gap:14px}
</style>
</head>
<body>
<div class="shell">

<!-- ═══════════ SIDEBAR ═══════════ -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-badge">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20M12 2c-3 4-3 14 0 20M12 2c3 4 3 14 0 20"/></svg>
    </div>
    <div>
      <div class="logo-text">APEX LEAGUE</div>
      <div class="logo-sub">Admin Panel</div>
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Overview</div>
    <div class="nav-item active" data-page="dashboard">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Management</div>
    <div class="nav-item" data-page="teams">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Teams
    </div>
    <div class="nav-item" data-page="players">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a6 6 0 0 1 12 0v2"/></svg>
      Players
    </div>
    <div class="nav-item" data-page="matches">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20M12 2c-3 4-3 14 0 20"/></svg>
      Matches
    </div>
    <div class="nav-item" data-page="transfers">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4 4 4M17 8v12m0 0 4-4m-4 4-4-4"/></svg>
      Transfers
      <span class="badge-count">3</span>
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">Competition</div>
    <div class="nav-item" data-page="leaderboard">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
      Leaderboard
    </div>
    <div class="nav-item" data-page="awards">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 21h8M12 17v4M7 4H4a1 1 0 0 0-1 1v3a4 4 0 0 0 4 4h10a4 4 0 0 0 4-4V5a1 1 0 0 0-1-1h-3"/><rect x="7" y="2" width="10" height="11" rx="2"/></svg>
      Awards
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-label">System</div>
    <div class="nav-item" data-page="users">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Users
    </div>
    <div class="nav-item" data-page="news">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a4 4 0 0 1-4-4V6"/><path d="M10 8h6M10 12h6M10 16h4"/></svg>
      News
      <span class="badge-count">2</span>
    </div>
  </div>

  <div class="sidebar-footer">
    <div class="user-avatar">SA</div>
    <div>
      <div class="user-name">Admin</div>
      <div class="user-role">⚡ Full Access</div>
    </div>
  </div>
</aside>

<!-- ═══════════ MAIN ═══════════ -->
<div class="main">

  <!-- LIVE TICKER -->
  <div class="ticker-bar">
    <div class="ticker-label">LIVE</div>
    <div style="overflow:hidden;flex:1">
      <div class="ticker-track">
        <span class="ticker-item"><strong>Man City 3–1 Arsenal</strong> <span style="color:var(--red)">67'</span></span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item"><strong>Spurs 0–0 Chelsea</strong> <span style="color:var(--red)">44'</span></span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">GOAL ⚽ <strong>E. Haaland</strong> (Man City) 62'</span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">YELLOW 🟨 <strong>B. White</strong> (Arsenal) 58'</span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">Liverpool vs Everton — <strong>Kicks off 18:30</strong></span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">Transfer: <strong>M. Olise £52m</strong> — Completed</span>
        <span class="ticker-sep">|</span>
        <!-- duplicate for seamless scroll -->
        <span class="ticker-item"><strong>Man City 3–1 Arsenal</strong> <span style="color:var(--red)">67'</span></span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item"><strong>Spurs 0–0 Chelsea</strong> <span style="color:var(--red)">44'</span></span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">GOAL ⚽ <strong>E. Haaland</strong> (Man City) 62'</span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">YELLOW 🟨 <strong>B. White</strong> (Arsenal) 58'</span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">Liverpool vs Everton — <strong>Kicks off 18:30</strong></span>
        <span class="ticker-sep">|</span>
        <span class="ticker-item">Transfer: <strong>M. Olise £52m</strong> — Completed</span>
        <span class="ticker-sep">|</span>
      </div>
    </div>
  </div>

  <!-- TOPBAR -->
  <div class="topbar">
    <div>
      <div class="page-title" id="page-title">Dashboard</div>
      <div class="page-sub" id="page-sub">Season 2024/25 · Matchweek 32</div>
    </div>
    <div class="tb-actions">
      <div class="mw-badge">MW 32 / 38</div>
      <button class="tb-btn secondary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Report
      </button>
      <button class="tb-btn primary" id="main-action-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        <span id="main-action-label">New Match</span>
      </button>
      <div class="notif-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <div class="notif-dot"></div>
      </div>
    </div>
  </div>
  <div class="content" id="content">
    @yield('content')
  </div>

  
</div><!-- end shell -->

<script>
/* ── DATA ── */
const TEAMS=[
  {n:'Man City',b:'MC',c:'#1a3cff',mgr:'P. Guardiola',st:'city'},
  {n:'Arsenal',b:'ARS',c:'#ef0107',mgr:'M. Arteta'},
  {n:'Liverpool',b:'LIV',c:'#d00027',mgr:'J. Klopp'},
  {n:'Aston Villa',b:'AVL',c:'#6c1d45',mgr:'U. Emery'},
  {n:'Tottenham',b:'TOT',c:'#132257',mgr:'A. Postecoglou'},
  {n:'Chelsea',b:'CHE',c:'#034694',mgr:'M. Pochettino'},
  {n:'Newcastle',b:'NEW',c:'#241f20',mgr:'E. Howe'},
  {n:'Man Utd',b:'MUN',c:'#e21a23',mgr:'E. ten Hag'},
  {n:'Brighton',b:'BHA',c:'#0057b8',mgr:'R. De Zerbi'},
  {n:'West Ham',b:'WHU',c:'#7a263a',mgr:'D. Moyes'},
  {n:'Wolves',b:'WOL',c:'#fdb913',mgr:'G. O\'Neil'},
  {n:'Everton',b:'EVE',c:'#003399',mgr:'S. Dyche'},
  {n:'Crystal Palace',b:'CRY',c:'#1b458f',mgr:'O. Glasner'},
  {n:'Fulham',b:'FUL',c:'#cc0000',mgr:'M. Silva'},
  {n:'Brentford',b:'BRE',c:'#d20000',mgr:'T. Frank'},
  {n:'Nottm Forest',b:'NFO',c:'#e53233',mgr:'N. Cooper'},
  {n:'Bournemouth',b:'BOU',c:'#da291c',mgr:'A. Iraola'},
  {n:'Luton',b:'LUT',c:'#f78f1e',mgr:'R. Edwards'},
  {n:'Burnley',b:'BUR',c:'#6c1d45',mgr:'V. Kompany'},
  {n:'Sheffield Utd',b:'SHU',c:'#e21a23',mgr:'C. Wilder'},
];

const STANDINGS=[
  {pos:1,n:'Man City',b:'MC',c:'#1a3cff',p:32,w:22,d:6,l:4,gf:68,ga:28,pts:72,form:['w','w','w','d','w'],zone:'cl'},
  {pos:2,n:'Arsenal',b:'ARS',c:'#ef0107',p:32,w:20,d:6,l:6,gf:60,ga:32,pts:66,form:['l','w','w','w','d'],zone:'cl'},
  {pos:3,n:'Liverpool',b:'LIV',c:'#d00027',p:32,w:19,d:7,l:6,gf:65,ga:34,pts:64,form:['w','w','d','l','w'],zone:'cl'},
  {pos:4,n:'Aston Villa',b:'AVL',c:'#6c1d45',p:32,w:18,d:6,l:8,gf:58,ga:42,pts:60,form:['d','w','l','w','w'],zone:'cl'},
  {pos:5,n:'Tottenham',b:'TOT',c:'#132257',p:32,w:15,d:8,l:9,gf:55,ga:48,pts:53,form:['w','d','w','l','d'],zone:'eur'},
  {pos:6,n:'Chelsea',b:'CHE',c:'#034694',p:32,w:14,d:9,l:9,gf:52,ga:44,pts:51,form:['d','l','w','d','w'],zone:'eur'},
  {pos:7,n:'Newcastle',b:'NEW',c:'#241f20',p:32,w:14,d:7,l:11,gf:48,ga:46,pts:49,form:['d','w','d','l','w'],zone:''},
  {pos:8,n:'Man Utd',b:'MUN',c:'#e21a23',p:32,w:13,d:6,l:13,gf:42,ga:46,pts:45,form:['l','l','w','d','l'],zone:''},
  {pos:9,n:'Brighton',b:'BHA',c:'#0057b8',p:32,w:12,d:9,l:11,gf:50,ga:46,pts:45,form:['w','d','w','l','d'],zone:''},
  {pos:10,n:'West Ham',b:'WHU',c:'#7a263a',p:32,w:12,d:7,l:13,gf:44,ga:52,pts:43,form:['l','w','d','l','w'],zone:''},
  {pos:11,n:'Wolves',b:'WOL',c:'#fdb913',p:32,w:11,d:7,l:14,gf:40,ga:50,pts:40,form:['w','l','d','w','l'],zone:''},
  {pos:12,n:'Everton',b:'EVE',c:'#003399',p:32,w:10,d:8,l:14,gf:36,ga:52,pts:38,form:['d','l','w','d','l'],zone:''},
  {pos:13,n:'Crystal Palace',b:'CRY',c:'#1b458f',p:32,w:9,d:10,l:13,gf:34,ga:48,pts:37,form:['d','d','l','w','d'],zone:''},
  {pos:14,n:'Fulham',b:'FUL',c:'#cc0000',p:32,w:10,d:7,l:15,gf:40,ga:56,pts:37,form:['l','w','l','d','l'],zone:''},
  {pos:15,n:'Brentford',b:'BRE',c:'#d20000',p:32,w:9,d:8,l:15,gf:44,ga:60,pts:35,form:['d','l','l','w','d'],zone:''},
  {pos:16,n:'Nottm Forest',b:'NFO',c:'#e53233',p:32,w:8,d:9,l:15,gf:36,ga:56,pts:33,form:['l','d','l','l','d'],zone:''},
  {pos:17,n:'Bournemouth',b:'BOU',c:'#da291c',p:32,w:8,d:7,l:17,gf:38,ga:62,pts:31,form:['l','l','w','l','d'],zone:''},
  {pos:18,n:'Luton',b:'LUT',c:'#f78f1e',p:32,w:6,d:5,l:21,gf:28,ga:64,pts:23,form:['l','l','d','l','l'],zone:'rel'},
  {pos:19,n:'Burnley',b:'BUR',c:'#6c1d45',p:32,w:5,d:4,l:23,gf:25,ga:72,pts:19,form:['l','l','l','d','l'],zone:'rel'},
  {pos:20,n:'Sheffield Utd',b:'SHU',c:'#e21a23',p:32,w:3,d:5,l:24,gf:22,ga:80,pts:14,form:['l','l','l','l','d'],zone:'rel'},
];

const SCORERS=[
  {name:'E. Haaland',club:'Man City',nat:'🇳🇴',goals:24,pct:100,rc:'gold'},
  {name:'O. Watkins',club:'Aston Villa',nat:'🏴󠁧󠁢󠁥󠁮󠁧󠁿',goals:18,pct:75,rc:'silver'},
  {name:'A. Lacazette',club:'Arsenal',nat:'🇫🇷',goals:15,pct:63,rc:'bronze'},
  {name:'M. Salah',club:'Liverpool',nat:'🇪🇬',goals:14,pct:58,rc:''},
  {name:'J. Wilson',club:'Newcastle',nat:'🏴󠁧󠁢󠁥󠁮󠁧󠁿',goals:13,pct:54,rc:''},
];

const MATCHES_LIVE=[
  {h:'Man City',hb:'MC',hc:'#1a3cff',a:'Arsenal',ab:'ARS',ac:'#ef0107',score:'3–1',status:'LIVE 67\'',type:'live'},
  {h:'Tottenham',hb:'TOT',hc:'#132257',a:'Chelsea',ab:'CHE',ac:'#034694',score:'0–0',status:'LIVE 44\'',type:'live'},
];
const MATCHES_UPCOMING=[
  {h:'Liverpool',hb:'LIV',hc:'#d00027',a:'Everton',ab:'EVE',ac:'#003399',score:'vs',status:'18:30 Today',type:'ns'},
  {h:'Man Utd',hb:'MUN',hc:'#e21a23',a:'Wolves',ab:'WOL',ac:'#fdb913',score:'vs',status:'Apr 7 · 14:00',type:'ns'},
  {h:'Aston Villa',hb:'AVL',hc:'#6c1d45',a:'Brighton',ab:'BHA',ac:'#0057b8',score:'vs',status:'Apr 7 · 16:30',type:'ns'},
];
const MATCHES_RESULTS=[
  {h:'Aston Villa',hb:'AVL',hc:'#6c1d45',a:'Newcastle',ab:'NEW',ac:'#241f20',score:'2–2',status:'FT',type:'ft'},
  {h:'Man Utd',hb:'MUN',hc:'#e21a23',a:'Brighton',ab:'BHA',ac:'#0057b8',score:'1–3',status:'FT',type:'ft'},
  {h:'Wolves',hb:'WOL',hc:'#fdb913',a:'Brentford',ab:'BRE',ac:'#d20000',score:'0–1',status:'FT',type:'ft'},
];

const TRANSFERS=[
  {pl:'A. Nunez',from:'Benfica',to:'Liverpool',fee:'£65m',status:'done'},
  {pl:'M. Olise',from:'Crystal Palace',to:'Bayern Munich',fee:'£52m',status:'done'},
  {pl:'J. Timber',from:'Ajax',to:'Arsenal',fee:'£40m',status:'done'},
  {pl:'T. Werner',from:'Chelsea',to:'Spurs',fee:'£25m',status:'pending'},
  {pl:'D. Kulusevski',from:'Spurs',to:'Juventus',fee:'Loan',status:'pending'},
  {pl:'B. Rashford',from:'Man Utd',to:'PSG',fee:'£45m',status:'pending'},
  {pl:'K. Havertz',from:'Chelsea',to:'Arsenal',fee:'£65m',status:'override'},
];

const AWARDS=[
  {icon:'🏆',bg:'rgba(240,192,64,.12)',name:'Golden Boot',winner:'E. Haaland · Man City',val:'24 Goals'},
  {icon:'🧤',bg:'rgba(0,229,255,.1)',name:'Golden Glove',winner:'A. Raya · Arsenal',val:'16 Clean Sheets'},
  {icon:'⭐',bg:'rgba(34,197,94,.1)',name:'Player of the Season',winner:'TBD — Voting Active',val:'Open Vote'},
  {icon:'🌟',bg:'rgba(255,59,59,.1)',name:'Young Player of the Season',winner:'B. Saka · Arsenal',val:'2024/25'},
  {icon:'👔',bg:'rgba(240,192,64,.1)',name:'Manager of the Season',winner:'P. Guardiola · Man City',val:'2024/25'},
  {icon:'📈',bg:'rgba(0,229,255,.08)',name:'Most Assists',winner:'K. De Bruyne · Man City',val:'16 Assists'},
];

const USERS=[
  {name:'Super Admin',email:'admin@apexleague.com',role:'superadmin',rl:'Super Admin',last:'Now',online:true},
  {name:'James Mitchell',email:'j.mitchell@apex.com',role:'admin',rl:'Admin',last:'1h ago',online:false},
  {name:'Sarah Connor',email:'s.connor@apex.com',role:'editor',rl:'Editor',last:'30m ago',online:true},
  {name:'Tom Bradley',email:'t.bradley@apex.com',role:'editor',rl:'Editor',last:'2h ago',online:false},
  {name:'Lisa Park',email:'l.park@apex.com',role:'viewer',rl:'Viewer',last:'Yesterday',online:false},
  {name:'Marcus Green',email:'m.green@apex.com',role:'viewer',rl:'Viewer',last:'3 days ago',online:false},
];

const NEWS=[
  {title:'Man City edge closer to fourth consecutive title after Gunners slip-up',tag:'Title Race',time:'2 hours ago',status:'published',icon:'🏆',bg:'rgba(240,192,64,.1)'},
  {title:'Three key players ruled out for upcoming Merseyside Derby weekend',tag:'Injury Update',time:'5 hours ago',status:'published',icon:'🚑',bg:'rgba(255,59,59,.1)'},
  {title:'£52m deal confirmed: striker completes move ahead of deadline day',tag:'Transfer',time:'Yesterday',status:'published',icon:'🔄',bg:'rgba(0,229,255,.1)'},
  {title:'Season statistics report — Matchweek 31 full analysis now available',tag:'Stats',time:'2 days ago',status:'published',icon:'📊',bg:'rgba(34,197,94,.1)'},
  {title:'New stadium development: planning approved for 2026 construction',tag:'Announcement',time:'3 days ago',status:'draft',icon:'🏗️',bg:'rgba(122,136,153,.1)'},
  {title:'VAR controversy: review committee issues formal statement on decisions',tag:'Regulation',time:'4 days ago',status:'flagged',icon:'⚠️',bg:'rgba(255,59,59,.1)'},
];

const PLAYERS=[
  {name:'E. Haaland',pos:'ST',team:'Man City',nat:'🇳🇴',age:23,rating:94,status:'active'},
  {name:'B. Saka',pos:'RW',team:'Arsenal',nat:'🏴󠁧󠁢󠁥󠁮󠁧󠁿',age:22,rating:88,status:'active'},
  {name:'M. Salah',pos:'RW',team:'Liverpool',nat:'🇪🇬',age:31,rating:90,status:'active'},
  {name:'R. James',pos:'RB',team:'Chelsea',nat:'🏴󠁧󠁢󠁥󠁮󠁧󠁿',age:24,rating:85,status:'injured'},
  {name:'K. Walker',pos:'RB',team:'Man City',nat:'🏴󠁧󠁢󠁥󠁮󠁧󠁿',age:33,rating:83,status:'active'},
  {name:'P. Dybala',pos:'AM',team:'Juventus',nat:'🇦🇷',age:30,rating:86,status:'suspended'},
  {name:'V. Kompany',pos:'CB',team:'Burnley',nat:'🇧🇪',age:37,rating:80,status:'active'},
  {name:'T. Werner',pos:'LW',team:'Spurs',nat:'🇩🇪',age:27,rating:82,status:'active'},
];

/* ── HELPERS ── */
function tb(b,c,sz=18){return`<div class="team-badge" style="width:${sz}px;height:${sz}px;font-size:${Math.round(sz*.38)}px;background:${c};color:#fff">${b}</div>`;}
function formDots(arr){return`<div class="form-dots">${arr.map(f=>`<div class="form-dot ${f}"></div>`).join('')}</div>`;}
function editDel(){return`<button class="tb-btn secondary" style="font-size:8px;padding:2px 5px">Edit</button><button class="tb-btn secondary" style="font-size:8px;padding:2px 5px;color:var(--red)">✕</button>`;}

/* ── RENDERS ── */
function renderMatchCenter(tab='live'){
  const sets={live:MATCHES_LIVE,upcoming:MATCHES_UPCOMING,results:MATCHES_RESULTS};
  const data=sets[tab]||MATCHES_LIVE;
  return data.map(m=>`
    <div class="match-item">
      <span class="ms ${m.type}">${m.status}</span>
      <div class="match-teams">
        <div class="match-team">${tb(m.hb,m.hc)} ${m.h}</div>
        <div class="score-box">${m.score}</div>
        <div class="match-team">${m.a} ${tb(m.ab,m.ac)}</div>
      </div>
      <button class="tb-btn secondary" style="font-size:8px;padding:2px 6px;flex-shrink:0">Stats</button>
    </div>`).join('');
}

function renderTopScorers(){
  return SCORERS.map((s,i)=>`
    <div class="scorer-item">
      <div class="scorer-rank ${s.rc}">${i+1}</div>
      <div style="width:26px;height:26px;border-radius:50%;background:rgba(0,229,255,.1);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:var(--accent);flex-shrink:0">${s.nat}</div>
      <div class="scorer-info"><div class="scorer-name">${s.name}</div><div class="scorer-club">${s.club}</div></div>
      <div class="goal-bar"><div class="goal-fill" style="width:${s.pct}%"></div></div>
      <div class="scorer-goals">${s.goals}</div>
    </div>`).join('');
}

function renderMiniTable(){
  const head=`<thead><tr><th>#</th><th>Club</th><th>P</th><th>W</th><th>D</th><th>L</th><th>GD</th><th>Pts</th><th>Form</th></tr></thead>`;
  const body=STANDINGS.slice(0,8).map(t=>{
    const gd=t.gf-t.ga;const gds=(gd>0?'+':'')+gd;
    const cl=t.zone==='cl'?'cl':t.zone==='eur'?'eur':t.zone==='rel'?'rel':'norm';
    return`<tr>
      <td><span class="pos-num ${cl}">${t.pos}</span></td>
      <td><div class="team-row">${tb(t.b,t.c,16)} ${t.n}</div></td>
      <td>${t.p}</td><td>${t.w}</td><td>${t.d}</td><td>${t.l}</td>
      <td style="color:${gd>=0?'var(--green)':'var(--red)'}">${gds}</td>
      <td><span class="pts-col">${t.pts}</span></td>
      <td>${formDots(t.form)}</td>
    </tr>`;}).join('');
  return head+'<tbody>'+body+'</tbody>';
}

function renderFullTable(){
  const head=`<thead><tr><th>#</th><th>Club</th><th>P</th><th>W</th><th>D</th><th>L</th><th>GF</th><th>GA</th><th>GD</th><th>Pts</th><th>Form</th></tr></thead>`;
  const body=STANDINGS.map(t=>{
    const gd=t.gf-t.ga;const gds=(gd>0?'+':'')+gd;
    const cl=t.zone==='cl'?'cl':t.zone==='eur'?'eur':t.zone==='rel'?'rel':'norm';
    return`<tr>
      <td><span class="pos-num ${cl}">${t.pos}</span></td>
      <td><div class="team-row">${tb(t.b,t.c,16)} ${t.n}</div></td>
      <td>${t.p}</td><td>${t.w}</td><td>${t.d}</td><td>${t.l}</td>
      <td>${t.gf}</td><td>${t.ga}</td>
      <td style="color:${gd>=0?'var(--green)':'var(--red)'}">${gds}</td>
      <td><span class="pts-col">${t.pts}</span></td>
      <td>${formDots(t.form)}</td>
    </tr>`;}).join('');
  return head+'<tbody>'+body+'</tbody>';
}

function renderNewsFeed(){
  return NEWS.slice(0,4).map(n=>`
    <div class="news-item">
      <div class="news-thumb" style="background:${n.bg}">${n.icon}</div>
      <div><div class="news-tag">${n.tag}</div><div class="news-title">${n.title}</div><div class="news-time">${n.time}</div></div>
    </div>`).join('');
}

function renderTeams(){
  return TEAMS.map(t=>`
    <div style="padding:12px;border-bottom:1px solid var(--border);border-right:1px solid var(--border);cursor:pointer;transition:background .15s" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background=''">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:7px">
        ${tb(t.b,t.c,28)}
        <div><div style="font-weight:600;font-size:11px;color:var(--text)">${t.n}</div><div style="font-size:9px;color:var(--muted)">${t.mgr}</div></div>
      </div>
      <div style="display:flex;gap:4px">${editDel()}<button class="tb-btn secondary" style="font-size:8px;padding:2px 5px">Squad</button></div>
    </div>`).join('');
}

function renderPlayers(){
  const head=`<div style="padding:7px 14px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:1fr 40px 1fr 36px 48px 70px 70px;gap:8px;font-size:9px;font-weight:700;letter-spacing:.7px;color:var(--muted);text-transform:uppercase"><div>Player</div><div>Pos</div><div>Team</div><div>Age</div><div>OVR</div><div>Status</div><div>Actions</div></div>`;
  const rows=PLAYERS.map(p=>`
    <div style="padding:8px 14px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:1fr 40px 1fr 36px 48px 70px 70px;gap:8px;align-items:center;font-size:10px;cursor:pointer" onmouseover="this.style.background='rgba(255,255,255,.025)'" onmouseout="this.style.background=''">
      <div style="font-weight:600">${p.nat} ${p.name}</div>
      <div style="background:rgba(0,229,255,.1);color:var(--accent);padding:2px 5px;border-radius:3px;font-size:8px;font-weight:800;text-align:center">${p.pos}</div>
      <div style="color:var(--muted)">${p.team}</div>
      <div>${p.age}</div>
      <div style="font-family:var(--font-head);font-weight:900;font-size:14px;color:${p.rating>=90?'var(--gold)':p.rating>=85?'var(--green)':'var(--text)'}">${p.rating}</div>
      <div><span class="status-badge ${p.status}">${p.status}</span></div>
      <div style="display:flex;gap:3px">${editDel()}</div>
    </div>`).join('');
  return head+rows;
}

function renderAllMatches(){
  const all=[...MATCHES_LIVE,...MATCHES_UPCOMING,...MATCHES_RESULTS];
  return all.map(m=>`
    <div style="padding:10px 14px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:80px 1fr auto 1fr 90px;align-items:center;gap:8px;cursor:pointer" onmouseover="this.style.background='rgba(255,255,255,.025)'" onmouseout="this.style.background=''">
      <div><div style="font-size:9px;color:var(--accent);font-weight:700">${m.status}</div></div>
      <div style="display:flex;align-items:center;gap:6px;justify-content:flex-end"><span style="font-size:10px;font-weight:600">${m.h}</span>${tb(m.hb,m.hc,20)}</div>
      <div style="font-family:var(--font-head);font-size:17px;font-weight:900;padding:0 8px;text-align:center;min-width:42px">${m.score}</div>
      <div style="display:flex;align-items:center;gap:6px">${tb(m.ab,m.ac,20)}<span style="font-size:10px;font-weight:600">${m.a}</span></div>
      <div style="display:flex;gap:4px;justify-content:flex-end"><button class="tb-btn secondary" style="font-size:8px;padding:2px 6px">Edit</button><button class="tb-btn primary" style="font-size:8px;padding:2px 6px">Stats</button></div>
    </div>`).join('');
}

function renderTransfers(){
  return TRANSFERS.map(t=>`
    <div class="transfer-item" style="display:grid;grid-template-columns:1.2fr 1fr 1fr 70px 80px 70px;align-items:center">
      <div style="font-weight:600">${t.pl}</div>
      <div style="color:var(--muted)">${t.from}</div>
      <div style="color:var(--muted)">${t.to}</div>
      <div style="font-family:var(--font-head);font-weight:800;font-size:12px;color:var(--green)">${t.fee}</div>
      <span class="t-status ${t.status}">${t.status}</span>
      <div style="display:flex;gap:3px"><button class="tb-btn secondary" style="font-size:8px;padding:2px 5px">Edit</button><button class="tb-btn danger" style="font-size:8px;padding:2px 5px">Override</button></div>
    </div>`).join('');
}

function renderAwards(){
  return AWARDS.map(a=>`
    <div class="award-item">
      <div class="award-icon" style="background:${a.bg}">${a.icon}</div>
      <div><div class="award-name">${a.name}</div><div class="award-winner">${a.winner}</div></div>
      <div class="award-val">${a.val}</div>
      <div style="display:flex;gap:4px;margin-left:10px">${editDel()}</div>
    </div>`).join('');
}

function renderUsers(){
  const head=`<div style="padding:7px 14px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:1.2fr 1.5fr 90px 70px 70px;gap:8px;font-size:9px;font-weight:700;letter-spacing:.7px;color:var(--muted);text-transform:uppercase"><div>Name</div><div>Email</div><div>Role</div><div>Last Seen</div><div>Actions</div></div>`;
  const rows=USERS.map(u=>`
    <div class="user-row" style="grid-template-columns:1.2fr 1.5fr 90px 70px 70px;gap:8px" onmouseover="this.style.background='rgba(255,255,255,.025)'" onmouseout="this.style.background=''">
      <div style="display:flex;align-items:center;gap:7px">
        <div class="online-dot ${u.online?'on':'off'}"></div>
        <div style="width:24px;height:24px;border-radius:50%;background:rgba(0,229,255,.15);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;flex-shrink:0">${u.name.split(' ').map(x=>x[0]).join('').slice(0,2)}</div>
        <span style="font-weight:600;font-size:10px">${u.name}</span>
      </div>
      <div style="color:var(--muted);font-size:10px">${u.email}</div>
      <div><span class="role-badge ${u.role}">${u.rl}</span></div>
      <div style="color:var(--muted);font-size:10px">${u.last}</div>
      <div style="display:flex;gap:3px">${editDel()}</div>
    </div>`).join('');
  return head+rows;
}

function renderNewsPage(){
  return NEWS.map(n=>`
    <div style="padding:9px 14px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:1fr 80px 80px 70px;align-items:center;gap:8px;cursor:pointer" onmouseover="this.style.background='rgba(255,255,255,.025)'" onmouseout="this.style.background=''">
      <div><div class="news-tag">${n.tag}</div><div style="font-size:10px;font-weight:600;color:var(--text)">${n.title}</div><div class="news-time">${n.time}</div></div>
      <span class="status-badge ${n.status}">${n.status}</span>
      <div style="color:var(--muted);font-size:9px">${n.time}</div>
      <div style="display:flex;gap:3px">${editDel()}</div>
    </div>`).join('');
}

/* ── INIT ── */
function init(){
  document.getElementById('match-center-body').innerHTML=renderMatchCenter('live');
  document.getElementById('top-scorers-body').innerHTML=renderTopScorers();
  document.getElementById('mini-table').innerHTML=renderMiniTable();
  document.getElementById('news-feed-body').innerHTML=renderNewsFeed();
  document.getElementById('full-table').innerHTML=renderFullTable();
  document.getElementById('teams-grid').innerHTML=renderTeams();
  document.getElementById('players-body').innerHTML=renderPlayers();
  document.getElementById('matches-body').innerHTML=renderAllMatches();
  document.getElementById('transfers-body').innerHTML=renderTransfers();
  document.getElementById('awards-body').innerHTML=renderAwards();
  document.getElementById('users-body').innerHTML=renderUsers();
  document.getElementById('news-body').innerHTML=renderNewsPage();
}

/* ── NAVIGATION ── */
const PAGE_META={
  dashboard:{title:'Dashboard',sub:'Season 2024/25 · Matchweek 32',action:'New Match'},
  teams:{title:'Teams',sub:'Manage all 20 clubs · Season 2024/25',action:'Add Team'},
  players:{title:'Players',sub:'487 registered players across 20 clubs',action:'Register Player'},
  matches:{title:'Matches',sub:'Schedule & results management',action:'Schedule Match'},
  transfers:{title:'Transfers',sub:'Transfer window management',action:'Override Transfer'},
  leaderboard:{title:'Leaderboard',sub:'Full season standings 2024/25',action:'Export Table'},
  awards:{title:'Awards',sub:'Season honours & nominations',action:'New Award'},
  users:{title:'Users',sub:'System access & role management',action:'Invite User'},
  news:{title:'News',sub:'League news & announcements',action:'Write Article'},
};

function nav(pageId){
  // hide all pages
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  const pg=document.getElementById('page-'+pageId);
  if(pg)pg.classList.add('active');
  // nav highlight
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  const ni=document.querySelector(`[data-page="${pageId}"]`);
  if(ni)ni.classList.add('active');
  // topbar
  const m=PAGE_META[pageId]||PAGE_META.dashboard;
  document.getElementById('page-title').textContent=m.title;
  document.getElementById('page-sub').textContent=m.sub;
  document.getElementById('main-action-label').textContent=m.action;
}

// bind nav items
document.querySelectorAll('.nav-item[data-page]').forEach(el=>{
  el.addEventListener('click',()=>nav(el.dataset.page));
});

// match center tabs
function mTab(el,tab){
  el.parentElement.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('match-center-body').innerHTML=renderMatchCenter(tab);
}

init();
</script>
</body>
</html>
