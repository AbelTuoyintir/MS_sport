<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>Apex League — Create Your Team</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800;900&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#f0c040;--gold2:#c8930a;--accent:#00e5ff;--red:#ff3b3b;--green:#22c55e;
  --surface:#0d1117;--surface2:#161b24;--surface3:#1e2530;--surface4:#242d38;
  --border:#2a3340;--border2:#3a4555;--text:#e8edf4;--muted:#7a8899;
  --font-head:'Barlow Condensed',sans-serif;--font-body:'Barlow',sans-serif;
}
html,body{min-height:100vh;background:var(--surface);color:var(--text);font-family:var(--font-body);font-size:11px}

/* ── BG PATTERN ── */
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 80% 60% at 50% -10%,rgba(0,229,255,.06),transparent),radial-gradient(ellipse 40% 40% at 90% 80%,rgba(240,192,64,.04),transparent);pointer-events:none;z-index:0}

/* ── PAGE WRAP ── */
.page-wrap{min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:24px 16px 48px;position:relative;z-index:1}

/* ── HEADER ── */
.header{display:flex;align-items:center;gap:10px;margin-bottom:28px;align-self:flex-start;width:100%;max-width:760px}
.logo-badge{width:36px;height:36px;background:linear-gradient(135deg,var(--gold),var(--gold2));border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-text{font-family:var(--font-head);font-size:16px;font-weight:900;letter-spacing:.4px;line-height:1.1}
.logo-sub{font-size:9px;color:var(--gold);letter-spacing:1.5px;font-weight:700;text-transform:uppercase}
.header-right{margin-left:auto;font-size:10px;color:var(--muted)}
.header-right a{color:var(--accent);font-weight:600;text-decoration:none}
.header-right a:hover{text-decoration:underline}

/* ── STEPPER ── */
.stepper-wrap{width:100%;max-width:760px;margin-bottom:24px}
.stepper{display:flex;align-items:center;gap:0}
.step{display:flex;align-items:center;flex:1;position:relative}
.step:last-child{flex:none}
.step-line{flex:1;height:1px;background:var(--border2);position:relative;margin:0 4px;transition:background .4s}
.step-line.done{background:var(--accent)}
.step-node{width:32px;height:32px;border-radius:50%;border:2px solid var(--border2);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:12px;font-weight:800;color:var(--muted);background:var(--surface2);flex-shrink:0;transition:all .3s;position:relative;z-index:1}
.step-node.active{border-color:var(--accent);color:var(--accent);background:rgba(0,229,255,.08);box-shadow:0 0 0 4px rgba(0,229,255,.1)}
.step-node.done{border-color:var(--green);background:var(--green);color:#000}
.step-node.done svg{width:14px;height:14px}
.step-meta{display:flex;flex-direction:column;align-items:center;position:absolute;top:38px;left:50%;transform:translateX(-50%);white-space:nowrap}
.step-label{font-size:9px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:var(--muted);transition:color .3s}
.step-label.active{color:var(--accent)}
.step-label.done{color:var(--green)}

/* ── FORM CARD ── */
.form-card{width:100%;max-width:760px;background:var(--surface2);border:1px solid var(--border);border-radius:10px;overflow:hidden}
.form-card-head{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px}
.form-card-icon{width:36px;height:36px;border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.fch-title{font-family:var(--font-head);font-size:17px;font-weight:800;letter-spacing:.3px;color:var(--text)}
.fch-sub{font-size:10px;color:var(--muted);margin-top:1px}
.form-body{padding:20px}

/* ── FORM ELEMENTS ── */
.form-grid{display:grid;gap:14px}
.form-grid.cols2{grid-template-columns:1fr 1fr}
.form-grid.cols3{grid-template-columns:1fr 1fr 1fr}
.field{display:flex;flex-direction:column;gap:5px}
.field.span2{grid-column:span 2}
.field.span3{grid-column:span 3}
label{font-size:10px;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase;display:flex;align-items:center;gap:5px}
label .req{color:var(--red);font-size:12px;line-height:1}
label .opt{font-size:9px;font-weight:600;color:var(--surface4);background:var(--surface3);padding:1px 6px;border-radius:3px;text-transform:none;letter-spacing:0}
input[type=text],input[type=email],input[type=tel],input[type=password],input[type=number],select{
  background:var(--surface3);border:1px solid var(--border);border-radius:6px;
  padding:9px 12px;font-size:11px;font-family:var(--font-body);color:var(--text);
  transition:border-color .2s,background .2s;outline:none;width:100%;
}
input::placeholder{color:var(--muted)}
input:focus,select:focus{border-color:var(--accent);background:rgba(0,229,255,.04)}
input.error,select.error{border-color:var(--red)}
select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%237a8899' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;padding-right:28px}
.field-hint{font-size:9px;color:var(--muted);margin-top:2px}
.field-error{font-size:9px;color:var(--red);margin-top:2px;display:none}
.field-error.show{display:block}

/* ── PASSWORD INPUT ── */
.pw-wrap{position:relative}
.pw-wrap input{padding-right:36px}
.pw-toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--muted);background:none;border:none;padding:0;display:flex}
.pw-toggle:hover{color:var(--text)}
.pw-toggle svg{width:14px;height:14px}

/* ── COLOR PICKER ── */
.color-field{display:flex;flex-direction:column;gap:5px}
.color-row{display:flex;gap:8px;align-items:center}
.color-swatch{width:36px;height:36px;border-radius:6px;border:2px solid var(--border);cursor:pointer;transition:border-color .2s;position:relative;overflow:hidden;flex-shrink:0}
.color-swatch:hover{border-color:var(--accent)}
.color-swatch input[type=color]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;border:none;padding:0}
.color-preview{display:flex;gap:6px;align-items:center;flex-wrap:wrap}
.cp-item{display:flex;align-items:center;gap:5px;background:var(--surface3);border:1px solid var(--border);border-radius:5px;padding:4px 8px;font-size:10px;font-weight:600}
.cp-dot{width:14px;height:14px;border-radius:3px;flex-shrink:0}

/* ── LOGO UPLOAD ── */
.upload-zone{border:2px dashed var(--border2);border-radius:8px;padding:20px;display:flex;flex-direction:column;align-items:center;gap:6px;cursor:pointer;transition:all .2s;position:relative;text-align:center}
.upload-zone:hover{border-color:var(--accent);background:rgba(0,229,255,.03)}
.upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer}
.upload-icon{width:36px;height:36px;border-radius:50%;background:var(--surface3);display:flex;align-items:center;justify-content:center;margin-bottom:2px}
.upload-icon svg{width:18px;height:18px;color:var(--muted)}
.upload-text{font-size:11px;font-weight:600;color:var(--text)}
.upload-sub{font-size:9px;color:var(--muted)}
.logo-preview{width:72px;height:72px;border-radius:50%;border:2px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden;background:var(--surface3);flex-shrink:0}
.logo-preview img{width:100%;height:100%;object-fit:cover}
.logo-placeholder{font-family:var(--font-head);font-size:22px;font-weight:900;color:var(--muted)}
.upload-row{display:flex;align-items:center;gap:12px}

/* ── OWNER SECTION ── */
.owner-cards{display:flex;flex-direction:column;gap:10px}
.owner-card{background:var(--surface3);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:border-color .2s}
.owner-card.filled{border-color:rgba(0,229,255,.25)}
.owner-card-head{padding:9px 14px;display:flex;align-items:center;gap:8px;border-bottom:1px solid var(--border);cursor:pointer;user-select:none}
.owner-card-head.collapsed{border-bottom:none}
.owner-num{width:22px;height:22px;border-radius:50%;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:11px;font-weight:800;color:var(--muted);flex-shrink:0}
.owner-num.primary{background:rgba(0,229,255,.1);border-color:rgba(0,229,255,.3);color:var(--accent)}
.owner-num.filled{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.3);color:var(--green)}
.owner-label{font-size:11px;font-weight:600;color:var(--text);flex:1}
.owner-sublabel{font-size:9px;color:var(--muted)}
.owner-primary-badge{font-size:8px;font-weight:800;letter-spacing:.5px;padding:2px 7px;border-radius:3px;background:rgba(240,192,64,.15);color:var(--gold);text-transform:uppercase;flex-shrink:0}
.owner-toggle{color:var(--muted);transition:transform .25s;display:flex}
.owner-toggle.open{transform:rotate(180deg)}
.owner-body{padding:14px;display:none}
.owner-body.open{display:block}
.owner-add-btn{border:2px dashed var(--border2);border-radius:8px;padding:12px;display:flex;align-items:center;justify-content:center;gap:8px;cursor:pointer;transition:all .2s;color:var(--muted);font-size:11px;font-weight:600;background:transparent;width:100%;font-family:var(--font-body)}
.owner-add-btn:hover{border-color:var(--accent);color:var(--accent);background:rgba(0,229,255,.03)}
.owner-add-btn svg{width:14px;height:14px}
.remove-owner{background:none;border:none;cursor:pointer;color:var(--muted);padding:3px;border-radius:3px;display:flex;transition:color .15s;margin-left:4px}
.remove-owner:hover{color:var(--red)}
.remove-owner svg{width:13px;height:13px}
.owner-count-info{font-size:9px;color:var(--muted);display:flex;align-items:center;gap:5px;margin-bottom:12px}
.owner-count-info strong{color:var(--accent)}

/* ── PAYMENT ── */
.payment-summary{background:var(--surface3);border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:14px}
.pay-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid rgba(42,51,64,.6);font-size:11px}
.pay-row:last-child{border-bottom:none;padding-top:10px;margin-top:4px}
.pay-label{color:var(--muted)}
.pay-val{font-weight:600;color:var(--text)}
.pay-total .pay-label{font-family:var(--font-head);font-size:13px;font-weight:700;color:var(--text)}
.pay-total .pay-val{font-family:var(--font-head);font-size:20px;font-weight:900;color:var(--gold)}
.pay-methods{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:14px}
.pay-method{background:var(--surface3);border:2px solid var(--border);border-radius:8px;padding:12px 10px;display:flex;flex-direction:column;align-items:center;gap:6px;cursor:pointer;transition:all .2s;font-size:10px;font-weight:600;color:var(--muted);text-align:center}
.pay-method:hover{border-color:var(--border2);color:var(--text)}
.pay-method.selected{border-color:var(--accent);background:rgba(0,229,255,.06);color:var(--accent)}
.pay-method-icon{font-size:22px}
.pay-details{background:var(--surface3);border:1px solid var(--border);border-radius:8px;padding:14px}
.pay-details-title{font-size:10px;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase;margin-bottom:10px}
.momo-number{background:var(--surface);border:1px solid var(--border);border-radius:6px;padding:10px 14px;font-size:13px;font-weight:700;color:var(--accent);letter-spacing:2px;text-align:center;margin-bottom:8px;font-family:var(--font-head)}
.momo-note{font-size:9px;color:var(--muted);text-align:center;line-height:1.5}
.momo-note strong{color:var(--gold)}
.ref-code{display:flex;align-items:center;gap:8px;background:var(--surface);border:1px solid var(--border);border-radius:6px;padding:8px 12px;margin-top:10px}
.ref-code-val{font-family:var(--font-head);font-size:14px;font-weight:800;color:var(--accent);letter-spacing:2px;flex:1}
.copy-btn{background:var(--surface3);border:1px solid var(--border);color:var(--muted);border-radius:4px;padding:4px 8px;font-size:9px;font-weight:700;cursor:pointer;transition:all .2s;font-family:var(--font-body)}
.copy-btn:hover{border-color:var(--accent);color:var(--accent)}
.copy-btn.copied{background:rgba(34,197,94,.1);border-color:var(--green);color:var(--green)}
.bank-field{margin-bottom:8px}

/* ── SECURITY NOTE ── */
.security-note{display:flex;align-items:flex-start;gap:8px;background:rgba(0,229,255,.05);border:1px solid rgba(0,229,255,.15);border-radius:6px;padding:10px 12px;margin-bottom:14px}
.security-note svg{width:14px;height:14px;color:var(--accent);flex-shrink:0;margin-top:1px}
.security-note p{font-size:9px;color:var(--muted);line-height:1.5}
.security-note p strong{color:var(--text)}

/* ── DIVIDER ── */
.divider{height:1px;background:var(--border);margin:16px 0}

/* ── SECTION TITLE ── */
.sec-title{font-family:var(--font-head);font-size:12px;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase;margin-bottom:12px;display:flex;align-items:center;gap:8px}
.sec-title::after{content:'';flex:1;height:1px;background:var(--border)}

/* ── BOTTOM NAV ── */
.form-footer{padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;background:var(--surface2)}
.btn{display:flex;align-items:center;gap:6px;padding:9px 18px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;border:none;transition:all .15s;letter-spacing:.3px;font-family:var(--font-body)}
.btn-secondary{background:var(--surface3);color:var(--text);border:1px solid var(--border)}
.btn-secondary:hover{background:var(--border)}
.btn-primary{background:var(--accent);color:#000}
.btn-primary:hover{background:#00cfee}
.btn-primary:disabled{background:var(--surface3);color:var(--muted);cursor:not-allowed;border:1px solid var(--border)}
.btn svg{width:13px;height:13px}
.step-progress{font-size:10px;color:var(--muted);font-weight:600}
.step-progress strong{color:var(--text)}

/* ── SUCCESS ── */
.success-wrap{display:none;flex-direction:column;align-items:center;padding:40px 20px;text-align:center;gap:14px}
.success-wrap.show{display:flex}
.success-icon{width:64px;height:64px;border-radius:50%;background:rgba(34,197,94,.12);border:2px solid var(--green);display:flex;align-items:center;justify-content:center}
.success-icon svg{width:30px;height:30px;color:var(--green)}
.success-title{font-family:var(--font-head);font-size:22px;font-weight:900;color:var(--text)}
.success-sub{font-size:11px;color:var(--muted);max-width:380px;line-height:1.6}
.success-code{background:var(--surface3);border:1px solid var(--border);border-radius:8px;padding:12px 24px;font-family:var(--font-head);font-size:20px;font-weight:900;color:var(--accent);letter-spacing:3px}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.layer{animation:fadeUp .3s ease both}

/* ── RESPONSIVE ── */
@media(max-width:580px){
  .form-grid.cols2,.form-grid.cols3{grid-template-columns:1fr}
  .field.span2,.field.span3{grid-column:span 1}
  .pay-methods{grid-template-columns:1fr}
}
</style>
</head>
<body>
<div class="page-wrap">

  <!-- HEADER -->
  <div class="header">
    <div class="logo-badge">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20M12 2c-3 4-3 14 0 20M12 2c3 4 3 14 0 20"/></svg>
    </div>
    <div>
      <div class="logo-text">APEX LEAGUE</div>
      <div class="logo-sub">Team Registration</div>
    </div>
    <div class="header-right">Already have a team? <a href="#">Sign in</a></div>
  </div>

  <div id="status-banner" style="display:none;width:100%;max-width:760px;margin-bottom:16px;padding:12px 14px;border-radius:8px;border:1px solid var(--border);font-size:11px;font-weight:600"></div>

  <!-- STEPPER -->
  <div class="stepper-wrap">
    <div class="stepper" id="stepper">
      <div class="step">
        <div class="step-node active" id="node-1">1</div>
        <div class="step-meta"><div class="step-label active" id="lbl-1">Team Info</div></div>
        <div class="step-line" id="line-1"></div>
      </div>
      <div class="step">
        <div class="step-node" id="node-2">2</div>
        <div class="step-meta"><div class="step-label" id="lbl-2">Ownership</div></div>
        <div class="step-line" id="line-2"></div>
      </div>
      <div class="step">
        <div class="step-node" id="node-3">3</div>
        <div class="step-meta"><div class="step-label" id="lbl-3">Payment</div></div>
      </div>
    </div>
  </div>

  <!-- FORM CARD -->
  <div class="form-card" id="form-card">

    <!-- ════ LAYER 1: TEAM INFO ════ -->
    <div id="layer-1" class="layer">
      <div class="form-card-head">
        <div class="form-card-icon" style="background:rgba(0,229,255,.1)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20"/></svg>
        </div>
        <div>
          <div class="fch-title">Team Information</div>
          <div class="fch-sub">Set up your club's identity and credentials</div>
        </div>
      </div>
      <div class="form-body">

        <!-- Logo + Name Row -->
        <div class="sec-title">Club Identity</div>
        <div class="upload-row" style="margin-bottom:16px">
          <div class="logo-preview" id="logo-preview-circle">
            <span class="logo-placeholder" id="logo-placeholder">?</span>
          </div>
          <div style="flex:1">
            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('logo-file').click()">
              <input type="file" id="logo-file" accept="image/*" onchange="handleLogo(this)" style="display:none"/>
              <div class="upload-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
              <div class="upload-text">Upload Team Logo</div>
              <div class="upload-sub">PNG, JPG, SVG · Max 2MB · <span style="color:var(--accent)">Optional</span></div>
            </div>
            <div id="logo-filename" style="display:none;margin-top:6px;font-size:9px;color:var(--green);display:flex;align-items:center;gap:5px"></div>
          </div>
        </div>

        <div class="form-grid cols2">
          <div class="field span2">
            <label>Team Name <span class="req">*</span></label>
            <input type="text" id="team_name" placeholder="e.g. Accra FC, Golden Stars" oninput="updateLogoPlaceholder()"/>
            <div class="field-error" id="err-team_name">Team name is required</div>
          </div>
          <div class="field">
            <label>Team Size <span class="req">*</span></label>
            <select id="team_size">
              <option value="">Select squad size</option>
              <option value="11">11 Players (Standard)</option>
              <option value="15">15 Players</option>
              <option value="18">18 Players</option>
              <option value="23">23 Players (Full Squad)</option>
              <option value="25">25 Players (Extended)</option>
              <option value="30">30 Players (Maximum)</option>
            </select>
            <div class="field-error" id="err-team_size">Please select a squad size</div>
          </div>
          <div class="field">
            <label>Division / League <span class="req">*</span></label>
            <select id="team_division">
              <option value="">Select division</option>
              <option value="premier">Premier Division</option>
              <option value="div1">Division 1</option>
              <option value="div2">Division 2</option>
              <option value="amateur">Amateur League</option>
            </select>
            <div class="field-error" id="err-team_division">Please select a division</div>
          </div>
        </div>

        <div class="divider"></div>

        <!-- Team Colors -->
        <div class="sec-title">Team Colors</div>
        <div class="form-grid cols3" style="margin-bottom:14px">
          <div class="color-field">
            <label>Primary Color <span class="req">*</span></label>
            <div class="color-row">
              <div class="color-swatch" id="swatch-primary">
                <input type="color" id="color_primary" value="#00e5ff" oninput="updateColor('primary')"/>
              </div>
              <input type="text" id="color_primary_hex" value="#00e5ff" placeholder="#000000" oninput="updateColorFromHex('primary')" style="font-family:monospace;font-size:11px"/>
            </div>
          </div>
          <div class="color-field">
            <label>Secondary Color <span class="req">*</span></label>
            <div class="color-row">
              <div class="color-swatch" id="swatch-secondary">
                <input type="color" id="color_secondary" value="#0d1117" oninput="updateColor('secondary')"/>
              </div>
              <input type="text" id="color_secondary_hex" value="#0d1117" placeholder="#000000" oninput="updateColorFromHex('secondary')" style="font-family:monospace;font-size:11px"/>
            </div>
          </div>
          <div class="color-field">
            <label>Accent Color <span class="opt">Optional</span></label>
            <div class="color-row">
              <div class="color-swatch" id="swatch-accent">
                <input type="color" id="color_accent" value="#f0c040" oninput="updateColor('accent')"/>
              </div>
              <input type="text" id="color_accent_hex" value="#f0c040" placeholder="#000000" oninput="updateColorFromHex('accent')" style="font-family:monospace;font-size:11px"/>
            </div>
          </div>
        </div>

        <!-- Color Preview -->
        <div class="field" style="margin-bottom:0">
          <label>Kit Preview</label>
          <div style="background:var(--surface3);border:1px solid var(--border);border-radius:8px;padding:14px;display:flex;align-items:center;gap:16px">
            <!-- Mini kit -->
            <div id="kit-preview" style="flex-shrink:0">
              <svg width="52" height="64" viewBox="0 0 52 64" fill="none" xmlns="http://www.w3.org/2000/svg" id="kit-svg">
                <path id="kit-body" d="M14 10 L4 20 L8 24 L8 56 L44 56 L44 24 L48 20 L38 10 Q32 14 26 14 Q20 14 14 10Z" fill="#00e5ff"/>
                <path id="kit-collar" d="M18 10 Q22 6 26 6 Q30 6 34 10 Q30 14 26 14 Q22 14 18 10Z" fill="#0d1117"/>
                <path id="kit-sleeve-l" d="M4 20 L8 24 L12 22 L14 10" fill="#f0c040"/>
                <path id="kit-sleeve-r" d="M48 20 L44 24 L40 22 L38 10" fill="#f0c040"/>
                <path d="M8 56 L44 56 L44 64 L8 64Z" id="kit-shorts" fill="#0d1117"/>
              </svg>
            </div>
            <div>
              <div class="color-preview" id="color-preview-swatches">
                <div class="cp-item"><div class="cp-dot" id="prev-primary" style="background:#00e5ff"></div>Primary</div>
                <div class="cp-item"><div class="cp-dot" id="prev-secondary" style="background:#0d1117;border:1px solid var(--border)"></div>Secondary</div>
                <div class="cp-item"><div class="cp-dot" id="prev-accent" style="background:#f0c040"></div>Accent</div>
              </div>
              <div style="margin-top:8px;font-size:10px;color:var(--muted);line-height:1.5">Your kit colors will be used across<br>match displays, player profiles &amp; the league table.</div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <!-- Password -->
        <div class="sec-title">Account Security</div>
        <div class="form-grid cols2">
          <div class="field">
            <label>Team Password <span class="req">*</span></label>
            <div class="pw-wrap">
              <input type="password" id="password" placeholder="Create a strong password"/>
              <button type="button" class="pw-toggle" onclick="togglePw('password','eye1')">
                <svg id="eye1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div class="field-hint">Min. 8 characters, include a number</div>
            <div class="field-error" id="err-password">Password must be at least 8 characters</div>
          </div>
          <div class="field">
            <label>Confirm Password <span class="req">*</span></label>
            <div class="pw-wrap">
              <input type="password" id="confirm_password" placeholder="Repeat your password"/>
              <button type="button" class="pw-toggle" onclick="togglePw('confirm_password','eye2')">
                <svg id="eye2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div class="field-error" id="err-confirm_password">Passwords do not match</div>
          </div>
        </div>

      </div>
      <div class="form-footer">
        <div class="step-progress">Step <strong>1</strong> of 3</div>
        <button class="btn btn-primary" onclick="goNext(1)">
          Continue to Ownership
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>

    <!-- ════ LAYER 2: OWNERSHIP ════ -->
    <div id="layer-2" class="layer" style="display:none">
      <div class="form-card-head">
        <div class="form-card-icon" style="background:rgba(240,192,64,.1)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div>
          <div class="fch-title">Team Ownership</div>
          <div class="fch-sub">Register the legal owners of this club — up to 5 people</div>
        </div>
      </div>
      <div class="form-body">

        <div class="owner-count-info">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          A minimum of <strong>1 owner</strong> is required. A maximum of <strong>5 owners</strong> can be registered per club. Each owner must provide valid contact information.
        </div>

        <div class="owner-cards" id="owner-cards">
          <!-- Owner 1 — always present, primary -->
        </div>

        <button class="owner-add-btn" id="add-owner-btn" onclick="addOwner()" style="margin-top:10px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
          Add Another Owner
          <span style="font-size:9px;color:var(--muted);font-weight:500" id="owner-slots-label">(4 slots remaining)</span>
        </button>

      </div>
      <div class="form-footer">
        <button class="btn btn-secondary" onclick="goBack(2)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          Back
        </button>
        <div class="step-progress">Step <strong>2</strong> of 3</div>
        <button class="btn btn-primary" onclick="goNext(2)">
          Continue to Payment
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>

    <!-- ════ LAYER 3: PAYMENT ════ -->
    <div id="layer-3" class="layer" style="display:none">
      <div class="form-card-head">
        <div class="form-card-icon" style="background:rgba(34,197,94,.1)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div>
          <div class="fch-title">Registration Payment</div>
          <div class="fch-sub">One-time team registration fee — Season 2024/25</div>
        </div>
      </div>
      <div class="form-body">

        <!-- Order Summary -->
        <div class="sec-title">Order Summary</div>
        <div class="payment-summary">
          <div class="pay-row"><span class="pay-label">Team Registration Fee</span><span class="pay-val">GH₵ 500.00</span></div>
          <div class="pay-row"><span class="pay-label">Processing Fee</span><span class="pay-val">GH₵ 2.00</span></div>
          <div class="pay-row"><span class="pay-label">Season</span><span class="pay-val">2024/25</span></div>
          <div class="pay-row pay-total"><span class="pay-label">Total Due</span><span class="pay-val">GH₵ 502.00</span></div>
        </div>

        <!-- Payment Method Hidden, defaulted to Card/Paystack -->
        <input type="hidden" id="selected_pay_method" value="card">

        <!-- Payment Details -->
        <div class="pay-details" id="pay-details">

          <!-- Paystack (Default) -->
          <div id="pd-card">
            <div class="pay-details-title">Secure Checkout via Paystack</div>
            <div class="security-note" style="margin-bottom:12px">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
              <p><strong>Universal Payment Gateway.</strong> You can pay via <strong>Mobile Money, Card, or Bank Transfer</strong> through Paystack's secure checkout.</p>
            </div>

            <div class="ref-code" style="margin-bottom:18px;flex-direction:column;align-items:flex-start;gap:4px">
              <div style="font-size:9px;color:var(--muted);text-transform:uppercase;font-weight:700;letter-spacing:1px">Registration Reference</div>
              <div class="ref-code-val" id="ref-code-val">...</div>
            </div>
            <div class="form-grid cols2">
              <div class="field span2">
                <label>Payment Email <span class="req">*</span></label>
                <input type="email" id="payer_email" placeholder="Defaults to the primary owner's email"/>
                <div class="field-hint">If left blank, the primary owner's email from Step 2 will be used for the Paystack checkout.</div>
              </div>
            </div>
          </div>
        </div>

        <div style="height:10px"></div>
        <div class="security-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <p><strong>Secure & Encrypted.</strong> Your payment details are protected. Registration is only confirmed after successful payment verification by the Apex League administration team.</p>
        </div>

      </div>
      <div class="form-footer">
        <button class="btn btn-secondary" onclick="goBack(3)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
          Back
        </button>
        <div class="step-progress">Step <strong>3</strong> of 3</div>
        <button class="btn btn-primary" id="pay-submit-btn" onclick="submitRegistration()" style="background:var(--green);min-width:160px">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Pay GH₵ 502.00
        </button>
      </div>
    </div>

    <!-- SUCCESS -->
    <div class="success-wrap" id="success-screen">
      <div class="success-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="success-title">Team Registered!</div>
      <div class="success-sub">Your club has been successfully submitted to the Apex League. Our admin team will review and approve your registration within 48 hours.</div>
      <div class="success-code" id="success-code">MPL-2025-????</div>
      <div style="font-size:9px;color:var(--muted)">Save this reference code for your records</div>
      <div style="display:flex;gap:10px;margin-top:8px">
        <button class="btn btn-secondary" onclick="location.reload()">Register Another Team</button>
        <button class="btn btn-primary">Go to Dashboard</button>
      </div>
    </div>

  </div><!-- end form-card -->
</div><!-- end page-wrap -->

<script>
const csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';
let currentStep=1;
let ownerCount=1;
const maxOwners=5;
let payMethod='card';
let teamReferenceCode='MPL-PENDING';

document.addEventListener('DOMContentLoaded',()=>{
  updateReferenceDisplays(teamReferenceCode);
  initColors();
  buildOwner(1,true);
  updateOwnerUI();
  handleGatewayReturn();
});

function updateReferenceDisplays(reference){
  teamReferenceCode=reference||'MPL-PENDING';
  const refVal = document.getElementById('ref-code-val');
  if(refVal) refVal.textContent=teamReferenceCode;
  const succCode = document.getElementById('success-code');
  if(succCode) succCode.textContent=teamReferenceCode;
}

function showNotice(message,type='info'){
  const banner=document.getElementById('status-banner');
  const styles={
    success:{bg:'rgba(34,197,94,.12)',border:'rgba(34,197,94,.4)',text:'#86efac'},
    error:{bg:'rgba(255,59,59,.12)',border:'rgba(255,59,59,.4)',text:'#fca5a5'},
    info:{bg:'rgba(0,229,255,.08)',border:'rgba(0,229,255,.25)',text:'var(--accent)'}
  };
  const style=styles[type]||styles.info;
  banner.style.display='block';
  banner.style.background=style.bg;
  banner.style.borderColor=style.border;
  banner.style.color=style.text;
  banner.textContent=message;
}

function clearNotice(){
  const banner=document.getElementById('status-banner');
  banner.style.display='none';
  banner.textContent='';
}

function handleGatewayReturn(){
  const params=new URLSearchParams(window.location.search);
  const payment=params.get('payment');
  const reference=params.get('reference_code');
  const message=params.get('message');

  if(reference){
    updateReferenceDisplays(reference);
  }

  if(payment==='success'){
    showSuccess(reference||teamReferenceCode,'Your Paystack payment has been confirmed and your team registration is now awaiting league review.');
    showNotice('Payment verified successfully via Paystack.','success');
  }else if(payment==='failed'){
    currentStep=3;
    setStep(3);
    showLayer(3);
    showNotice(message||'Payment could not be verified. Please try again.','error');
  }
}

function initColors(){
  ['primary','secondary','accent'].forEach(k=>{
    const sw=document.getElementById('swatch-'+k);
    const col=document.getElementById('color_'+k);
    if(sw&&col)sw.style.background=col.value;
  });
  updateKit();
}
function updateColor(key){
  const val=document.getElementById('color_'+key).value;
  document.getElementById('swatch-'+key).style.background=val;
  document.getElementById('color_'+key+'_hex').value=val;
  document.getElementById('prev-'+key).style.background=val;
  if(key==='secondary')document.getElementById('prev-'+key).style.border=val.toLowerCase()==='#0d1117'||val.toLowerCase()==='#000000'?'1px solid var(--border)':'none';
  updateKit();
}
function updateColorFromHex(key){
  const hex=document.getElementById('color_'+key+'_hex').value;
  if(/^#[0-9a-fA-F]{6}$/.test(hex)){
    document.getElementById('color_'+key).value=hex;
    updateColor(key);
  }
}
function updateKit(){
  const p=document.getElementById('color_primary').value;
  const s=document.getElementById('color_secondary').value;
  const a=document.getElementById('color_accent').value;
  document.getElementById('kit-body').setAttribute('fill',p);
  document.getElementById('kit-collar').setAttribute('fill',s);
  document.getElementById('kit-shorts').setAttribute('fill',s);
  document.getElementById('kit-sleeve-l').setAttribute('fill',a);
  document.getElementById('kit-sleeve-r').setAttribute('fill',a);
}

function updateLogoPlaceholder(){
  const v=document.getElementById('team_name').value.trim();
  const ph=document.getElementById('logo-placeholder');
  ph.textContent=v?v.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2):'?';
}
function handleLogo(input){
  if(!input.files||!input.files[0])return;
  const file=input.files[0];
  const reader=new FileReader();
  reader.onload=e=>{
    const circle=document.getElementById('logo-preview-circle');
    circle.innerHTML=`<img src="${e.target.result}" alt="logo"/>`;
    document.getElementById('logo-placeholder').textContent='';
    const fn=document.getElementById('logo-filename');
    fn.style.display='flex';
    fn.innerHTML=`<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> ${file.name}`;
  };
  reader.readAsDataURL(file);
}

function togglePw(id,eyeId){
  const inp=document.getElementById(id);
  const eye=document.getElementById(eyeId);
  if(inp.type==='password'){
    inp.type='text';
    eye.innerHTML='<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
  }else{
    inp.type='password';
    eye.innerHTML='<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }
}

function buildOwner(num,primary=false,collapsed=false){
  const card=document.createElement('div');
  card.className='owner-card'+(primary?' filled':'');
  card.id='owner-card-'+num;
  const headClass='owner-card-head'+(collapsed?' collapsed':'');
  card.innerHTML=`
    <div class="${headClass}" id="owner-head-${num}" onclick="toggleOwner(${num})">
      <div class="owner-num ${primary?'primary filled':''}" id="owner-num-${num}">${num}</div>
      <div style="flex:1">
        <div class="owner-label" id="owner-label-${num}">${primary?'Primary Owner':'Owner '+num}</div>
        <div class="owner-sublabel" id="owner-sub-${num}">${primary?'Required · Lead contact':'Click to expand and fill in details'}</div>
      </div>
      ${primary?'<span class="owner-primary-badge">Primary</span>':''}
      ${!primary?`<button class="remove-owner" onclick="removeOwner(event,${num})" title="Remove owner"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>`:''}
      <div class="owner-toggle ${collapsed?'':'open'}" id="owner-toggle-${num}">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>
    <div class="owner-body ${collapsed?'':'open'}" id="owner-body-${num}">
      <div class="form-grid cols3">
        <div class="field span2">
          <label>Full Name <span class="req">*</span></label>
          <input type="text" id="owner_name_${num}" placeholder="e.g. Kwame Mensah" oninput="updateOwnerLabel(${num})"/>
          <div class="field-error" id="err-owner_name_${num}">Full name is required</div>
        </div>
        <div class="field">
          <label>Ownership % <span class="req">*</span></label>
          <input type="number" id="owner_pct_${num}" placeholder="e.g. 100" min="1" max="100" ${primary?'value="100"':''}/>
          <div class="field-error" id="err-owner_pct_${num}">Enter a valid percentage</div>
        </div>
        <div class="field span2">
          <label>Email Address <span class="req">*</span></label>
          <input type="email" id="owner_email_${num}" placeholder="e.g. kwame@email.com"/>
          <div class="field-error" id="err-owner_email_${num}">Valid email is required</div>
        </div>
        <div class="field">
          <label>Phone Number <span class="req">*</span></label>
          <input type="tel" id="owner_phone_${num}" placeholder="e.g. 024 000 0000"/>
          <div class="field-error" id="err-owner_phone_${num}">Phone number is required</div>
        </div>
      </div>
    </div>`;
  document.getElementById('owner-cards').appendChild(card);
}

function toggleOwner(num){
  const body=document.getElementById('owner-body-'+num);
  const toggle=document.getElementById('owner-toggle-'+num);
  const head=document.getElementById('owner-head-'+num);
  const isOpen=body.classList.contains('open');
  body.classList.toggle('open',!isOpen);
  body.style.display=isOpen?'none':'block';
  toggle.classList.toggle('open',!isOpen);
  head.classList.toggle('collapsed',isOpen);
}

function updateOwnerLabel(num){
  const v=document.getElementById('owner_name_'+num).value.trim();
  const lbl=document.getElementById('owner-label-'+num);
  const sub=document.getElementById('owner-sub-'+num);
  const card=document.getElementById('owner-card-'+num);
  if(v){
    lbl.textContent=v;
    sub.textContent=num===1?'Primary Owner · Required':'Co-Owner';
    card.classList.add('filled');
    document.getElementById('owner-num-'+num).classList.add('filled');
  }else{
    lbl.textContent=num===1?'Primary Owner':'Owner '+num;
    sub.textContent=num===1?'Required · Lead contact':'Click to expand and fill in details';
  }
}

function addOwner(){
  if(ownerCount>=maxOwners)return;
  ownerCount++;
  buildOwner(ownerCount,false,false);
  updateOwnerUI();
}

function removeOwner(e,num){
  e.stopPropagation();
  document.getElementById('owner-card-'+num).remove();
  ownerCount--;
  updateOwnerUI();
}

function updateOwnerUI(){
  const btn=document.getElementById('add-owner-btn');
  const slots=document.getElementById('owner-slots-label');
  const remaining=maxOwners-ownerCount;
  slots.textContent=remaining>0?`(${remaining} slot${remaining>1?'s':''} remaining)`:'(Maximum reached)';
  btn.style.display=ownerCount>=maxOwners?'none':'flex';
}

// Removed selectPayMethod as we now default to Paystack

function copyRef(){
  navigator.clipboard.writeText(document.getElementById('ref-code-val').textContent).catch(()=>{});
  const btn=document.getElementById('copy-btn');
  btn.textContent='Copied!';
  btn.classList.add('copied');
  setTimeout(()=>{btn.textContent='Copy';btn.classList.remove('copied')},2000);
}

function showErr(id,show){
  const el=document.getElementById('err-'+id);
  if(!el)return;
  el.classList.toggle('show',show);
  const inp=document.getElementById(id);
  if(inp)inp.classList.toggle('error',show);
}
function clearErr(id){showErr(id,false);}

function clearAllErrors(){
  document.querySelectorAll('.field-error').forEach(el=>el.classList.remove('show'));
  document.querySelectorAll('input.error, select.error').forEach(el=>el.classList.remove('error'));
}

function validateLayer1(){
  let ok=true;
  const name=document.getElementById('team_name').value.trim();
  showErr('team_name',!name);if(!name)ok=false;

  const sz=document.getElementById('team_size').value;
  showErr('team_size',!sz);if(!sz)ok=false;

  const div=document.getElementById('team_division').value;
  showErr('team_division',!div);if(!div)ok=false;

  const pw=document.getElementById('password').value;
  showErr('password',pw.length<8);if(pw.length<8)ok=false;

  const cpw=document.getElementById('confirm_password').value;
  showErr('confirm_password',pw!==cpw);if(pw!==cpw)ok=false;

  const primary=document.getElementById('color_primary_hex').value.trim();
  const secondary=document.getElementById('color_secondary_hex').value.trim();
  if(!/^#[0-9a-fA-F]{6}$/.test(primary)||!/^#[0-9a-fA-F]{6}$/.test(secondary)){
    ok=false;
    showNotice('Primary and secondary team colors must be valid hex values.','error');
  }

  return ok;
}

function validateLayer2(){
  let ok=true;
  let totalOwnership=0;
  const cards=document.querySelectorAll('[id^="owner-card-"]');
  cards.forEach(card=>{
    const id=card.id.replace('owner-card-','');
    const n=document.getElementById('owner_name_'+id);
    const em=document.getElementById('owner_email_'+id);
    const ph=document.getElementById('owner_phone_'+id);
    const pct=document.getElementById('owner_pct_'+id);
    const pctValue=parseInt(pct.value,10);

    if(!n.value.trim()){showErr('owner_name_'+id,true);ok=false;}else showErr('owner_name_'+id,false);
    if(!em.value.trim()||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value.trim())){showErr('owner_email_'+id,true);ok=false;}else showErr('owner_email_'+id,false);
    if(!ph.value.trim()){showErr('owner_phone_'+id,true);ok=false;}else showErr('owner_phone_'+id,false);
    if(Number.isNaN(pctValue)||pctValue<1||pctValue>100){showErr('owner_pct_'+id,true);ok=false;}else{showErr('owner_pct_'+id,false);totalOwnership+=pctValue;}
  });

  if(totalOwnership!==100){
    ok=false;
    showNotice('Total ownership percentage must equal exactly 100%.','error');
  }

  return ok;
}

function validateLayer3(){
  const payerEmailInput=document.getElementById('payer_email');
  const payerEmail=payerEmailInput ? payerEmailInput.value.trim() : '';
  const primaryOwnerEmail=getPrimaryOwnerEmail();
  
  if(!payerEmail&&!primaryOwnerEmail){
    showNotice('Provide a payment email or enter the primary owner email in Step 2 for Paystack checkout.','error');
    return false;
  }
  return true;
}

function setStep(s){
  [1,2,3].forEach(i=>{
    const n=document.getElementById('node-'+i);
    const l=document.getElementById('lbl-'+i);
    const line=document.getElementById('line-'+i);
    if(i<s){
      n.className='step-node done';
      n.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';
      l.className='step-label done';
      if(line)line.classList.add('done');
    }else if(i===s){
      n.className='step-node active';
      n.innerHTML=i;
      l.className='step-label active';
      if(line)line.classList.remove('done');
    }else{
      n.className='step-node';
      n.innerHTML=i;
      l.className='step-label';
      if(line)line.classList.remove('done');
    }
  });
}

function showLayer(n){
  [1,2,3].forEach(i=>{
    const el=document.getElementById('layer-'+i);
    if(el)el.style.display=i===n?'block':'none';
  });
  document.getElementById('success-screen').classList.remove('show');
}

function showSuccess(referenceCode,message){
  if(referenceCode){
    updateReferenceDisplays(referenceCode);
  }

  [1,2,3].forEach(i=>{
    const el=document.getElementById('layer-'+i);
    if(el)el.style.display='none';
    const n=document.getElementById('node-'+i);
    if(n){
      n.className='step-node done';
      n.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';
    }
    const l=document.getElementById('lbl-'+i);
    if(l)l.className='step-label done';
    const line=document.getElementById('line-'+i);
    if(line)line.classList.add('done');
  });

  document.querySelector('.success-sub').textContent=message;
  document.getElementById('success-screen').classList.add('show');
  window.scrollTo({top:0,behavior:'smooth'});
}

async function parseResponse(response){
  const text=await response.text();
  try{
    return text?JSON.parse(text):{};
  }catch{
    return {message:text||'Unexpected server response.'};
  }
}

function firstErrorMessage(errors){
  const firstKey=Object.keys(errors||{})[0];
  const firstValue=firstKey?errors[firstKey]:null;
  return Array.isArray(firstValue)?firstValue[0]:firstValue;
}

function applyServerErrors(errors){
  if(errors.team_name)showErr('team_name',true);
  if(errors.team_size)showErr('team_size',true);
  if(errors.team_division)showErr('team_division',true);
  if(errors.password)showErr('password',true);
}

async function saveTeamInfo(){
  const formData=new FormData();
  formData.append('team_name',document.getElementById('team_name').value.trim());
  formData.append('team_size',document.getElementById('team_size').value);
  formData.append('team_division',document.getElementById('team_division').value);
  formData.append('primary_color',document.getElementById('color_primary_hex').value.trim());
  formData.append('secondary_color',document.getElementById('color_secondary_hex').value.trim());

  const accent=document.getElementById('color_accent_hex').value.trim();
  if(/^#[0-9a-fA-F]{6}$/.test(accent)){
    formData.append('accent_color',accent);
  }

  formData.append('password',document.getElementById('password').value);
  formData.append('password_confirmation',document.getElementById('confirm_password').value);

  const logoInput=document.getElementById('logo-file');
  if(logoInput.files&&logoInput.files[0]){
    formData.append('logo',logoInput.files[0]);
  }

  const response=await fetch('/api/team-info',{
    method:'POST',
    headers:{
      'X-CSRF-TOKEN':csrfToken,
      'Accept':'application/json'
    },
    credentials:'same-origin',
    body:formData
  });

  const data=await parseResponse(response);

  if(!response.ok){
    applyServerErrors(data.errors||{});
    throw new Error(firstErrorMessage(data.errors)||data.error||'Unable to save team information.');
  }

  return data;
}

function collectOwners(){
  return Array.from(document.querySelectorAll('[id^="owner-card-"]')).map(card=>{
    const id=card.id.replace('owner-card-','');
    return {
      full_name:document.getElementById('owner_name_'+id).value.trim(),
      ownership_percentage:Number(document.getElementById('owner_pct_'+id).value),
      email:document.getElementById('owner_email_'+id).value.trim(),
      phone:document.getElementById('owner_phone_'+id).value.trim()
    };
  });
}

async function saveOwners(){
  const response=await fetch('/api/owners',{
    method:'POST',
    headers:{
      'Content-Type':'application/json',
      'X-CSRF-TOKEN':csrfToken,
      'Accept':'application/json'
    },
    credentials:'same-origin',
    body:JSON.stringify({owners:collectOwners()})
  });

  const data=await parseResponse(response);

  if(!response.ok){
    throw new Error(firstErrorMessage(data.errors)||data.error||'Unable to save owner information.');
  }

  return data;
}

async function goNext(from){
  clearNotice();
  clearAllErrors();

  try{
    if(from===1){
      if(!validateLayer1())return;
      await saveTeamInfo();
    }

    if(from===2){
      if(!validateLayer2())return;
      await saveOwners();
    }

    currentStep=from+1;
    setStep(currentStep);
    showLayer(currentStep);
    window.scrollTo({top:0,behavior:'smooth'});
  }catch(error){
    showNotice(error.message||'Unable to continue to the next step.','error');
  }
}

function goBack(from){
  clearNotice();
  currentStep=from-1;
  setStep(currentStep);
  showLayer(currentStep);
  window.scrollTo({top:0,behavior:'smooth'});
}

function getPrimaryOwnerEmail(){
  return document.getElementById('owner_email_1')?.value.trim()||'';
}

function setSubmitButtonState(loading){
  const btn=document.getElementById('pay-submit-btn');
  btn.disabled=loading;
  btn.innerHTML=loading
    ? '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Processing...'
    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Pay GH₵ 502.00';
}

async function submitRegistration(){
  console.log('Submitting registration...');
  clearNotice();
  
  if(!validateLayer3()){
    console.log('Validation failed');
    return;
  }

  const payerEmailInput = document.getElementById('payer_email');
  const payerEmail = (payerEmailInput ? payerEmailInput.value.trim() : '') || getPrimaryOwnerEmail();
  
  const payload = {
    payment_method: 'card', // Force card/paystack
    payer_email: payerEmail
  };

  console.log('Payload:', payload);
  setSubmitButtonState(true);

  try{
    const response=await fetch('/api/process-payment',{
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':csrfToken,
        'Accept':'application/json'
      },
      credentials:'same-origin',
      body:JSON.stringify(payload)
    });

    const data=await parseResponse(response);
    console.log('Server response:', data);

    if(!response.ok){
      throw new Error(data.error||firstErrorMessage(data.errors)||'Payment processing failed.');
    }

    if(data.reference_code){
      updateReferenceDisplays(data.reference_code);
    }

    if(data.redirect_url){
      console.log('Redirecting to:', data.redirect_url);
      showNotice('Redirecting to Paystack checkout...','info');
      window.location.href=data.redirect_url;
      return;
    }

    showSuccess(
      data.reference_code||teamReferenceCode,
      data.payment_status==='pending'
        ? 'Your team registration has been submitted. Payment is pending confirmation by the Apex League administration team.'
        : 'Your team registration has been submitted successfully.'
    );

    showNotice(data.message||'Registration submitted successfully.','success');
  }catch(error){
    console.error('Submission error:', error);
    showNotice(error.message||'Payment processing failed.','error');
  }finally{
    setSubmitButtonState(false);
  }
}
</script>
</body>
</html>
