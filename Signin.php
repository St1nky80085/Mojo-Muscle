<div id="signin-modal" class="modal-overlay">
    <span class="close-modal" id="close-modal">&times;</span>
    <div class="form-container">
        <input type="radio" name="form-nav" id="nav-login" checked class="hidden-radio" />
        <input type="radio" name="form-nav" id="nav-signup" class="hidden-radio" />
        <input type="radio" name="form-nav" id="nav-forgot" class="hidden-radio" />

        <!-- SIGN UP -->
        <div class="sign-up-container">
            <span class="form-title">Sign Up</span>
            <div id="selected-plan-display" style="display:none; text-align:center; margin-bottom:4px;">
                <span id="selected-plan-label" style="font-size:0.75rem; font-family:'Courier New',monospace; color:#92ff77; background:rgba(146,255,119,0.1); border:1px solid rgba(146,255,119,0.3); padding:3px 12px; border-radius:30px;"></span>
            </div>
            <label for="nav-login" class="switch-label">Already have an account? Login</label>
            <form class="sign-up-form" id="register-form">
                <input type="hidden" id="selected-plan" name="plan" value="Free" />

                <label for="username-sign-up">Username</label>
                <input placeholder="MojoMinion01" id="username-sign-up" name="username" type="text" required />

                <label for="email-sign-up">Email</label>
                <input placeholder="minion@mojojojo.com" id="email-sign-up" name="email" type="email" required />

                <label for="pwd-sign-up">Password</label>
                <div style="position:relative;">
                    <input placeholder="Min 8 chars, 1 upper, 1 number" id="pwd-sign-up" name="password" type="password" required style="padding-right:30px;" oninput="checkStrength(this.value)" />
                    <span class="eye-toggle" onclick="togglePw('pwd-sign-up',this)">👁</span>
                </div>
                <!-- PASSWORD STRENGTH BAR -->
                <div id="strength-wrap" style="margin-top:4px;">
                    <div id="strength-bar" style="height:3px; border-radius:2px; width:0%; transition:width 0.3s, background 0.3s; background:#ff4d4d;"></div>
                    <span id="strength-label" style="font-size:0.6rem; font-family:'Courier New',monospace; color:#666; letter-spacing:1px;"></span>
                </div>

                <label for="confirm-pwd-sign-up">Confirm Password</label>
                <div style="position:relative;">
                    <input placeholder="••••••••" id="confirm-pwd-sign-up" name="confirm_password" type="password" required style="padding-right:30px;" />
                    <span class="eye-toggle" onclick="togglePw('confirm-pwd-sign-up',this)">👁</span>
                </div>

                <button type="submit" id="register-btn">BECOME A MINION</button>
            </form>
        </div>

        <!-- LOGIN -->
        <div class="log-in-container">
            <span class="form-title">Login</span>
            <label for="nav-signup" class="switch-label">Don't have an account? Sign Up</label>
            <form class="log-in-form" id="login-form">
                <label for="email-login">Email</label>
                <input placeholder="minion@mojojojo.com" id="email-login" name="email" type="email" required />

                <label for="pwd-login">Password</label>
                <div style="position:relative;">
                    <input placeholder="••••••••" id="pwd-login" name="password" type="password" required style="padding-right:30px;" />
                    <span class="eye-toggle" onclick="togglePw('pwd-login',this)">👁</span>
                </div>

                <button type="submit" id="login-btn">ENTER LAIR</button>
                <label for="nav-forgot" class="switch-label">Forgot password?</label>
            </form>
        </div>

        <!-- FORGOT PASSWORD -->
        <div class="forgot-pwd-container">
            <span class="form-title">Forgot Password</span>
            <label for="nav-login" class="switch-label">Remember it? Back to Login</label>
            <form class="forgot-pwd-form" id="forgot-form">
                <label for="email-forgot">Email</label>
                <input placeholder="minion@mojojojo.com" id="email-forgot" name="email" type="email" required />
                <button type="submit" id="forgot-btn">SEND RECOVERY BEAM</button>
            </form>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="mojo-toast" class="mojo-toast"></div>

<script>
// Resize container to match active panel height
function resizeFormContainer() {
    var panels = [
        { radio: document.getElementById('nav-login'),  panel: document.querySelector('.log-in-container') },
        { radio: document.getElementById('nav-signup'), panel: document.querySelector('.sign-up-container') },
        { radio: document.getElementById('nav-forgot'), panel: document.querySelector('.forgot-pwd-container') },
    ];
    var container = document.querySelector('.form-container');
    if (!container) return;
    for (var i = 0; i < panels.length; i++) {
        if (panels[i].radio && panels[i].radio.checked && panels[i].panel) {
            // Temporarily make visible to measure
            var p = panels[i].panel;
            p.style.visibility = 'hidden';
            p.style.opacity = '0';
            p.style.transform = 'none';
            p.style.position = 'relative';
            var h = p.offsetHeight;
            p.style.position = '';
            p.style.transform = '';
            p.style.opacity = '';
            p.style.visibility = '';
            container.style.height = h + 'px';
            break;
        }
    }
}
// Run on radio change
document.querySelectorAll('.hidden-radio').forEach(function(r) {
    r.addEventListener('change', function() { setTimeout(resizeFormContainer, 30); });
});
// Run when modal becomes visible
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('signin-modal');
    if (!modal) return;
    var obs = new MutationObserver(function() {
        if (modal.classList.contains('show')) {
            setTimeout(resizeFormContainer, 40);
        }
    });
    obs.observe(modal, { attributes: true, attributeFilter: ['class'] });
    setTimeout(resizeFormContainer, 200);
});

// Show/hide password
function togglePw(inputId, el) {
    var input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type   = 'text';
        el.style.opacity = '1';
        el.style.color   = '#92ff77';
    } else {
        input.type   = 'password';
        el.style.opacity = '0.4';
        el.style.color   = '#cfb2ff';
    }
}

// Password strength checker
function checkStrength(val) {
    var bar   = document.getElementById('strength-bar');
    var label = document.getElementById('strength-label');
    if (!bar) return;

    var score = 0;
    if (val.length >= 8)              score++;
    if (val.length >= 12)             score++;
    if (/[A-Z]/.test(val))            score++;
    if (/[0-9]/.test(val))            score++;
    if (/[^A-Za-z0-9]/.test(val))     score++;

    var levels = [
        { pct:'0%',   color:'#333',    text:'' },
        { pct:'25%',  color:'#ff4d4d', text:'WEAK' },
        { pct:'50%',  color:'#ff9f45', text:'FAIR' },
        { pct:'75%',  color:'#ffd700', text:'GOOD' },
        { pct:'90%',  color:'#92ff77', text:'STRONG' },
        { pct:'100%', color:'#00e676', text:'VERY STRONG' },
    ];
    var lvl = val.length === 0 ? levels[0] : levels[Math.min(score, 5)];
    bar.style.width      = lvl.pct;
    bar.style.background = lvl.color;
    label.textContent    = lvl.text;
    label.style.color    = lvl.color;
}
</script>
