<?php
session_start();
if (!isset($_SESSION['void_auth']) || $_SESSION['void_auth'] !== true) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VENOM OPS BOARD — GEKKO AGENCY</title>

<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
<style>
  /* ═══════════════════════════════════════════════════
     VENOM OPS BOARD — CSS
     Cyberpunk Mission Control Aesthetic
     ═══════════════════════════════════════════════════ */

  *, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  :root {
    --venom-green: #00ff41;
    --venom-green-dim: #00cc33;
    --venom-green-dark: #003b00;
    --venom-green-glow: 0 0 10px #00ff4180, 0 0 40px #00ff4130;
    --venom-green-glow-intense: 0 0 10px #00ff41cc, 0 0 40px #00ff4166, 0 0 80px #00ff4133;
    --bg-primary: #0a0a0a;
    --bg-card: rgba(0, 15, 2, 0.75);
    --bg-card-hover: rgba(0, 25, 4, 0.85);
    --border-card: rgba(0, 255, 65, 0.15);
    --border-card-hover: rgba(0, 255, 65, 0.4);
    --text-primary: #00ff41;
    --text-secondary: #00cc33;
    --text-dim: #007a1e;
    --text-muted: #004d13;
    --status-building: #ffaa00;
    --status-active: #00ff41;
    --status-review: #00ccff;
    --status-progress: #ff6600;
    --status-operational: #00ff41;
    --font-mono: 'Share Tech Mono', 'Courier New', monospace;
  }

  html {
    font-size: 16px;
    scroll-behavior: smooth;
  }

  body {
    font-family: var(--font-mono);
    background: var(--bg-primary);
    color: var(--text-primary);
    min-height: 100vh;
    overflow-x: hidden;
    position: relative;
  }

  /* ── Matrix Rain Canvas ── */
  #matrix-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
  }

  /* ── CRT Scanline Overlay ── */
  .scanlines {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
    background: repeating-linear-gradient(
      0deg,
      transparent,
      transparent 2px,
      rgba(0, 0, 0, 0.08) 2px,
      rgba(0, 0, 0, 0.08) 4px
    );
  }

  /* ── Vignette ── */
  .vignette {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
    background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.6) 100%);
  }

  /* ── Main Content Layer ── */
  .dashboard {
    position: relative;
    z-index: 2;
    padding: 2rem 3rem 4rem;
    max-width: 1440px;
    margin: 0 auto;
  }

  /* ── Header ── */
  .header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 3rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(0, 255, 65, 0.1);
  }

  .header-left {
    flex: 1;
  }

  .header-right {
    text-align: right;
    flex-shrink: 0;
  }

  .title {
    font-size: 3rem;
    font-weight: 400;
    letter-spacing: 0.3em;
    color: var(--venom-green);
    text-shadow: var(--venom-green-glow-intense);
    line-height: 1;
    margin-bottom: 0.5rem;
    animation: titleGlitch 8s infinite;
    position: relative;
  }

  .title::before,
  .title::after {
    content: 'VENOM OPS BOARD';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
  }

  .title::before {
    color: #ff0040;
    z-index: -1;
    animation: glitchLeft 4s infinite linear alternate-reverse;
  }

  .title::after {
    color: #00ccff;
    z-index: -2;
    animation: glitchRight 3s infinite linear alternate-reverse;
  }

  @keyframes glitchLeft {
    0%, 90%, 100% { clip-path: inset(0 0 0 0); transform: translate(0); }
    92% { clip-path: inset(20% 0 60% 0); transform: translate(-3px, 0); }
    94% { clip-path: inset(50% 0 20% 0); transform: translate(3px, 0); }
    96% { clip-path: inset(10% 0 70% 0); transform: translate(-2px, 0); }
    98% { clip-path: inset(80% 0 0% 0); transform: translate(2px, 0); }
  }

  @keyframes glitchRight {
    0%, 88%, 100% { clip-path: inset(0 0 0 0); transform: translate(0); }
    90% { clip-path: inset(40% 0 30% 0); transform: translate(3px, 0); }
    93% { clip-path: inset(10% 0 60% 0); transform: translate(-3px, 0); }
    95% { clip-path: inset(60% 0 10% 0); transform: translate(2px, 0); }
    97% { clip-path: inset(30% 0 50% 0); transform: translate(-2px, 0); }
  }

  @keyframes titleGlitch {
    0%, 95%, 100% { opacity: 1; }
    96% { opacity: 0.8; }
    97% { opacity: 1; }
    98% { opacity: 0.6; }
    99% { opacity: 1; }
  }

  .subtitle {
    font-size: 0.95rem;
    letter-spacing: 0.5em;
    color: var(--text-dim);
    text-transform: uppercase;
    margin-top: 0.75rem;
  }

  .subtitle span {
    color: var(--text-secondary);
  }

  .clock {
    font-size: 2rem;
    color: var(--venom-green);
    text-shadow: var(--venom-green-glow);
    letter-spacing: 0.1em;
    line-height: 1;
    margin-bottom: 0.25rem;
  }

  .clock-date {
    font-size: 0.75rem;
    color: var(--text-dim);
    letter-spacing: 0.3em;
    text-transform: uppercase;
  }

  .version-tag {
    display: inline-block;
    margin-top: 0.75rem;
    font-size: 0.65rem;
    letter-spacing: 0.3em;
    color: var(--venom-green-dark);
    border: 1px solid var(--venom-green-dark);
    padding: 0.2rem 0.6rem;
    text-transform: uppercase;
  }

  /* ── Status Bar ── */
  .status-bar {
    display: flex;
    gap: 2rem;
    margin-bottom: 2.5rem;
    padding: 1rem 1.5rem;
    background: rgba(0, 255, 65, 0.02);
    border: 1px solid rgba(0, 255, 65, 0.06);
    border-radius: 2px;
    animation: fadeInUp 0.8s ease-out;
  }

  .stat {
    text-align: center;
  }

  .stat-value {
    font-size: 1.75rem;
    color: var(--venom-green);
    text-shadow: var(--venom-green-glow);
    line-height: 1;
  }

  .stat-label {
    font-size: 0.6rem;
    letter-spacing: 0.4em;
    color: var(--text-dim);
    text-transform: uppercase;
    margin-top: 0.25rem;
  }

  .stat-divider {
    width: 1px;
    background: rgba(0, 255, 65, 0.1);
    align-self: stretch;
  }

  /* ── Project Cards Grid ── */
  .projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 1.5rem;
  }

  /* ── Individual Card ── */
  .project-card {
    background: var(--bg-card);
    border: 1px solid var(--border-card);
    border-radius: 3px;
    padding: 1.75rem;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    opacity: 0;
    transform: translateY(30px);
    animation: cardEnter 0.7s ease-out forwards;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
  }

  .project-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--venom-green), transparent);
    opacity: 0.3;
    transition: opacity 0.4s;
  }

  .project-card::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 3px;
    box-shadow: inset 0 0 30px rgba(0, 255, 65, 0.03);
    pointer-events: none;
    transition: box-shadow 0.4s;
  }

  .project-card:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-card-hover);
    transform: translateY(-4px);
    box-shadow: 0 8px 40px rgba(0, 255, 65, 0.08), 0 0 60px rgba(0, 255, 65, 0.04);
  }

  .project-card:hover::before {
    opacity: 0.8;
  }

  .project-card:hover::after {
    box-shadow: inset 0 0 40px rgba(0, 255, 65, 0.06);
  }

  .project-card:nth-child(1) { animation-delay: 0.1s; }
  .project-card:nth-child(2) { animation-delay: 0.25s; }
  .project-card:nth-child(3) { animation-delay: 0.4s; }
  .project-card:nth-child(4) { animation-delay: 0.55s; }
  .project-card:nth-child(5) { animation-delay: 0.7s; }
  .project-card:nth-child(6) { animation-delay: 0.85s; }
  .project-card:nth-child(7) { animation-delay: 1.0s; }
  .project-card:nth-child(8) { animation-delay: 1.15s; }

  @keyframes cardEnter {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ── Card Header ── */
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
  }

  .card-title {
    font-size: 1.15rem;
    letter-spacing: 0.15em;
    color: var(--venom-green);
    text-shadow: 0 0 8px rgba(0, 255, 65, 0.3);
  }

  .card-url {
    display: block;
    font-size: 0.65rem;
    color: var(--text-dim);
    letter-spacing: 0.1em;
    margin-top: 0.3rem;
    text-decoration: none;
    transition: color 0.3s;
  }

  .card-url:hover {
    color: var(--text-secondary);
  }

  /* ── Status Badge ── */
  .status-badge {
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    padding: 0.25rem 0.6rem;
    border-radius: 2px;
    text-transform: uppercase;
    font-family: var(--font-mono);
    white-space: nowrap;
    position: relative;
    flex-shrink: 0;
  }

  .status-badge::before {
    content: '';
    display: inline-block;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    margin-right: 0.5em;
    vertical-align: middle;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
  }

  .status-building {
    color: var(--status-building);
    border: 1px solid rgba(255, 170, 0, 0.3);
    background: rgba(255, 170, 0, 0.06);
  }
  .status-building::before { background: var(--status-building); }

  .status-in-progress {
    color: var(--status-progress);
    border: 1px solid rgba(255, 102, 0, 0.3);
    background: rgba(255, 102, 0, 0.06);
  }
  .status-in-progress::before { background: var(--status-progress); }

  .status-active {
    color: var(--status-active);
    border: 1px solid rgba(0, 255, 65, 0.3);
    background: rgba(0, 255, 65, 0.06);
  }
  .status-active::before { background: var(--status-active); }

  .status-qc-review {
    color: var(--status-review);
    border: 1px solid rgba(0, 204, 255, 0.3);
    background: rgba(0, 204, 255, 0.06);
  }
  .status-qc-review::before { background: var(--status-review); }

  .status-operational {
    color: var(--status-operational);
    border: 1px solid rgba(0, 255, 65, 0.3);
    background: rgba(0, 255, 65, 0.06);
  }
  .status-operational::before { background: var(--status-operational); }

  /* ── Progress Bar ── */
  .progress-section {
    margin: 1.25rem 0;
  }

  .progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
  }

  .progress-label {
    font-size: 0.6rem;
    letter-spacing: 0.3em;
    color: var(--text-dim);
    text-transform: uppercase;
  }

  .progress-value {
    font-size: 0.85rem;
    color: var(--text-secondary);
    text-shadow: 0 0 6px rgba(0, 255, 65, 0.2);
  }

  .progress-track {
    width: 100%;
    height: 6px;
    background: rgba(0, 255, 65, 0.06);
    border-radius: 1px;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(0, 255, 65, 0.05);
  }

  .progress-fill {
    height: 100%;
    border-radius: 1px;
    position: relative;
    width: 0;
    transition: width 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  .progress-fill.green {
    background: linear-gradient(90deg, var(--venom-green-dark), var(--venom-green));
    box-shadow: 0 0 12px rgba(0, 255, 65, 0.4), 0 0 30px rgba(0, 255, 65, 0.15);
  }

  .progress-fill.orange {
    background: linear-gradient(90deg, #4d2200, #ff6600);
    box-shadow: 0 0 12px rgba(255, 102, 0, 0.4), 0 0 30px rgba(255, 102, 0, 0.15);
  }

  .progress-fill.yellow {
    background: linear-gradient(90deg, #4d3300, #ffaa00);
    box-shadow: 0 0 12px rgba(255, 170, 0, 0.4), 0 0 30px rgba(255, 170, 0, 0.15);
  }

  .progress-fill.cyan {
    background: linear-gradient(90deg, #003344, #00ccff);
    box-shadow: 0 0 12px rgba(0, 204, 255, 0.4), 0 0 30px rgba(0, 204, 255, 0.15);
  }

  .progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 20px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15));
    animation: shimmer 2s infinite;
  }

  @keyframes shimmer {
    0% { opacity: 0; }
    50% { opacity: 1; }
    100% { opacity: 0; }
  }

  /* ── Checklist ── */
  .checklist {
    margin-top: 1.25rem;
  }

  .checklist-group {
    margin-bottom: 0.75rem;
  }

  .checklist-label {
    font-size: 0.55rem;
    letter-spacing: 0.35em;
    color: var(--text-muted);
    text-transform: uppercase;
    margin-bottom: 0.35rem;
  }

  .checklist-item {
    font-size: 0.75rem;
    padding: 0.3rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    line-height: 1.3;
  }

  .checklist-item.done {
    color: var(--text-dim);
  }

  .checklist-item.done .check-icon {
    color: var(--venom-green);
    text-shadow: 0 0 4px rgba(0, 255, 65, 0.4);
  }

  .checklist-item.next .check-icon {
    color: var(--text-muted);
  }

  .check-icon {
    font-size: 0.7rem;
    flex-shrink: 0;
    width: 1em;
    text-align: center;
  }

  /* ── Footer ── */
  .footer {
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(0, 255, 65, 0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    animation: fadeInUp 1s ease-out 1s both;
  }

  .footer-left {
    font-size: 0.6rem;
    letter-spacing: 0.3em;
    color: var(--text-muted);
    text-transform: uppercase;
  }

  .footer-right {
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    color: var(--text-muted);
    display: flex;
    gap: 0.5rem;
    align-items: center;
  }

  .footer-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--venom-green);
    animation: pulse 2s infinite;
    box-shadow: 0 0 4px var(--venom-green);
  }

  /* ── Responsive ── */
  @media (max-width: 900px) {
    .dashboard {
      padding: 1.5rem 1.25rem 3rem;
    }
    .title {
      font-size: 1.75rem;
      letter-spacing: 0.2em;
    }
    .subtitle {
      font-size: 0.7rem;
      letter-spacing: 0.3em;
    }
    .clock {
      font-size: 1.25rem;
    }
    .projects-grid {
      grid-template-columns: 1fr;
    }
    .header {
      flex-direction: column;
      gap: 1rem;
    }
    .header-right {
      text-align: left;
    }
    .status-bar {
      flex-wrap: wrap;
      gap: 1rem;
    }
  }

  /* ── Selection ── */
  ::selection {
    background: rgba(0, 255, 65, 0.2);
    color: var(--venom-green);
  }

  /* ── Scrollbar ── */
  ::-webkit-scrollbar {
    width: 6px;
  }
  ::-webkit-scrollbar-track {
    background: var(--bg-primary);
  }
  ::-webkit-scrollbar-thumb {
    background: var(--venom-green-dark);
    border-radius: 3px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: var(--text-dim);
  }

  /* ── SKULL ROW (under title, left) ─────────────── */
  .header-skull-row{
    display:flex;align-items:center;gap:1rem;margin-top:1.25rem;
  }
  .skull-img{
    width:72px;height:72px;object-fit:contain;flex-shrink:0;
    filter:sepia(1) hue-rotate(88deg) saturate(9) brightness(.95);
    mix-blend-mode:lighten;
  }
  .skull-time{display:flex;flex-direction:column;gap:0.1rem;}

  /* ── 3D LOGO (header right) ─────────────────────── */
  #logo3d{
    display:block;
    width:300px;height:160px;
  }
  @media(max-width:900px){
    #logo3d{width:200px;height:110px;}
    .skull-img{width:52px;height:52px;}
  }
</style>
</head>
<body>

<!-- Matrix Rain Background -->
<canvas id="matrix-canvas"></canvas>

<!-- CRT Overlays -->
<div class="scanlines"></div>
<div class="vignette"></div>

<!-- Dashboard Content -->
<div class="dashboard">

  <!-- Header -->
  <header class="header">
    <div class="header-left">
      <h1 class="title">VENOM OPS BOARD</h1>
      <p class="subtitle"><span>GEKKO AGENCY</span> &mdash; MISSION CONTROL</p>
      <div class="header-skull-row">
        <img src="/skull.gif" class="skull-img" alt="skull">
        <div class="skull-time">
          <div class="clock" id="clock">00:00:00</div>
          <div class="clock-date" id="clock-date">--</div>
          <div class="version-tag" style="margin-top:0.5rem">VENOM v1.0</div>
        </div>
      </div>
    </div>
    <div class="header-right">
      <canvas id="logo3d"></canvas>
    </div>
  </header>

  <!-- Status Bar -->
  <div class="status-bar">
    <div class="stat">
      <div class="stat-value">5</div>
      <div class="stat-label">Active Projects</div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat">
      <div class="stat-value">63<span style="font-size:0.6em; opacity:0.5">%</span></div>
      <div class="stat-label">Avg Progress</div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat">
      <div class="stat-value">12</div>
      <div class="stat-label">Tasks Done</div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat">
      <div class="stat-value">9</div>
      <div class="stat-label">Pending</div>
    </div>
  </div>
  </div>

  <!-- Project Cards -->
  <div class="projects-grid">

    <!-- PAWTECH -->
    <div class="project-card">
      <div class="card-header">
        <div>
          <div class="card-title">PAWTECH</div>
          <a href="https://getpawtech.com" target="_blank" class="card-url">getpawtech.com</a>
        </div>
        <div class="status-badge status-building">BUILDING</div>
      </div>
      <div class="progress-section">
        <div class="progress-header">
          <span class="progress-label">Completion</span>
          <span class="progress-value">35%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill yellow" data-width="35"></div>
        </div>
      </div>
      <div class="checklist">
        <div class="checklist-group">
          <div class="checklist-label">// completed</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> N8N order routing</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> Shopify OAuth</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> API access configured</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// next up</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Store build-out</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> CJ API integration</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Klaviyo setup</div>
        </div>
      </div>
    </div>

    <!-- GEKKO WEBSITE -->
    <div class="project-card">
      <div class="card-header">
        <div>
          <div class="card-title">GEKKO WEBSITE</div>
          <a href="https://gekkoagency.com" target="_blank" class="card-url">gekkoagency.com</a>
        </div>
        <div class="status-badge status-in-progress">IN PROGRESS</div>
      </div>
      <div class="progress-section">
        <div class="progress-header">
          <span class="progress-label">Completion</span>
          <span class="progress-value">60%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill orange" data-width="60"></div>
        </div>
      </div>
      <div class="checklist">
        <div class="checklist-group">
          <div class="checklist-label">// completed</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> Built locally</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// next up</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Deploy to production</div>
        </div>
      </div>
    </div>

    <!-- N8N AUTOMATIONS -->
    <div class="project-card">
      <div class="card-header">
        <div>
          <div class="card-title">N8N AUTOMATIONS</div>
          <a href="https://rubenverhagen.app.n8n.cloud" target="_blank" class="card-url">rubenverhagen.app.n8n.cloud</a>
        </div>
        <div class="status-badge status-active">ACTIVE</div>
      </div>
      <div class="progress-section">
        <div class="progress-header">
          <span class="progress-label">Completion</span>
          <span class="progress-value">50%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill green" data-width="50"></div>
        </div>
      </div>
      <div class="checklist">
        <div class="checklist-group">
          <div class="checklist-label">// completed</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> Order routing live</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> Shopify connected</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// next up</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Revenue digest automation</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> ROAS watchdog</div>
        </div>
      </div>
    </div>

    <!-- MG AMERICAS TOUR -->
    <div class="project-card">
      <div class="card-header">
        <div>
          <div class="card-title">MG AMERICAS TOUR 2026</div>
          <span class="card-url" style="cursor:default">Martin Garrix</span>
        </div>
        <div class="status-badge status-qc-review">QC REVIEW</div>
      </div>
      <div class="progress-section">
        <div class="progress-header">
          <span class="progress-label">Completion</span>
          <span class="progress-value">80%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill cyan" data-width="80"></div>
        </div>
      </div>
      <div class="checklist">
        <div class="checklist-group">
          <div class="checklist-label">// completed</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> Models 2/5/6 approved</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// next up</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Correction emails to factory</div>
        </div>
      </div>
    </div>

    <!-- VENOM AI SETUP -->
    <div class="project-card">
      <div class="card-header">
        <div>
          <div class="card-title">VENOM AI SETUP</div>
          <span class="card-url" style="cursor:default">Internal Infrastructure</span>
        </div>
        <div class="status-badge status-operational">OPERATIONAL</div>
      </div>
      <div class="progress-section">
        <div class="progress-header">
          <span class="progress-label">Completion</span>
          <span class="progress-value">90%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill green" data-width="90"></div>
        </div>
      </div>
      <div class="checklist">
        <div class="checklist-group">
          <div class="checklist-label">// completed</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> Shopify API integrated</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> Memory system active</div>
          <div class="checklist-item done"><span class="check-icon">&#10003;</span> N8N connected</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// next up</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Mac Mini deploy (Mar 5)</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> iPhone integration</div>
        </div>
      </div>
    </div>

    <!-- JOSEPH KLIBANSKY x SUPERMAN -->
    <div class="project-card">
      <div class="card-header">
        <div>
          <div class="card-title">KLIBANSKY × SUPERMAN</div>
          <span class="card-url" style="cursor:default">Joseph Klibansky</span>
        </div>
        <div class="status-badge status-building">SAMPLING</div>
      </div>
      <div class="progress-section">
        <div class="progress-header">
          <span class="progress-label">Completion</span>
          <span class="progress-value">0%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill orange" data-width="0"></div>
        </div>
      </div>
      <div class="checklist">
        <div class="checklist-group">
          <div class="checklist-label">// references</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Shirt reference</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Artcore Hoodie — recreate</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> GUCCI model + fabric — recreate</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// decisions</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Choose fabrics</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Sizes decision</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Neck label + care label design</div>
        </div>
      </div>
    </div>

    <!-- MARCH 6 — EMPIRE LAUNCH -->
    <div class="project-card">
      <div class="card-header">
        <div>
          <div class="card-title">MARCH 6 — EMPIRE LAUNCH</div>
          <span class="card-url" style="cursor:default">Graaf Bentincklaan 10, Rhoon</span>
        </div>
        <div class="status-badge status-building">T-12 DAYS</div>
      </div>
      <div class="progress-section">
        <div class="progress-header">
          <span class="progress-label">Deployment</span>
          <span class="progress-value">0%</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill orange" data-width="0"></div>
        </div>
      </div>
      <div class="checklist">
        <div class="checklist-group">
          <div class="checklist-label">// hardware</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Mac Mini — buy March 5 (Rotterdam)</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Dedicated iPhone + SIM for VENOM</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> UniFi router + access points</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> UniFi cameras — gate, driveway, garden, garage</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> ReSpeaker mics — office, kitchen, living room</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Sonos speakers — multi-room</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> 10GB/s fiber — confirm install date</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// venom deployment</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> OpenClaw install on Mac Mini</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Migrate workspace from MacBook</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Set VENOM as permanent boot service</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> iPhone → join all GEKKO WhatsApp groups</div>
        </div>
        <div class="checklist-group">
          <div class="checklist-label">// agent org chart</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> GEKKO Agent — 129 webshops, factories, clients</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> PawTech Agent — store, ads, fulfilment</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Home Agent — cameras, gate, alarm, climate</div>
          <div class="checklist-item next"><span class="check-icon">&#9675;</span> Finance Agent — invoices, P&L, payments</div>
        </div>
      </div>
    </div>

  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-left">Ruben Verhagen &mdash; GEKKO Agency &copy; 2026</div>
    <div class="footer-right">
      <div class="footer-dot"></div>
      SYSTEM ONLINE
    </div>
  </footer>

</div>

<script>


  /* ═══════════════════════════════════════════════════
     MATRIX RAIN — Canvas Animation
     ═══════════════════════════════════════════════════ */
  (function initMatrixRain() {
    const canvas = document.getElementById('matrix-canvas');
    const ctx = canvas.getContext('2d');

    function resize() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    const chars = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ<>{}[]|/\\=+-_@#$%&';
    const charArray = chars.split('');
    const fontSize = 14;
    let columns = Math.floor(canvas.width / fontSize);
    let drops = new Array(columns).fill(1);

    // Randomize initial drop positions
    for (let i = 0; i < drops.length; i++) {
      drops[i] = Math.random() * -100;
    }

    window.addEventListener('resize', () => {
      columns = Math.floor(canvas.width / fontSize);
      const newDrops = new Array(columns).fill(1);
      for (let i = 0; i < newDrops.length; i++) {
        newDrops[i] = i < drops.length ? drops[i] : Math.random() * -100;
      }
      drops = newDrops;
    });

    function draw() {
      // Trailing fade
      ctx.fillStyle = 'rgba(10, 10, 10, 0.06)';
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      for (let i = 0; i < drops.length; i++) {
        const char = charArray[Math.floor(Math.random() * charArray.length)];
        const x = i * fontSize;
        const y = drops[i] * fontSize;

        // Lead character — bright white-green
        ctx.fillStyle = '#b0ffb0';
        ctx.font = fontSize + 'px monospace';
        ctx.fillText(char, x, y);

        // Trail — fading green
        ctx.fillStyle = 'rgba(0, 255, 65, 0.12)';
        ctx.fillText(charArray[Math.floor(Math.random() * charArray.length)], x, y - fontSize);

        // Reset drop after it goes off screen
        if (y > canvas.height && Math.random() > 0.975) {
          drops[i] = 0;
        }
        drops[i]++;
      }

      requestAnimationFrame(draw);
    }
    draw();
  })();

  /* ═══════════════════════════════════════════════════
     LIVE CLOCK
     ═══════════════════════════════════════════════════ */
  (function initClock() {
    const clockEl = document.getElementById('clock');
    const dateEl = document.getElementById('clock-date');

    function update() {
      const now = new Date();
      const h = String(now.getHours()).padStart(2, '0');
      const m = String(now.getMinutes()).padStart(2, '0');
      const s = String(now.getSeconds()).padStart(2, '0');
      clockEl.textContent = h + ':' + m + ':' + s;

      const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
      dateEl.textContent = now.toLocaleDateString('en-US', options).toUpperCase();
    }
    update();
    setInterval(update, 1000);
  })();

  /* ═══════════════════════════════════════════════════
     ANIMATED PROGRESS BARS
     ═══════════════════════════════════════════════════ */
  (function initProgressBars() {
    // Use IntersectionObserver to trigger progress animation on visibility
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const fill = entry.target;
          const width = fill.getAttribute('data-width');
          // Small delay for the staggered card animation to finish
          setTimeout(() => {
            fill.style.width = width + '%';
          }, 800);
          observer.unobserve(fill);
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.progress-fill').forEach(fill => {
      observer.observe(fill);
    });
  })();

</script>

<!-- THREE.JS — 3D Gore Logo in header -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://unpkg.com/three@0.128.0/examples/js/loaders/SVGLoader.js"></script>
<script>
(function(){
  try {
  if(typeof THREE === 'undefined'){ throw new Error('THREE not loaded'); }
  if(typeof THREE.SVGLoader === 'undefined'){ throw new Error('SVGLoader not loaded'); }
  var c = document.getElementById('logo3d');
  var W = c.offsetWidth || 300;
  var H = c.offsetHeight || 160;
  c.width = W; c.height = H;

  var rr = new THREE.WebGLRenderer({canvas:c, antialias:true, alpha:true});
  rr.setSize(W, H, false);
  rr.setPixelRatio(Math.min(devicePixelRatio, 2));
  rr.setClearColor(0, 0);

  var scene = new THREE.Scene();
  var cam = new THREE.PerspectiveCamera(36, W/H, 0.1, 200);
  cam.position.z = 8;

  scene.add(new THREE.AmbientLight(0x004400, 2));
  var kl = new THREE.DirectionalLight(0xffffff, 6); kl.position.set(5,8,6); scene.add(kl);
  var rl = new THREE.DirectionalLight(0x00ff55, 5); rl.position.set(-6,0,-5); scene.add(rl);
  var fl = new THREE.DirectionalLight(0x00cc44, 3); fl.position.set(0,-5,4); scene.add(fl);
  var sp = new THREE.PointLight(0x99ffbb, 12, 30); scene.add(sp);

  var mat = new THREE.MeshStandardMaterial({
    color:0x00dd40, emissive:0x002a10, emissiveIntensity:.5,
    metalness:.95, roughness:.07, side:THREE.DoubleSide
  });

  var svgStr = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1190.55 841.89">'
    + '<path fill="#00ff41" d="M294.4,684.66c34.77,0,57.77-25.8,77.96-51.6l11.78-15.14c1.12-6.17,1.68-15.71,1.68-28.61v-70.67l-31.97,40.38c-21.88-11.21-32.53-28.61-32.53-52.16,0-6.17-1.12-24.12-1.12-57.77v-111.05c26.36-10.65,49.35-25.8,70.11-50.48.56-.56,3.36-.56,8.41-.56,16.83,0,25.8,8.98,26.92,27.48,2.24,15.7,3.92,56.08,2.24,123.95-2.24,97.03-2.24,139.09-2.24,127.31,0,2.8-.56,7.86-1.12,16.27-1.68,17.38,5.61,26.36,19.62,26.36-3.36,1.68-22.99,19.06-58.88,52.72-25.8,24.12-52.72,35.89-80.2,35.89-20.76,0-35.89-6.17-46.56-19.07,12.91,4.49,24.68,6.74,35.89,6.74ZM360.02,375.63l.56,103.2c0,7.85,7.85,22.44,16.27,22.44,3.36,0,6.17-1.12,8.97-2.81l1.12-133.48c0-28.04-4.49-42.06-13.47-42.06-8.41,0-12.9,6.17-12.9,17.94-.56,2.8-1.12,14.02-.56,34.77Z"/>'
    + '<path fill="#00ff41" d="M442.46,345.34l80.76-58.33c4.49,1.68,7.3,5.05,8.98,9.54,2.24,6.73,5.05,21.87,5.61,45.99,0,5.61,2.24,11.77,7.29,17.95l-2.8,1.68-10.09,8.41c-23,19.06-40.39,43.18-52.16,71.79v31.97c0,29.17,7.29,41.5,21.88,37.58,8.41-2.24,15.7-8.41,22.99-19.63l5.62-12.34c6.73-14.58,3.92-35.33-4.49-60.01l16.27,26.92v.56c3.92,11.77,4.49,24.12,1.68,37.58-5.05,25.24-18.51,49.35-42.06,72.91l-4.49,4.49-11.78-5.61c-21.87-10.09-37.02-22.44-41.5-42.06-.56-6.17-1.68-16.83-1.68-34.77v-131.8c-.56-1.12-.56-2.24,0-2.8ZM480.03,418.82c7.29-7.29,14.58-19.62,21.88-37.58,0-20.19-1.68-34.21-4.49-42.06-2.81-3.92-5.62-5.61-8.98-5.61-2.8-1.12-4.49-1.12-6.17,0-1.68,0-2.24.56-2.24,1.12v84.12Z"/>'
    + '<path fill="#00ff41" d="M553.49,345.91v-60.57l-.56-61.13c1.68-1.68,11.21-17.95,28.61-47.67,11.77-21.87,27.48-32.53,47.67-32.53,3.92,0,9.53,1.12,16.83,2.8-26.36,7.29-42.63,21.32-50.48,42.07-2.8,7.29-3.36,18.51-3.36,32.53,0,10.65-.56,42.06,1.12,95.9l42.06-31.4c9.54,5.61,14.59,24.68,15.15,56.65,0,7.85,2.24,14.03,6.17,17.95l-.56,1.12c-11.77,8.41-24.68,19.62-35.89,33.65l44.87,123.94c28.61,80.76,63.94,130.12,102.08,152.55,10.65,6.17,25.8,11.22,50.47,19.63-3.92,1.68-7.29,1.68-10.09,2.24-31.41,2.24-63.94-12.34-99.83-50.48-28.6-31.4-49.35-62.81-66.17-97.02-10.66-21.88-25.8-54.41-48.24-101.52v13.46c-1.68,6.74-1.12,11.22-1.12,13.47v28.04c0,14.03,6.73,20.76,20.76,19.07h2.8q.56,0,.56.56l-31.41,39.82c-15.71-8.41-25.8-20.75-30.29-35.89-1.12-6.73-1.68-17.94-1.68-34.21v-20.75c0-24.12.56-34.77.56-32.53v-87.5c-1.12,0-1.68-.56-1.68-1.12l1.68-1.12ZM593.31,417.13c8.41-8.97,14.03-20.75,20.19-35.89,1.12-31.41-3.36-47.67-13.47-48.79-2.8,0-4.49.56-6.17,1.12h-.56v83.56Z"/>'
    + '<path fill="#00ff41" d="M667.34,345.91v-60.57l-.56-61.13c1.68-1.68,11.21-17.95,28.61-47.67,11.77-21.87,27.48-32.53,47.67-32.53,3.92,0,9.53,1.12,16.83,2.8-26.36,7.29-42.63,21.32-50.48,42.07-2.8,7.29-3.36,18.51-3.36,32.53,0,10.65-.56,42.06,1.12,95.9l42.06-31.4c9.54,5.61,14.59,24.68,15.15,56.65,0,7.85,2.24,14.03,6.17,17.95l-.56,1.12c-11.77,8.41-24.68,19.62-35.89,33.65l44.87,123.94c28.61,80.76,63.94,130.12,102.08,152.55,10.65,6.17,25.8,11.22,50.47,19.63-3.92,1.68-7.29,1.68-10.09,2.24-31.41,2.24-63.94-12.34-99.83-50.48-28.6-31.4-49.35-62.81-66.17-97.02-10.66-21.88-25.8-54.41-48.24-101.52v13.46c-1.68,6.74-1.12,11.22-1.12,13.47v28.04c0,14.03,6.73,20.76,20.76,19.07h2.8q.56,0,.56.56l-31.41,39.82c-15.71-8.41-25.8-20.75-30.29-35.89-1.12-6.73-1.68-17.94-1.68-34.21v-20.75c0-24.12.56-34.77.56-32.53v-87.5c-1.12,0-1.68-.56-1.68-1.12l1.68-1.12ZM707.16,417.13c8.41-8.97,14.03-20.75,20.19-35.89,1.12-31.41-3.36-47.67-13.47-48.79-2.8,0-4.49.56-6.17,1.12h-.56v83.56Z"/>'
    + '<path fill="#00ff41" d="M849.6,287.58c.56-.56,3.36-.56,7.29-.56,15.15,0,25.8,8.98,27.48,26.92,1.12,12.9,1.68,25.8,1.68,38.69l-2.24,152.56c.56,6.17,2.8,11.77,6.73,15.7-5.05,3.36-5.05,7.29-77.96,45.99-21.32-10.09-31.97-27.47-31.97-52.16l-1.12-154.23v-22.44c26.36-10.65,49.35-25.8,70.11-50.48ZM819.88,502.39c0,20.75,6.17,36.46,17.95,36.46,4.49,0,7.85,0,15.14-6.74-7.29-9.53-9.53-14.58-9.53-32.53,0-58.32,1.68-53.84,1.68-134.6,0-28.04-4.49-42.06-12.91-42.06-4.49,0-13.46,4.49-13.46,17.94l1.12,161.53Z"/>'
    + '</svg>';

  var loader = new THREE.SVGLoader();
  var data = loader.parse(svgStr);
  var group = new THREE.Group();

  data.paths.forEach(function(path){
    var shapes = THREE.SVGLoader.createShapes(path);
    shapes.forEach(function(shape){
      var geo = new THREE.ExtrudeGeometry(shape, {
        depth:20, bevelEnabled:true,
        bevelThickness:3, bevelSize:2,
        bevelSegments:2, curveSegments:4
      });
      group.add(new THREE.Mesh(geo, mat));
    });
  });

  var box = new THREE.Box3().setFromObject(group);
  var ctr = box.getCenter(new THREE.Vector3());
  var sz  = box.getSize(new THREE.Vector3());
  var s = 5.0 / Math.max(sz.x, sz.y);
  group.scale.set(s, -s, s);
  group.position.set(-ctr.x * s, ctr.y * s, -ctr.z * s);
  scene.add(group);

  var gt=0, ga=false;
  (function loop(){
    requestAnimationFrame(loop);
    var t = Date.now() * .001;
    group.rotation.y += .016;
    group.rotation.x = Math.sin(t * .3) * .07;
    sp.position.set(Math.sin(t*1.2)*6, Math.cos(t*.6)*2.5, Math.sin(t*.9)*5+5);
    gt++;
    if(gt > 200 && Math.random() > .97){ ga=true; gt=0; }
    if(ga){ mat.emissiveIntensity = Math.random()>.5?2:.1; mat.color.setHex(Math.random()>.5?0x00ffcc:0x00dd40); if(Math.random()>.6) ga=false; }
    else { mat.emissiveIntensity=.5; mat.color.setHex(0x00dd40); }
    rr.render(scene, cam);
  })();
  } catch(e) {
    var errc=document.getElementById('logo3d');
    var ctx=errc.getContext('2d');
    errc.width=300;errc.height=80;
    ctx.fillStyle='#00ff41';ctx.font='11px monospace';
    ctx.fillText('ERR: '+e.message,5,40);
  }
})();
</script>

</body>
</html>
