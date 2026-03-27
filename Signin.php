<div id="signin-modal" class="modal-overlay">
    <span class="close-modal" id="close-modal">&times;</span>
    <div class="form-container">
        <input type="radio" name="form-nav" id="nav-login" checked class="hidden-radio" />
        <input type="radio" name="form-nav" id="nav-signup" class="hidden-radio" />
        <input type="radio" name="form-nav" id="nav-forgot" class="hidden-radio" />

        <!-- ═══ SIGN UP ═══ -->
        <div class="sign-up-container">
            <span class="form-title">Sign Up</span>
            <div id="selected-plan-display" style="display:none; text-align:center; margin-bottom:6px;">
                <span id="selected-plan-label"></span>
            </div>
            <label for="nav-login" class="switch-label">Already have an account? Login</label>

            <form class="sign-up-form" id="register-form">
                <input type="hidden" id="selected-plan" name="plan" value="Free" />

                <div class="field-group">
                    <label for="username-sign-up">Username</label>
                    <input placeholder="MojoMinion01" id="username-sign-up" name="username" type="text" required />
                </div>

                <div class="field-group">
                    <label for="email-sign-up">Email</label>
                    <input placeholder="minion@mojojojo.com" id="email-sign-up" name="email" type="email" required />
                </div>

                <div class="field-group">
                    <label for="pwd-sign-up">Password</label>
                    <div class="pw-wrap">
                        <input placeholder="Min 8 chars, 1 upper, 1 number" id="pwd-sign-up" name="password" type="password" required oninput="checkStrength(this.value)" />
                        <span class="eye-toggle" onclick="togglePw('pwd-sign-up',this)">👁</span>
                    </div>
                    <div id="strength-wrap">
                        <div id="strength-bar"></div>
                        <span id="strength-label"></span>
                    </div>
                </div>

                <div class="field-group">
                    <label for="confirm-pwd-sign-up">Confirm Password</label>
                    <div class="pw-wrap">
                        <input placeholder="••••••••" id="confirm-pwd-sign-up" name="confirm_password" type="password" required />
                        <span class="eye-toggle" onclick="togglePw('confirm-pwd-sign-up',this)">👁</span>
                    </div>
                </div>

                <button type="submit" id="register-btn">Become a Minion</button>
            </form>
        </div>

        <!-- ═══ LOGIN ═══ -->
        <div class="log-in-container">
            <span class="form-title">Login</span>
            <label for="nav-signup" class="switch-label">Don't have an account? Sign Up</label>

            <form class="log-in-form" id="login-form">
                <div class="field-group">
                    <label for="email-login">Email</label>
                    <input placeholder="minion@mojojojo.com" id="email-login" name="email" type="email" required />
                </div>

                <div class="field-group">
                    <label for="pwd-login">Password</label>
                    <div class="pw-wrap">
                        <input placeholder="••••••••" id="pwd-login" name="password" type="password" required />
                        <span class="eye-toggle" onclick="togglePw('pwd-login',this)">👁</span>
                    </div>
                </div>

                <button type="submit" id="login-btn">Enter Lair</button>
                <label for="nav-forgot" class="forgot-pwd">Forgot password?</label>
            </form>
        </div>

        <!-- ═══ FORGOT PASSWORD ═══ -->
        <div class="forgot-pwd-container">
            <span class="form-title">Forgot Password</span>
            <label for="nav-login" class="switch-label">Remember it? Back to Login</label>

            <form class="forgot-pwd-form" id="forgot-form">
                <div class="field-group">
                    <label for="email-forgot">Email</label>
                    <input placeholder="minion@mojojojo.com" id="email-forgot" name="email" type="email" required />
                </div>
                <button type="submit" id="forgot-btn">Send Recovery Beam</button>
            </form>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="mojo-toast" class="mojo-toast"></div>

<script>
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
document.querySelectorAll('.hidden-radio').forEach(function(r) {
    r.addEventListener('change', function() { setTimeout(resizeFormContainer, 30); });
});
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('signin-modal');
    if (!modal) return;
    var obs = new MutationObserver(function() {
        if (modal.classList.contains('show')) setTimeout(resizeFormContainer, 40);
    });
    obs.observe(modal, { attributes: true, attributeFilter: ['class'] });
    setTimeout(resizeFormContainer, 200);
});

function togglePw(inputId, el) {
    var input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        el.style.opacity = '1';
        el.style.color = '#92ff77';
    } else {
        input.type = 'password';
        el.style.opacity = '0.35';
        el.style.color = '#cfb2ff';
    }
}

function checkStrength(val) {
    var bar   = document.getElementById('strength-bar');
    var label = document.getElementById('strength-label');
    if (!bar) return;
    var score = 0;
    if (val.length >= 8)          score++;
    if (val.length >= 12)         score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
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
