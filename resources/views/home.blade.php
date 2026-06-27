<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes"/>
<title>MP League — Official Home</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@300;400;500;600;700;800;900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          'gold': '#f0c040',
          'gold2': '#c8930a',
          'gold3': '#fff0a0',
          'accent': '#00e5ff',
          'accent2': '#007fa8',
          'custom-red': '#ff3b3b',
          'custom-green': '#22c55e',
          'bg-dark': '#06090e',
          'bg-dark2': '#0d1117',
          'bg-dark3': '#161b24',
          'bg-dark4': '#1e2530',
          'border-dark': '#1e2a38',
          'border-dark2': '#2a3848',
          'text-light': '#e8edf4',
          'muted': '#6b7a8d',
          'muted2': '#99aabb',
        },
        fontFamily: {
          'display': ['Bebas Neue', 'sans-serif'],
          'heading': ['Barlow Condensed', 'sans-serif'],
          'body': ['Barlow', 'sans-serif'],
        },
        animation: {
          'ticker-scroll': 'tickerScroll 35s linear infinite',
          'pulse-live': 'livePulse 1.2s infinite',
          'fade-up': 'fadeUp 0.6s ease forwards',
        },
        keyframes: {
          tickerScroll: {
            '0%': { transform: 'translateX(0)' },
            '100%': { transform: 'translateX(-50%)' },
          },
          livePulse: {
            '0%, 100%': { opacity: '1', transform: 'scale(1)' },
            '50%': { opacity: '0.4', transform: 'scale(0.8)' },
          },
          fadeUp: {
            'from': { opacity: '0', transform: 'translateY(20px)' },
            'to': { opacity: '1', transform: 'translateY(0)' },
          }
        },
        screens: {
          'xs': '375px',
          'sm': '640px',
          'md': '768px',
          'lg': '1024px',
          'xl': '1280px',
          '2xl': '1536px',
        }
      }
    }
  }
</script>
<style>
  /* Custom utilities that Tailwind doesn't cover */
  .bg-noise::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 999;
    opacity: 0.5;
  }
  .reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  .reveal.visible {
    opacity: 1;
    transform: translateY(0);
  }
  .hover-lift:hover {
    transform: translateY(-2px);
  }
  .bg-radial {
    background: radial-gradient(circle, var(--tw-gradient-stops));
  }
  .animation-pause:hover {
    animation-play-state: paused;
  }
  
  /* Mobile menu styles */
  .mobile-menu-open {
    overflow: hidden;
  }
  .mobile-nav {
    transform: translateX(100%);
    transition: transform 0.3s ease-in-out;
  }
  .mobile-nav.open {
    transform: translateX(0);
  }
  
  /* Table responsive */
  .table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  
  /* Match card grid */
  .matches-grid {
    display: grid;
    gap: 0.75rem;
    grid-template-columns: 1fr;
  }
  
  @media (min-width: 640px) {
    .matches-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (min-width: 1024px) {
    .matches-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  
  /* News grid */
  .news-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: 1fr;
  }
  
  @media (min-width: 768px) {
    .news-grid {
      grid-template-columns: 1.4fr 1fr 1fr;
    }
  }
  
  /* Awards grid */
  .awards-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  }
  
  @media (min-width: 640px) {
    .awards-grid {
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
  }
  
  /* Clubs scroll */
  .clubs-track {
    display: flex;
    gap: 1rem;
    animation: tickerScroll 30s linear infinite;
    width: fit-content;
  }
  
  .clubs-track:hover {
    animation-play-state: paused;
  }
  
  @keyframes tickerScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
  
  /* Hero stats responsive */
  .hero-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }
  
  @media (min-width: 640px) {
    .hero-stats {
      gap: 1.75rem;
    }
  }
  
  /* Countdown responsive */
  .countdown-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.125rem;
  }
  
  /* Fix for iOS tap highlight */
  button, a, [role="button"] {
    -webkit-tap-highlight-color: transparent;
  }
</style>
</head>
<body class="bg-bg-dark text-text-light font-body overflow-x-hidden">

<div class="bg-noise"></div>

<!-- MOBILE MENU OVERLAY -->
<div id="mobile-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[200] hidden transition-all duration-300"></div>

<!-- MOBILE NAVIGATION -->
<div id="mobile-nav" class="fixed top-0 right-0 w-[280px] h-full bg-bg-dark2 border-l border-border-dark z-[201] transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
  <div class="p-5">
    <div class="flex justify-end mb-6">
      <button id="close-mobile-menu" class="w-8 h-8 flex items-center justify-center rounded-lg bg-bg-dark3 border border-border-dark">
        <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="flex flex-col gap-2">
      <a href="#standings" class="mobile-nav-link font-heading text-base font-semibold tracking-[0.8px] uppercase text-muted px-3 py-2.5 rounded-md transition-all duration-150 no-underline hover:text-text-light hover:bg-bg-dark3">Standings</a>
      <a href="#matches" class="mobile-nav-link font-heading text-base font-semibold tracking-[0.8px] uppercase text-muted px-3 py-2.5 rounded-md transition-all duration-150 no-underline hover:text-text-light hover:bg-bg-dark3">Matches</a>
      <a href="#news" class="mobile-nav-link font-heading text-base font-semibold tracking-[0.8px] uppercase text-muted px-3 py-2.5 rounded-md transition-all duration-150 no-underline hover:text-text-light hover:bg-bg-dark3">News</a>
      <a href="#awards" class="mobile-nav-link font-heading text-base font-semibold tracking-[0.8px] uppercase text-muted px-3 py-2.5 rounded-md transition-all duration-150 no-underline hover:text-text-light hover:bg-bg-dark3">Awards</a>
      <a href="#clubs" class="mobile-nav-link font-heading text-base font-semibold tracking-[0.8px] uppercase text-muted px-3 py-2.5 rounded-md transition-all duration-150 no-underline hover:text-text-light hover:bg-bg-dark3">Clubs</a>
    </div>
    <div class="mt-6 pt-6 border-t border-border-dark">
      <button class="w-full font-heading text-sm font-bold tracking-[0.5px] px-4 py-2.5 rounded-md transition-all duration-150 bg-transparent text-muted border border-border-dark2 hover:text-text-light hover:border-text-light uppercase mb-3">Sign In</button>
      <a href="{{ route('team.register.form') }}" class="block w-full font-heading text-sm font-bold tracking-[0.5px] px-4 py-2.5 rounded-md transition-all duration-150 bg-gold text-black hover:bg-gold3 uppercase text-center">Register Team</a>
    </div>
  </div>
</div>

<!-- NAVIGATION -->
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-[100] h-[60px] flex items-center justify-between px-4 sm:px-6 md:px-10 gap-0 transition-all duration-300 border-b border-transparent">
  <a href="#" class="flex items-center gap-2 md:gap-2.5 no-underline flex-shrink-0">
    <div class="w-[30px] h-[30px] md:w-[34px] md:h-[34px] bg-gradient-to-br from-gold to-gold2 rounded-lg flex items-center justify-center flex-shrink-0">
      <svg class="w-[16px] h-[16px] md:w-[19px] md:h-[19px]" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20M12 2c-3 4-3 14 0 20M12 2c3 4 3 14 0 20"/></svg>
    </div>
    <div>
      <div class="font-heading text-xs md:text-base font-black tracking-[0.5px] text-text-light whitespace-nowrap">MEDICAL PREMIER LEAGUE</div>
      <div class="text-[7px] md:text-[8px] text-gold tracking-[1.5px] md:tracking-[2px] font-bold uppercase">CAPE COAST, UCC · Season 2024/25</div>
    </div>
  </a>
  
  <!-- Desktop Navigation -->
  <div class="hidden md:flex items-center gap-0 ml-9">
    <a href="#standings" class="nav-link font-heading text-xs font-semibold tracking-[0.8px] uppercase text-muted px-3 py-1.5 rounded-md transition-all duration-150 no-underline border-b-2 border-transparent hover:text-text-light">Standings</a>
    <a href="#matches" class="nav-link font-heading text-xs font-semibold tracking-[0.8px] uppercase text-muted px-3 py-1.5 rounded-md transition-all duration-150 no-underline border-b-2 border-transparent hover:text-text-light">Matches</a>
    <a href="#news" class="nav-link font-heading text-xs font-semibold tracking-[0.8px] uppercase text-muted px-3 py-1.5 rounded-md transition-all duration-150 no-underline border-b-2 border-transparent hover:text-text-light">News</a>
    <a href="#awards" class="nav-link font-heading text-xs font-semibold tracking-[0.8px] uppercase text-muted px-3 py-1.5 rounded-md transition-all duration-150 no-underline border-b-2 border-transparent hover:text-text-light">Awards</a>
    <a href="#clubs" class="nav-link font-heading text-xs font-semibold tracking-[0.8px] uppercase text-muted px-3 py-1.5 rounded-md transition-all duration-150 no-underline border-b-2 border-transparent hover:text-text-light">Clubs</a>
  </div>
  
  <div class="flex items-center gap-2">
    <div class="hidden sm:flex items-center gap-1.5 bg-custom-red/20 border border-custom-red/30 rounded-full px-2.5 py-1 text-[9px] font-extrabold tracking-[1px] text-custom-red uppercase">
      <div class="w-1.5 h-1.5 rounded-full bg-custom-red animate-pulse-live"></div>
      2 Live
    </div>
    <button class="hidden sm:block font-heading text-[11px] font-bold tracking-[0.5px] px-4 py-1.5 rounded-md transition-all duration-150 bg-transparent text-muted border border-border-dark2 hover:text-text-light hover:border-text-light uppercase">Sign In</button>
    <a href="{{ route('team.register.form') }}" class="hidden sm:block font-heading text-[11px] font-bold tracking-[0.5px] px-4 py-1.5 rounded-md transition-all duration-150 bg-gold text-black hover:bg-gold3 uppercase">Register Team</a>
    
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="block md:hidden w-8 h-8 flex items-center justify-center rounded-lg bg-bg-dark3 border border-border-dark">
      <svg class="w-4 h-4 text-text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
  </div>
</nav>

<!-- TICKER -->
<div class="h-8 bg-gradient-to-r from-gold2 to-gold flex items-center overflow-hidden mt-[60px]">
  <div class="bg-black text-gold font-heading text-[8px] sm:text-[10px] font-extrabold tracking-[1.5px] px-3 sm:px-4 h-full flex items-center flex-shrink-0 uppercase">⚽ Live</div>
  <div class="overflow-hidden flex-1">
    <div class="flex animate-ticker-scroll whitespace-nowrap hover:animation-pause">
      <span class="item px-3 sm:px-6 font-heading text-[9px] sm:text-[11px] font-bold text-black inline-flex items-center gap-1 flex-shrink-0">Man City <em class="not-italic opacity-50">3</em> — <em class="not-italic opacity-50">1</em> Arsenal <span class="text-black/50 text-[8px] sm:text-[10px]">67'</span></span><span class="text-black/30 text-sm sm:text-base">·</span>
      <span class="item px-3 sm:px-6 font-heading text-[9px] sm:text-[11px] font-bold text-black inline-flex items-center gap-1 flex-shrink-0">Spurs <em class="not-italic opacity-50">0</em> — <em class="not-italic opacity-50">0</em> Chelsea <span class="text-black/50 text-[8px] sm:text-[10px]">44'</span></span><span class="text-black/30 text-sm sm:text-base">·</span>
      <span class="item px-3 sm:px-6 font-heading text-[9px] sm:text-[11px] font-bold text-black inline-flex items-center gap-1 flex-shrink-0">⚽ GOAL — E. Haaland (Man City) 62'</span><span class="text-black/30 text-sm sm:text-base">·</span>
      <span class="item px-3 sm:px-6 font-heading text-[9px] sm:text-[11px] font-bold text-black inline-flex items-center gap-1 flex-shrink-0">🟨 B. White (Arsenal) 58'</span><span class="text-black/30 text-sm sm:text-base">·</span>
      <!-- Duplicate for seamless loop -->
      <span class="item px-3 sm:px-6 font-heading text-[9px] sm:text-[11px] font-bold text-black inline-flex items-center gap-1 flex-shrink-0">Man City <em class="not-italic opacity-50">3</em> — <em class="not-italic opacity-50">1</em> Arsenal <span class="text-black/50 text-[8px] sm:text-[10px]">67'</span></span><span class="text-black/30 text-sm sm:text-base">·</span>
      <span class="item px-3 sm:px-6 font-heading text-[9px] sm:text-[11px] font-bold text-black inline-flex items-center gap-1 flex-shrink-0">Spurs <em class="not-italic opacity-50">0</em> — <em class="not-italic opacity-50">0</em> Chelsea <span class="text-black/50 text-[8px] sm:text-[10px]">44'</span></span><span class="text-black/30 text-sm sm:text-base">·</span>
    </div>
  </div>
</div>

<!-- HERO SECTION -->
<section class="relative min-h-screen flex items-center overflow-hidden px-4 sm:px-6 md:px-10">
  <div class="absolute inset-0 z-0">
    <div class="absolute inset-0 z-[3] bg-gradient-to-b from-transparent via-black/20 to-bg-dark"></div>
    <div class="absolute inset-0 z-[2] opacity-10 hidden md:block">
      <svg class="w-full h-full" viewBox="0 0 1200 700" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
        <rect x="150" y="50" width="900" height="600" fill="none" stroke="white" stroke-width="2"/>
        <line x1="150" y1="350" x2="1050" y2="350" stroke="white" stroke-width="1.5"/>
        <circle cx="600" cy="350" r="90" fill="none" stroke="white" stroke-width="1.5"/>
      </svg>
    </div>
  </div>

  <div class="relative z-10 max-w-[700px] py-10">
    <div class="flex items-center gap-2 mb-3.5">
      <div class="w-4 md:w-6 h-px bg-gold"></div>
      <div class="font-heading text-[9px] md:text-[11px] font-bold tracking-[2px] md:tracking-[3px] text-gold uppercase">Season 2024 / 25 — Matchweek 32</div>
    </div>
    <h1 class="font-display text-[clamp(42px,10vw,100px)] leading-[0.95] text-text-light mb-1.5 tracking-[1px]">The Premier<span class="text-gold block">Football League</span></h1>
    <p class="text-[11px] md:text-[13px] text-muted2 leading-relaxed max-w-[440px] mb-6 md:mb-7 font-normal">Follow every match, track standings, discover top scorers, and stay updated with real-time news from CAPE COAST, UCC's most competitive football league.</p>
    <div class="flex flex-wrap gap-2 mb-8 md:mb-10">
      <a href="#standings" class="font-heading text-[11px] md:text-xs font-extrabold tracking-[0.8px] uppercase px-4 md:px-6 py-2.5 md:py-3 rounded-md cursor-pointer transition-all duration-200 bg-gold text-black flex items-center gap-1.5 no-underline hover:bg-gold3 hover:-translate-y-px">
        <svg class="w-3 h-3 md:w-3.5 md:h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
        View Standings
      </a>
      <a href="#matches" class="font-heading text-[11px] md:text-xs font-extrabold tracking-[0.8px] uppercase px-4 md:px-6 py-2.5 md:py-3 rounded-md cursor-pointer transition-all duration-200 bg-custom-red/20 text-custom-red border border-custom-red/30 flex items-center gap-1.5 no-underline hover:bg-custom-red/30">
        <svg class="w-3 h-3 md:w-3.5 md:h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M10 15l5-3-5-3v6z"/></svg>
        Live Matches
      </a>
      <a href="{{ route('team.register.form') }}" class="font-heading text-[11px] md:text-xs font-extrabold tracking-[0.8px] uppercase px-4 md:px-6 py-2.5 md:py-3 rounded-md cursor-pointer transition-all duration-200 bg-transparent text-text-light border border-border-dark2 flex items-center gap-1.5 no-underline hover:border-accent hover:text-accent">Register a Team</a>
    </div>
    <div class="hero-stats flex flex-wrap gap-4 sm:gap-7 pt-5 md:pt-7 border-t border-border-dark">
      <div><div class="font-display text-2xl sm:text-[32px] text-text-light tracking-[1px] leading-none">20</div><div class="text-[8px] sm:text-[9px] font-bold tracking-[1.5px] text-muted uppercase">Clubs</div></div>
      <div><div class="font-display text-2xl sm:text-[32px] text-text-light tracking-[1px] leading-none">380</div><div class="text-[8px] sm:text-[9px] font-bold tracking-[1.5px] text-muted uppercase">Matches</div></div>
      <div><div class="font-display text-2xl sm:text-[32px] text-text-light tracking-[1px] leading-none">812</div><div class="text-[8px] sm:text-[9px] font-bold tracking-[1.5px] text-muted uppercase">Goals</div></div>
      <div><div class="font-display text-2xl sm:text-[32px] text-text-light tracking-[1px] leading-none">487</div><div class="text-[8px] sm:text-[9px] font-bold tracking-[1.5px] text-muted uppercase">Players</div></div>
    </div>
  </div>

  <!-- Live Match Card - Hidden on mobile, visible on larger screens -->
  <div class="hidden xl:block absolute right-10 top-1/2 -translate-y-1/2 z-10 w-[300px]">
    <div class="bg-bg-dark3/85 backdrop-blur-md border border-border-dark2 rounded-xl overflow-hidden">
      <div class="px-3.5 py-2.5 border-b border-border-dark flex items-center justify-between">
        <div class="flex items-center gap-1.5 text-[8px] font-extrabold tracking-[1px] text-custom-red uppercase">
          <div class="w-1.5 h-1.5 rounded-full bg-custom-red animate-pulse-live"></div>
          Live Now
        </div>
        <div class="text-[9px] text-muted font-semibold tracking-[0.5px]">Matchweek 32</div>
      </div>
      <div class="px-3.5 py-4 border-b border-border-dark">
        <div class="flex items-center justify-between gap-2">
          <div class="flex flex-col items-center gap-1.5 flex-1">
            <div class="w-10 h-10 rounded-full flex items-center justify-center font-heading text-[10px] font-extrabold flex-shrink-0 bg-[#1a3cff] text-white">MC</div>
            <div class="font-heading text-xs font-bold text-text-light text-center">Man City</div>
          </div>
          <div class="flex flex-col items-center gap-1">
            <div class="font-display text-[38px] text-text-light tracking-[2px] leading-none">3 — 1</div>
            <div class="text-[9px] font-extrabold tracking-[0.8px] text-custom-red bg-custom-red/10 px-2 py-0.5 rounded">67'</div>
          </div>
          <div class="flex flex-col items-center gap-1.5 flex-1">
            <div class="w-10 h-10 rounded-full flex items-center justify-center font-heading text-[10px] font-extrabold flex-shrink-0 bg-[#ef0107] text-white">ARS</div>
            <div class="font-heading text-xs font-bold text-text-light text-center">Arsenal</div>
          </div>
        </div>
      </div>
      <div class="px-3.5 py-2.5">
        <div class="flex items-center gap-1.5 py-1 text-[10px] text-muted2"><span class="font-heading font-bold text-[10px] text-accent min-w-[24px]">62'</span><span class="text-xs">⚽</span><span>E. Haaland (MC)</span></div>
        <div class="flex items-center gap-1.5 py-1 text-[10px] text-muted2"><span class="font-heading font-bold text-[10px] text-accent min-w-[24px]">58'</span><span class="text-xs">🟨</span><span>B. White (ARS)</span></div>
      </div>
      <div class="px-3.5 py-2 border-t border-border-dark flex justify-center">
        <button onclick="document.getElementById('matches').scrollIntoView({behavior:'smooth'})" class="font-heading text-[10px] font-bold tracking-[0.5px] text-accent bg-none border-none cursor-pointer uppercase">+ 1 more live match →</button>
      </div>
    </div>
  </div>
</section>

<!-- NEXT MATCH BANNER -->
<div class="bg-bg-dark2 border-t border-b border-border-dark px-4 sm:px-6 md:px-10" id="next-match">
  <div class="flex flex-col sm:flex-row flex-wrap items-center justify-between gap-4 py-4 sm:py-6">
    <div class="text-center sm:text-left">
      <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] text-gold uppercase mb-1.5">⏱ Next Match</div>
      <div class="flex items-center justify-center sm:justify-start gap-3">
        <div class="flex items-center gap-2 font-heading text-sm sm:text-base font-bold text-text-light">
          <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center text-[8px] sm:text-[9px] font-black bg-[#d00027] text-white">LIV</div>
          <span class="text-xs sm:text-base">Liverpool</span>
        </div>
        <div class="font-display text-lg sm:text-[22px] text-muted">VS</div>
        <div class="flex items-center gap-2 font-heading text-sm sm:text-base font-bold text-text-light">
          <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center text-[8px] sm:text-[9px] font-black bg-[#003399] text-white">EVE</div>
          <span class="text-xs sm:text-base">Everton</span>
        </div>
      </div>
      <div class="text-[9px] sm:text-[10px] text-muted mt-1">🏟 Anfield · Premier Division</div>
    </div>
    <div class="text-center sm:text-left">
      <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] text-gold uppercase mb-1.5">Kicks Off In</div>
      <div class="flex gap-2 sm:gap-3 justify-center sm:justify-start">
        <div class="countdown-unit"><div class="font-display text-2xl sm:text-[28px] text-gold leading-none" id="cd-h">02</div><div class="text-[7px] sm:text-[8px] font-bold tracking-[1px] text-muted uppercase">Hours</div></div>
        <div class="font-display text-xl sm:text-2xl text-muted self-start pt-1">:</div>
        <div class="countdown-unit"><div class="font-display text-2xl sm:text-[28px] text-gold leading-none" id="cd-m">34</div><div class="text-[7px] sm:text-[8px] font-bold tracking-[1px] text-muted uppercase">Mins</div></div>
        <div class="font-display text-xl sm:text-2xl text-muted self-start pt-1">:</div>
        <div class="countdown-unit"><div class="font-display text-2xl sm:text-[28px] text-gold leading-none" id="cd-s">17</div><div class="text-[7px] sm:text-[8px] font-bold tracking-[1px] text-muted uppercase">Secs</div></div>
      </div>
    </div>
    <div class="text-center sm:text-left">
      <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] text-gold uppercase mb-1.5">Today · 18:30 GMT</div>
      <div class="font-display text-xl sm:text-[26px] text-text-light">18:30</div>
      <div class="text-[9px] sm:text-[10px] text-muted">Matchweek 32 · April 6, 2025</div>
    </div>
    <a href="#matches" class="font-heading text-[11px] sm:text-xs font-extrabold tracking-[0.8px] uppercase px-3 sm:px-3.5 py-2 rounded-md transition-all duration-200 bg-transparent text-text-light border border-border-dark2 flex items-center gap-1.5 no-underline hover:border-accent hover:text-accent">Match Preview →</a>
  </div>
</div>

<!-- STANDINGS SECTION -->
<section class="py-12 sm:py-[70px] px-4 sm:px-6 md:px-10 bg-bg-dark2 border-t border-b border-border-dark" id="standings">
  <div class="reveal flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
      <div class="flex items-center gap-2 mb-2">
        <div class="w-[14px] sm:w-[18px] h-[2px] bg-gold"></div>
        <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] sm:tracking-[3px] text-gold uppercase">Season 2024/25</div>
      </div>
      <div class="font-display text-[clamp(28px,6vw,52px)] text-text-light mb-1.5 leading-none">League Standings</div>
      <div class="text-[11px] sm:text-xs text-muted max-w-[500px] leading-relaxed">Current table after Matchweek 32. Updated after every match.</div>
    </div>
    <a href="#" class="font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] text-accent uppercase flex items-center gap-1 no-underline border-b border-transparent hover:border-accent">Full Table <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
  </div>
  
  <div class="reveal flex flex-col lg:flex-row gap-5 lg:gap-7 items-start">
    <!-- League Table -->
    <div class="w-full lg:flex-[1.4] bg-bg-dark3 border border-border-dark rounded-xl overflow-hidden">
      <div class="px-3 sm:px-4 py-2.5 sm:py-3 border-b border-border-dark flex items-center justify-between">
        <div class="flex items-center gap-2 font-heading text-xs sm:text-[13px] font-bold text-text-light">
          <svg class="w-3 h-3 sm:w-[13px] sm:h-[13px] text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 21h8M12 17v4M7 4H4a1 1 0 0 0-1 1v3a4 4 0 0 0 4 4h10a4 4 0 0 0 4-4V5a1 1 0 0 0-1-1h-3"/><rect x="7" y="2" width="10" height="11" rx="2"/></svg>
          Premier Division
        </div>
        <div class="text-[8px] sm:text-[9px] text-muted">MW 32 / 38</div>
      </div>
      <div class="table-wrapper overflow-x-auto">
        <table class="w-full border-collapse min-w-[600px]" id="public-table"></table>
      </div>
      <div class="px-3 sm:px-4 py-2 sm:py-2.5 border-t border-border-dark flex flex-wrap gap-2 sm:gap-4">
        <div class="flex items-center gap-1.5 text-[8px] sm:text-[9px] font-semibold text-muted"><div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-accent"></div><span class="hidden xs:inline">Champions League</span><span class="xs:hidden">CL</span></div>
        <div class="flex items-center gap-1.5 text-[8px] sm:text-[9px] font-semibold text-muted"><div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-custom-green"></div><span class="hidden xs:inline">Europa League</span><span class="xs:hidden">EL</span></div>
        <div class="flex items-center gap-1.5 text-[8px] sm:text-[9px] font-semibold text-muted"><div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-custom-red"></div><span class="hidden xs:inline">Relegation</span><span class="xs:hidden">REL</span></div>
      </div>
    </div>
    
    <!-- Top Scorers & Assists -->
    <div class="w-full lg:flex-1 flex flex-col gap-4">
      <div class="bg-bg-dark3 border border-border-dark rounded-xl overflow-hidden">
        <div class="px-3 sm:px-4 py-2.5 sm:py-3 border-b border-border-dark flex items-center justify-between">
          <div class="flex items-center gap-2 font-heading text-xs sm:text-[13px] font-bold text-text-light">
            <svg class="w-3 h-3 sm:w-[13px] sm:h-[13px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Top Scorers
          </div>
          <div class="text-[8px] sm:text-[9px] text-muted">Season Goals</div>
        </div>
        <div id="public-scorers"></div>
      </div>
      <div class="bg-bg-dark3 border border-border-dark rounded-xl overflow-hidden">
        <div class="px-3 sm:px-4 py-2.5 sm:py-3 border-b border-border-dark flex items-center justify-between">
          <div class="flex items-center gap-2 font-heading text-xs sm:text-[13px] font-bold text-text-light">
            <svg class="w-3 h-3 sm:w-[13px] sm:h-[13px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622C17.175 19.29 21 14.591 21 9c0-1.067-.143-2.1-.382-3.016z"/></svg>
            Top Assists
          </div>
        </div>
        <div id="public-assists"></div>
      </div>
    </div>
  </div>
</section>

<!-- MATCHES SECTION -->
<section class="py-12 sm:py-[70px] px-4 sm:px-6 md:px-10" id="matches">
  <div class="reveal flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
      <div class="flex items-center gap-2 mb-2">
        <div class="w-[14px] sm:w-[18px] h-[2px] bg-gold"></div>
        <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] sm:tracking-[3px] text-gold uppercase">Matchweek 32</div>
      </div>
      <div class="font-display text-[clamp(28px,6vw,52px)] text-text-light leading-none">Fixtures &amp; Results</div>
    </div>
    <a href="#" class="font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] text-accent uppercase flex items-center gap-1 no-underline border-b border-transparent hover:border-accent">All Fixtures <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
  </div>
  
  <div class="flex flex-wrap gap-0 border-b border-border-dark mb-6 overflow-x-auto">
    <div class="mtab font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] px-2 sm:px-4 py-2 sm:py-2.5 cursor-pointer text-muted border-b-2 border-transparent transition-all duration-150 uppercase whitespace-nowrap hover:text-muted2" onclick="switchMatchTab(this,'all')">All</div>
    <div class="mtab font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] px-2 sm:px-4 py-2 sm:py-2.5 cursor-pointer text-muted border-b-2 border-transparent transition-all duration-150 uppercase whitespace-nowrap hover:text-muted2" onclick="switchMatchTab(this,'live')">🔴 Live (2)</div>
    <div class="mtab font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] px-2 sm:px-4 py-2 sm:py-2.5 cursor-pointer text-muted border-b-2 border-transparent transition-all duration-150 uppercase whitespace-nowrap hover:text-muted2" onclick="switchMatchTab(this,'today')">Today</div>
    <div class="mtab font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] px-2 sm:px-4 py-2 sm:py-2.5 cursor-pointer text-muted border-b-2 border-transparent transition-all duration-150 uppercase whitespace-nowrap hover:text-muted2" onclick="switchMatchTab(this,'upcoming')">Upcoming</div>
    <div class="mtab font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] px-2 sm:px-4 py-2 sm:py-2.5 cursor-pointer text-muted border-b-2 border-transparent transition-all duration-150 uppercase whitespace-nowrap hover:text-muted2" onclick="switchMatchTab(this,'results')">Results</div>
  </div>
  
  <div class="reveal matches-grid" id="matches-grid"></div>
</section>

<!-- CLUBS SECTION -->
<div class="py-8 sm:py-10 border-t border-border-dark overflow-hidden" id="clubs">
  <div class="px-4 sm:px-6 md:px-10 mb-4 sm:mb-5">
    <div class="flex items-center gap-2">
      <div class="w-[14px] sm:w-[18px] h-[2px] bg-gold"></div>
      <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] sm:tracking-[3px] text-gold uppercase">Season 2024/25 · Clubs</div>
    </div>
  </div>
  <div class="overflow-hidden px-4 sm:px-6 md:px-10">
    <div class="clubs-track" id="clubs-track"></div>
  </div>
</div>

<!-- NEWS SECTION -->
<section class="py-12 sm:py-[70px] px-4 sm:px-6 md:px-10 bg-bg-dark2 border-t border-border-dark" id="news">
  <div class="reveal flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
      <div class="flex items-center gap-2 mb-2">
        <div class="w-[14px] sm:w-[18px] h-[2px] bg-gold"></div>
        <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] sm:tracking-[3px] text-gold uppercase">Latest Updates</div>
      </div>
      <div class="font-display text-[clamp(28px,6vw,52px)] text-text-light leading-none">News &amp; Analysis</div>
    </div>
    <a href="#" class="font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] text-accent uppercase flex items-center gap-1 no-underline border-b border-transparent hover:border-accent">All Articles <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
  </div>
  <div class="reveal news-grid" id="news-grid"></div>
</section>

<!-- AWARDS SECTION -->
<section class="py-12 sm:py-[60px] px-4 sm:px-6 md:px-10 bg-gradient-to-br from-amber-950/95 to-yellow-950/95 border-t border-b border-gold/15" id="awards">
  <div class="reveal flex flex-wrap items-end justify-between gap-3 mb-6 sm:mb-7">
    <div>
      <div class="flex items-center gap-2 mb-2">
        <div class="w-[14px] sm:w-[18px] h-[2px] bg-gold"></div>
        <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] sm:tracking-[3px] text-gold uppercase">Season Honours</div>
      </div>
      <div class="font-display text-[clamp(28px,6vw,52px)] text-gold leading-none">Awards 2024/25</div>
    </div>
    <a href="#" class="font-heading text-[10px] sm:text-[11px] font-bold tracking-[0.5px] text-gold uppercase flex items-center gap-1 no-underline border-b border-transparent hover:border-gold">View All <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
  </div>
  <div class="reveal awards-grid" id="awards-grid"></div>
</section>

<!-- CTA SECTION -->
<section class="py-16 sm:py-20 px-4 sm:px-6 md:px-10 bg-bg-dark text-center relative overflow-hidden">
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] sm:w-[600px] h-[400px] sm:h-[600px] bg-radial from-gold/6 to-transparent pointer-events-none"></div>
  <div class="reveal relative">
    <div class="font-display text-[clamp(32px,8vw,64px)] text-text-light mb-2.5">Ready to <span class="text-gold">Play?</span></div>
    <p class="text-[11px] sm:text-[13px] text-muted2 max-w-[480px] mx-auto mb-6 sm:mb-7 leading-relaxed px-4">Register your team in the MP League and compete against the best clubs in CAPE COAST, UCC. Season 2025/26 registrations now open.</p>
    <div class="flex flex-wrap gap-2.5 justify-center">
      <a href="{{ route('team.register.form') }}" class="font-heading text-[11px] sm:text-xs font-extrabold tracking-[0.8px] uppercase px-4 sm:px-6 py-2.5 sm:py-3 rounded-md cursor-pointer transition-all duration-200 bg-gold text-black flex items-center gap-1.5 no-underline hover:bg-gold3 hover:-translate-y-px">
        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Register Your Team
      </a>
      <a href="#" class="font-heading text-[11px] sm:text-xs font-extrabold tracking-[0.8px] uppercase px-4 sm:px-6 py-2.5 sm:py-3 rounded-md cursor-pointer transition-all duration-200 bg-transparent text-text-light border border-border-dark2 flex items-center gap-1.5 no-underline hover:border-accent hover:text-accent">Learn More</a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="bg-bg-dark2 border-t border-border-dark px-4 sm:px-6 md:px-10 py-8 sm:py-10 pb-5 sm:pb-6">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.5fr,1fr,1fr,1fr] gap-6 sm:gap-8 mb-8 sm:mb-9">
    <div>
      <div class="flex items-center gap-2 mb-3">
        <div class="w-7 h-7 bg-gradient-to-br from-gold to-gold2 rounded flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20M12 2c-3 4-3 14 0 20M12 2c3 4 3 14 0 20"/></svg>
        </div>
        <div>
          <div class="font-heading text-xs sm:text-sm font-black tracking-[0.4px]">MP LEAGUE</div>
          <div class="text-[8px] sm:text-[9px] text-gold tracking-[1.5px] font-bold uppercase">CAPE COAST, UCC</div>
        </div>
      </div>
      <p class="text-[10px] text-muted leading-relaxed max-w-[220px]">The official home of the MP Football League CAPE COAST, UCC.</p>
    </div>
    <div>
      <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[1.5px] text-muted uppercase mb-3">League</div>
      <a href="#standings" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Standings</a>
      <a href="#matches" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Fixtures</a>
      <a href="#clubs" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Clubs</a>
      <a href="#awards" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Awards</a>
    </div>
    <div>
      <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[1.5px] text-muted uppercase mb-3">Club Zone</div>
      <a href="{{ route('team.register.form') }}" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Register Team</a>
      <a href="#" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Club Directory</a>
      <a href="#" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Transfer News</a>
    </div>
    <div>
      <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[1.5px] text-muted uppercase mb-3">Information</div>
      <a href="#" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Rules &amp; Regulations</a>
      <a href="#" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Contact Us</a>
      <a href="#" class="block text-[10px] sm:text-[11px] text-muted2 py-1 cursor-pointer transition-colors duration-150 no-underline hover:text-text-light">Privacy Policy</a>
    </div>
  </div>
  <div class="border-t border-border-dark pt-4 sm:pt-[18px] flex flex-col sm:flex-row flex-wrap items-center justify-between gap-2.5 text-[8px] sm:text-[9px] text-muted text-center sm:text-left">
    <div>© 2025 MP League CAPE COAST, UCC. All rights reserved.</div>
    <div class="flex gap-2">
      <div class="w-8 h-8 sm:w-[30px] sm:h-[30px] bg-bg-dark4 border border-border-dark rounded flex items-center justify-center cursor-pointer transition-all duration-150 hover:border-border-dark2 hover:bg-bg-dark3"><svg class="w-3 h-3 sm:w-[13px] sm:h-[13px] text-muted2" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.56v14.91A4.54 4.54 0 0 1 19.46 24H4.54A4.54 4.54 0 0 1 0 19.47V4.54A4.54 4.54 0 0 1 4.54 0h14.92A4.54 4.54 0 0 1 24 4.56zM9.44 18.69V9.25H6.56v9.44zm-1.44-10.7a1.58 1.58 0 1 0-.03-3.15 1.58 1.58 0 0 0 .03 3.15zm11.25 10.7v-5.05c0-2.7-.58-4.77-3.73-4.77a3.27 3.27 0 0 0-2.95 1.62h-.04V9.25H9.69v9.44h2.88v-4.67c0-1.31.25-2.58 1.87-2.58 1.6 0 1.62 1.49 1.62 2.66v4.59z"/></svg></div>
      <div class="w-8 h-8 sm:w-[30px] sm:h-[30px] bg-bg-dark4 border border-border-dark rounded flex items-center justify-center cursor-pointer transition-all duration-150 hover:border-border-dark2 hover:bg-bg-dark3"><svg class="w-3 h-3 sm:w-[13px] sm:h-[13px] text-muted2" viewBox="0 0 24 24" fill="currentColor"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg></div>
      <div class="w-8 h-8 sm:w-[30px] sm:h-[30px] bg-bg-dark4 border border-border-dark rounded flex items-center justify-center cursor-pointer transition-all duration-150 hover:border-border-dark2 hover:bg-bg-dark3"><svg class="w-3 h-3 sm:w-[13px] sm:h-[13px] text-muted2" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg></div>
    </div>
  </div>
</footer>

<script>
// Data from server
const STANDINGS = @json($standings);
const ALL_MATCHES = @json($recent_games);
const NEWS_DATA = @json($news);
const SCORERS = @json($top_scorers);
const ASSISTS = []; // Not yet implemented in backend
const CLUBS_DATA = @json($all_teams);

// Legacy fallback data (to keep the structure working if DB is empty)
const STANDINGS_LEGACY = [
  {pos:1,n:'Man City',b:'MC',c:'#1a3cff',p:32,w:22,d:6,l:4,gf:68,ga:28,pts:72,form:['w','w','w','d','w'],z:'cl'},
  {pos:2,n:'Arsenal',b:'ARS',c:'#ef0107',p:32,w:20,d:6,l:6,gf:60,ga:32,pts:66,form:['l','w','w','w','d'],z:'cl'},
  {pos:3,n:'Liverpool',b:'LIV',c:'#d00027',p:32,w:19,d:7,l:6,gf:65,ga:34,pts:64,form:['w','w','d','l','w'],z:'cl'},
  {pos:4,n:'Aston Villa',b:'AVL',c:'#6c1d45',p:32,w:18,d:6,l:8,gf:58,ga:42,pts:60,form:['d','w','l','w','w'],z:'cl'},
  {pos:5,n:'Tottenham',b:'TOT',c:'#132257',p:32,w:15,d:8,l:9,gf:55,ga:48,pts:53,form:['w','d','w','l','d'],z:'eur'},
  {pos:6,n:'Chelsea',b:'CHE',c:'#034694',p:32,w:14,d:9,l:9,gf:52,ga:44,pts:51,form:['d','l','w','d','w'],z:'eur'},
  {pos:7,n:'Newcastle',b:'NEW',c:'#241f20',p:32,w:14,d:7,l:11,gf:48,ga:46,pts:49,form:['d','w','d','l','w'],z:''},
  {pos:8,n:'Man Utd',b:'MUN',c:'#e21a23',p:32,w:13,d:6,l:13,gf:42,ga:46,pts:45,form:['l','l','w','d','l'],z:''},
  {pos:9,n:'Brighton',b:'BHA',c:'#0057b8',p:32,w:12,d:9,l:11,gf:50,ga:46,pts:45,form:['w','d','w','l','d'],z:''},
  {pos:18,n:'Luton',b:'LUT',c:'#f78f1e',p:32,w:6,d:5,l:21,gf:28,ga:64,pts:23,form:['l','l','d','l','l'],z:'rel'},
  {pos:19,n:'Burnley',b:'BUR',c:'#6c1d45',p:32,w:5,d:4,l:23,gf:25,ga:72,pts:19,form:['l','l','l','d','l'],z:'rel'},
  {pos:20,n:'Sheffield Utd',b:'SHU',c:'#e21a23',p:32,w:3,d:5,l:24,gf:22,ga:80,pts:14,form:['l','l','l','l','d'],z:'rel'},
];

const SCORERS_LEGACY = [
  {nm:'E. Haaland',club:'Man City',nat:'🇳🇴',g:24,p:100,rc:'g1'},
  {nm:'O. Watkins',club:'Aston Villa',nat:'🏴󠁧󠁢󠁥󠁮󠁧󠁿',g:18,p:75,rc:'g2'},
  {nm:'A. Lacazette',club:'Arsenal',nat:'🇫🇷',g:15,p:63,rc:'g3'},
];

const NEWS_DATA_LEGACY = [
  {title:'Welcome to MP League',tag:'title',tagLabel:'General',time:'Now',icon:'🏆',bg:'linear-gradient(135deg,#1a2a0a,#0d1a07)',excerpt:'The new season is about to begin.'},
];

const AWARDS_DATA = [
  {icon:'🏆',name:'Golden Boot',winner:'E. Haaland',detail:'24 Goals'},
  {icon:'🧤',name:'Golden Glove',winner:'A. Raya',detail:'16 Clean Sheets'},
  {icon:'⭐',name:'Player of Season',winner:'Voting Open',detail:'Cast your vote'},
  {icon:'🌟',name:'Young Player',winner:'B. Saka',detail:'Age 22'},
  {icon:'👔',name:'Manager of Season',winner:'P. Guardiola',detail:'Man City'},
  {icon:'📈',name:'Most Assists',winner:'K. De Bruyne',detail:'16 Assists'},
];

function tb(b, c, sz = 22) {
  return `<div class="rounded-full flex items-center justify-center font-black flex-shrink-0" style="width:${sz}px;height:${sz}px;font-size:${Math.round(sz * 0.36)}px;background:${c};color:#fff">${b}</div>`;
}

function fd(arr) {
  return `<div class="flex gap-0.5">${arr.map(f => `<div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full ${f === 'w' ? 'bg-custom-green' : f === 'd' ? 'bg-gold' : 'bg-custom-red'}"></div>`).join('')}</div>`;
}

function renderTable() {
  const data = STANDINGS.length > 0 ? STANDINGS : STANDINGS_LEGACY;
  const head = `<thead><tr class="text-[8px] sm:text-[9px] font-bold tracking-[0.8px] text-muted uppercase"><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">#</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-left">Club</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">P</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">W</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">D</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">L</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">GD</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">Pts</th><th class="px-2 sm:px-3 py-2 border-b border-border-dark text-center">Form</th></tr></thead>`;
  const body = data.map((t, index) => {
    const pos = index + 1;
    const n = t.name || t.n;
    const b = t.b || n.substring(0, 2).toUpperCase();
    const c = t.c || '#f0c040';
    const p = t.played !== undefined ? t.played : t.p;
    const w = t.won !== undefined ? t.won : t.w;
    const d = t.drawn !== undefined ? t.drawn : t.d;
    const l = t.lost !== undefined ? t.lost : t.l;
    const gd = t.gd !== undefined ? t.gd : (t.gf - t.ga);
    const pts = t.points !== undefined ? t.points : t.pts;
    const form = t.form || [];
    const gds = (gd > 0 ? '+' : '') + gd;
    const pc = t.z === 'cl' ? 'text-accent' : t.z === 'eur' ? 'text-custom-green' : t.z === 'rel' ? 'text-custom-red' : 'text-muted';
    return `<tr class="hover:bg-white/5 transition-colors duration-150"><td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center"><span class="font-heading font-extrabold text-[11px] sm:text-[13px] ${pc}">${pos}</span></td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-left"><div class="flex items-center gap-1.5 sm:gap-2">${tb(b, c, 16)} <span class="text-[10px] sm:text-[11px]">${n}</span></div></td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center text-[10px] sm:text-[11px]">${p}</td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center text-[10px] sm:text-[11px]">${w}</td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center text-[10px] sm:text-[11px]">${d}</td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center text-[10px] sm:text-[11px]">${l}</td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center text-[10px] sm:text-[11px]" style="color:${gd >= 0 ? '#22c55e' : '#ff3b3b'}">${gds}</td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center"><span class="font-heading font-black text-xs sm:text-sm">${pts}</span></td>
      <td class="px-2 sm:px-3 py-2 border-b border-border-dark/70 text-center">${fd(form)}</td></tr>`;
  }).join('');
  document.getElementById('public-table').innerHTML = head + '<tbody>' + body + '</tbody>';
}

function renderStats(data, id) {
  const finalData = data.length > 0 ? data : SCORERS_LEGACY;
  document.getElementById(id).innerHTML = finalData.map((s, i) => {
    const name = s.name || s.nm;
    const club = s.team ? s.team.team_name : (s.club || 'N/A');
    const goals = s.goals !== undefined ? s.goals : s.g;
    const progress = s.pct || (goals * 4);
    return `
    <div class="px-3 sm:px-4 py-2 sm:py-2.5 border-b border-border-dark/70 flex items-center gap-2 hover:bg-white/5 transition-colors duration-150">
      <div class="font-display text-base sm:text-lg ${i===0 ? 'text-gold' : i===1 ? 'text-muted2' : i===2 ? 'text-amber-700' : 'text-muted'} w-4 sm:w-5 text-center">${i + 1}</div>
      <div class="w-6 h-6 sm:w-[30px] sm:h-[30px] rounded-full flex items-center justify-center text-xs sm:text-sm font-extrabold flex-shrink-0 bg-accent/10 text-accent">${s.nationality || s.nat || '🏳️'}</div>
      <div class="flex-1"><div class="text-[10px] sm:text-xs font-semibold text-text-light">${name}</div><div class="text-[8px] sm:text-[9px] text-muted">${club}</div></div>
      <div class="w-10 sm:w-14 h-0.5 bg-bg-dark4 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-accent to-accent2 rounded-full transition-all duration-700" style="width:${progress}%"></div></div>
      <div class="font-display text-base sm:text-xl text-accent min-w-[24px] sm:min-w-[28px] text-right">${goals}</div>
    </div>`}).join('');
}

let activeMatchTab = 'all';
function renderMatches(tab = 'all') {
  activeMatchTab = tab;
  let data = ALL_MATCHES;
  if (data.length === 0) {
      document.getElementById('matches-grid').innerHTML = '<div class="col-span-full p-12 text-center text-gray-500">No matches scheduled yet.</div>';
      return;
  }
  if (tab === 'live') data = ALL_MATCHES.filter(m => m.status === 'live');
  else if (tab === 'upcoming') data = ALL_MATCHES.filter(m => m.status === 'upcoming');
  else if (tab === 'results') data = ALL_MATCHES.filter(m => m.status === 'finished');
  
  document.getElementById('matches-grid').innerHTML = data.map(m => {
    const homeName = m.home_team ? m.home_team.team_name : (m.h || 'N/A');
    const awayName = m.away_team ? m.away_team.team_name : (m.a || 'N/A');
    const score = m.status === 'upcoming' ? 'vs' : `${m.home_score}–${m.away_score}`;
    const isUpcoming = m.status === 'upcoming';

    return `
    <div class="bg-bg-dark3 border border-border-dark rounded-xl overflow-hidden transition-all duration-200 hover:border-border-dark2">
      <div class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 border-b border-border-dark flex flex-wrap items-center justify-between gap-1">
        <div class="text-[8px] sm:text-[9px] font-bold text-muted tracking-[0.5px]">MW ${m.matchweek || 'N/A'}</div>
        <div class="text-[7px] sm:text-[8px] font-extrabold tracking-[0.8px] px-1.5 sm:px-2 py-0.5 rounded uppercase ${m.status === 'live' ? 'bg-custom-red/15 text-custom-red animate-pulse-live' : 'bg-accent/10 text-accent'}">${m.status}</div>
      </div>
      <div class="px-2.5 sm:px-3.5 py-3 sm:py-4">
        <div class="flex items-center justify-between gap-2">
          <div class="flex flex-col items-center gap-1 flex-1">
            ${tb(homeName.substring(0,2).toUpperCase(), m.home_team?.primary_color || '#ccc', 28)}
            <div class="font-heading text-[10px] sm:text-xs font-bold text-text-light text-center">${homeName}</div>
          </div>
          <div class="font-display text-xl sm:text-[30px] text-text-light px-1 sm:px-2 text-center min-w-[40px] sm:min-w-[52px]">${score}</div>
          <div class="flex flex-col items-center gap-1 flex-1">
            ${tb(awayName.substring(0,2).toUpperCase(), m.away_team?.primary_color || '#ccc', 28)}
            <div class="font-heading text-[10px] sm:text-xs font-bold text-text-light text-center">${awayName}</div>
          </div>
        </div>

        ${isUpcoming ? `
          <div class="mt-4 pt-3 border-t border-border-dark">
            <button onclick="togglePrediction(${m.id})" class="w-full text-[8px] text-accent font-bold uppercase py-1 border border-accent/20 rounded hover:bg-accent/5">Predict Score</button>
            <form id="pred-${m.id}" action="{{ route('predictions.store') }}" method="POST" class="hidden mt-3 space-y-2">
              @csrf
              <input type="hidden" name="game_id" value="${m.id}">
              <input type="text" name="user_name" placeholder="Your Name" required class="w-full bg-bg-dark border border-border-dark rounded px-2 py-1 text-[9px] text-white outline-none">
              <div class="flex items-center gap-2">
                <input type="number" name="home_score_prediction" placeholder="Home" required class="w-1/2 bg-bg-dark border border-border-dark rounded px-2 py-1 text-[9px] text-white outline-none">
                <span class="text-gray-600">-</span>
                <input type="number" name="away_score_prediction" placeholder="Away" required class="w-1/2 bg-bg-dark border border-border-dark rounded px-2 py-1 text-[9px] text-white outline-none">
              </div>
              <button type="submit" class="w-full bg-accent text-bg-dark font-bold text-[8px] py-1 rounded">Submit</button>
            </form>
          </div>
        ` : ''}
      </div>
    </div>`}).join('');
}

function togglePrediction(id) {
    const el = document.getElementById('pred-' + id);
    if(el) el.classList.toggle('hidden');
}

function switchMatchTab(el, tab) {
  document.querySelectorAll('.mtab').forEach(t => t.classList.remove('active', 'text-text-light', 'border-b-gold'));
  el.classList.add('active');
  el.classList.add('text-text-light', 'border-b-gold');
  renderMatches(tab);
}

function renderClubs() {
  const data = (CLUBS_DATA && CLUBS_DATA.length > 0) ? CLUBS_DATA : [];
  if (data.length === 0) return;
  const double = [...data, ...data];
  document.getElementById('clubs-track').innerHTML = double.map(c => {
    const name = c.team_name || c.n;
    const badge = name.substring(0, 2).toUpperCase();
    const color = c.primary_color || c.c || '#ccc';

    return `
    <div class="flex items-center gap-1.5 sm:gap-2 bg-bg-dark3 border border-border-dark rounded-lg px-2.5 sm:px-3.5 py-1.5 sm:py-2 cursor-pointer transition-all duration-150 flex-shrink-0 hover:border-border-dark2 hover:bg-bg-dark4">
      <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center text-[7px] sm:text-[8px] font-black flex-shrink-0" style="background:${color};color:#fff">${badge}</div>
      <div class="font-heading text-[10px] sm:text-[11px] font-bold text-text-light whitespace-nowrap">${name}</div>
    </div>`}).join('');
}

function renderNews() {
  const grid = document.getElementById('news-grid');
  const data = NEWS_DATA.length > 0 ? NEWS_DATA : NEWS_DATA_LEGACY;
  grid.innerHTML = data.map((n, i) => {
    const title = n.title;
    const tag = n.tag || 'title';
    const tagLabel = n.tagLabel || 'News';
    const time = n.time || new Date(n.created_at).toLocaleDateString();
    const excerpt = n.excerpt || (n.content ? n.content.substring(0, 100) + '...' : '');
    const commentsCount = n.comments ? n.comments.length : 0;

    return `
    <div class="bg-bg-dark3 border border-border-dark rounded-xl overflow-hidden transition-all duration-200 hover:border-border-dark2">
      <div class="${i === 0 ? 'h-48 sm:h-60' : 'h-36 sm:h-[180px]'} flex items-center justify-center text-5xl relative overflow-hidden" style="background:${n.bg || 'linear-gradient(135deg,#121820,#0a1016)'};">
        ${n.image_path ? `<img src="/storage/${n.image_path}" class="w-full h-full object-cover">` : (n.icon || '⚽')}
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent"></div>
        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 text-[7px] sm:text-[8px] font-extrabold tracking-[1px] px-1.5 sm:px-2 py-0.5 rounded uppercase ${tag === 'title' ? 'bg-gold/15 text-gold border border-gold/25' : tag === 'stats' ? 'bg-custom-green/15 text-custom-green border border-custom-green/25' : tag === 'injury' ? 'bg-custom-red/15 text-custom-red border border-custom-red/25' : tag === 'transfer' ? 'bg-accent/15 text-accent border border-accent/25' : 'bg-muted2/10 text-muted2 border border-muted2/20'}">${tagLabel}</div>
      </div>
      <div class="${i === 0 ? 'p-3 sm:p-4' : 'p-2.5 sm:p-3.5'}">
        <div class="${i === 0 ? 'text-xs sm:text-base' : 'text-[11px] sm:text-[13px]'} font-heading font-bold text-text-light leading-tight mb-1">${title}</div>
        <div class="text-[9px] sm:text-[10px] text-muted2 leading-relaxed mt-1.5 line-clamp-2">${excerpt}</div>
        <div class="flex items-center justify-between mt-3">
            <div class="flex items-center gap-1.5 text-[8px] text-muted">
              <span class="flex items-center gap-0.5"><svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>${time}</span>
            </div>
            @auth
            <div class="flex items-center gap-1 text-[8px] text-accent font-bold uppercase cursor-pointer" onclick="toggleComments(${n.id})">
                ${commentsCount} Comments
            </div>
            @endauth
        </div>
        <div id="comments-${n.id}" class="hidden mt-4 pt-3 border-t border-border-dark">
            <form action="{{ route('comments.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="hidden" name="article_id" value="${n.id}">
                <input type="hidden" name="user_name" value="{{ auth()->user()->name ?? 'Guest' }}">
                <input type="text" name="content" placeholder="Add a comment..." required class="flex-1 bg-bg-dark border border-border-dark rounded px-2 py-1 text-[9px] text-white outline-none">
                <button type="submit" class="bg-accent text-bg-dark font-bold text-[8px] px-2 py-1 rounded">Post</button>
            </form>
        </div>
      </div>
    </div>`}).join('');
}

function toggleComments(id) {
    const el = document.getElementById('comments-' + id);
    if(el) el.classList.toggle('hidden');
}

function renderAwards() {
  document.getElementById('awards-grid').innerHTML = AWARDS_DATA.map(a => {
    const isVoting = a.name === 'Player of Season';

    return `
    <div class="bg-gold/5 border border-gold/15 rounded-xl p-3 sm:p-4 flex flex-col items-center gap-1.5 sm:gap-2 text-center transition-all duration-200 hover:border-gold/30 hover:bg-gold/10">
      <div class="text-2xl sm:text-3xl">${a.icon}</div>
      <div class="font-heading text-[10px] sm:text-xs font-bold text-gold uppercase tracking-[0.5px]">${a.name}</div>
      <div class="text-[11px] sm:text-xs font-semibold text-text-light">${a.winner}</div>
      <div class="text-[8px] sm:text-[9px] text-muted mb-2">${a.detail}</div>

      ${isVoting && SCORERS.length > 0 ? `
        <form action="{{ route('vote.store') }}" method="POST" class="w-full mt-2">
            @csrf
            <select name="player_id" required class="w-full bg-bg-dark border border-gold/20 rounded px-2 py-1 text-[9px] text-white outline-none mb-2">
                <option value="">Choose Player</option>
                ${SCORERS.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
            </select>
            <button type="submit" class="w-full bg-gold text-bg-dark font-bold text-[8px] py-1 rounded">Vote Now</button>
        </form>
      ` : ''}
    </div>`}).join('');
}

function startCountdown() {
  const target = new Date();
  target.setHours(18, 30, 0, 0);
  if (target < new Date()) target.setDate(target.getDate() + 1);
  function tick() {
    const now = new Date();
    const diff = Math.max(0, target - now);
    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    document.getElementById('cd-h').textContent = String(h).padStart(2, '0');
    document.getElementById('cd-m').textContent = String(m).padStart(2, '0');
    document.getElementById('cd-s').textContent = String(s).padStart(2, '0');
  }
  tick();
  setInterval(tick, 1000);
}

// Mobile menu functionality
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileNav = document.getElementById('mobile-nav');
const mobileOverlay = document.getElementById('mobile-overlay');
const closeMobileMenu = document.getElementById('close-mobile-menu');

function openMobileMenu() {
  mobileNav.classList.add('open');
  mobileOverlay.classList.remove('hidden');
  document.body.classList.add('mobile-menu-open');
}

function closeMobileMenuHandler() {
  mobileNav.classList.remove('open');
  mobileOverlay.classList.add('hidden');
  document.body.classList.remove('mobile-menu-open');
}

mobileMenuBtn.addEventListener('click', openMobileMenu);
closeMobileMenu.addEventListener('click', closeMobileMenuHandler);
mobileOverlay.addEventListener('click', closeMobileMenuHandler);

// Close mobile menu when clicking on a link
document.querySelectorAll('.mobile-nav-link').forEach(link => {
  link.addEventListener('click', closeMobileMenuHandler);
});

// Nav scroll effect
window.addEventListener('scroll', () => {
  const nav = document.getElementById('main-nav');
  if (window.scrollY > 20) {
    nav.classList.add('bg-bg-dark/92', 'backdrop-blur-md', 'border-border-dark');
  } else {
    nav.classList.remove('bg-bg-dark/92', 'backdrop-blur-md', 'border-border-dark');
  }
});

// Reveal on scroll
function checkReveal() {
  document.querySelectorAll('.reveal').forEach(el => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight - 60) el.classList.add('visible');
  });
}
window.addEventListener('scroll', checkReveal);
window.addEventListener('load', checkReveal);

// Initialize active tab styling
setTimeout(() => {
  const firstTab = document.querySelectorAll('.mtab')[0];
  if (firstTab) {
    firstTab.classList.add('active', 'text-text-light', 'border-b-gold');
  }
}, 100);

// Init all
renderTable();
renderStats(SCORERS, 'public-scorers');
renderStats(ASSISTS, 'public-assists');
renderMatches('all');
renderClubs();
renderNews();
renderAwards();
startCountdown();
checkReveal();
</script>
</body>
</html>