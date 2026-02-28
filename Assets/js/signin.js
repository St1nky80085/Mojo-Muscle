// =============================================
// THE MOJO MUSCLE - signin.js
// =============================================

function showToast(message, type) {
    type = type || 'success';
    var toast = document.getElementById('mojo-toast');
    if (!toast) return;
    toast.textContent = message;
    toast.className = 'mojo-toast ' + type + ' show';
    setTimeout(function() { toast.classList.remove('show'); }, 4000);
}

async function safeFetch(url, formData) {
    var res  = await fetch(url, { method: 'POST', body: formData });
    var text = await res.text();
    try { return JSON.parse(text); }
    catch(e) { console.error('=== PHP ERROR OUTPUT ===\n' + text); throw new Error('Server error — open Console for details.'); }
}

function getSiteRoot() {
    if (typeof SITE_ROOT !== 'undefined') return SITE_ROOT;
    var parts = window.location.pathname.split('/');
    return window.location.origin + '/' + parts[1] + '/';
}

// ── OPEN MODAL WITH PLAN ─────────────────────────────────
function openSigninWithPlan(plan) {
    var modal = document.getElementById('signin-modal');
    if (!modal) return;

    // Set hidden plan input
    var planInput   = document.getElementById('selected-plan');
    var planDisplay = document.getElementById('selected-plan-display');
    var planLabel   = document.getElementById('selected-plan-label');

    if (planInput) planInput.value = plan;

    if (plan && plan !== 'Free' && planDisplay && planLabel) {
        var labels = { Monthly: '🟢 MONTHLY PLAN', Quarterly: '⭐ QUARTERLY PLAN', Annual: '👑 ANNUAL VIP PLAN' };
        planLabel.textContent   = labels[plan] || plan;
        planDisplay.style.display = 'block';
    } else if (planDisplay) {
        planDisplay.style.display = 'none';
    }

    // Switch to signup tab
    var signupRadio = document.getElementById('nav-signup');
    if (signupRadio) signupRadio.checked = true;

    modal.classList.add('show');
}

// ── MODAL OPEN / CLOSE ───────────────────────────────────
var openModalBtn  = document.getElementById('open-signin-btn');
var signinModal   = document.getElementById('signin-modal');
var closeModalBtn = document.getElementById('close-modal');

if (openModalBtn && signinModal) {
    openModalBtn.addEventListener('click', function(e) {
        e.preventDefault();
        // Default to login tab when clicking SIGN IN in navbar
        var loginRadio = document.getElementById('nav-login');
        if (loginRadio) loginRadio.checked = true;
        signinModal.classList.add('show');
    });
}
if (closeModalBtn) {
    closeModalBtn.addEventListener('click', function() { signinModal.classList.remove('show'); });
}
window.addEventListener('click', function(e) {
    if (e.target === signinModal) signinModal.classList.remove('show');
});

// ── REGISTER ─────────────────────────────────────────────
var registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var username = document.getElementById('username-sign-up').value.trim();
        var password = document.getElementById('pwd-sign-up').value;
        var confirm  = document.getElementById('confirm-pwd-sign-up').value;
        var btn      = document.getElementById('register-btn');

        if (username.length < 3) { showToast('Username must be at least 3 characters.', 'error'); return; }
        if (password.length < 8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
            showToast('Password: 8+ chars, 1 uppercase, 1 number.', 'error'); return;
        }
        if (password !== confirm) { showToast('Passwords do not match!', 'error'); return; }

        btn.textContent = 'DEPLOYING...'; btn.disabled = true;
        try {
            var data = await safeFetch(getSiteRoot() + 'handlers/register.php', new FormData(registerForm));
            if (data.status === 'success') {
                showToast('🧠 ' + data.message, 'success');
                registerForm.reset();
                document.getElementById('selected-plan-display').style.display = 'none';
                setTimeout(function() { document.getElementById('nav-login').checked = true; }, 2000);
            } else {
                showToast('⚠️ ' + data.message, 'error');
            }
        } catch(err) { showToast('❌ ' + err.message, 'error'); }
        btn.textContent = 'BECOME A MINION'; btn.disabled = false;
    });
}

// ── LOGIN ─────────────────────────────────────────────────
var loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var btn = document.getElementById('login-btn');
        btn.textContent = 'ENTERING...'; btn.disabled = true;
        try {
            var data = await safeFetch(getSiteRoot() + 'handlers/login.php', new FormData(loginForm));
            if (data.status === 'success') {
                showToast('🔓 ' + data.message, 'success');
                loginForm.reset();
                signinModal.classList.remove('show');
                setTimeout(function() {
                    if (data.role === 'admin') {
                        window.location.reload();
                    } else {
                        window.location.href = getSiteRoot() + 'dashboard.php';
                    }
                }, 1200);
            } else {
                showToast('⚠️ ' + data.message, 'error');
            }
        } catch(err) { showToast('❌ ' + err.message, 'error'); }
        btn.textContent = 'ENTER LAIR'; btn.disabled = false;
    });
}

// ── FORGOT PASSWORD ───────────────────────────────────────
var forgotForm = document.getElementById('forgot-form');
if (forgotForm) {
    forgotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('📡 Recovery beam sent! (Coming soon)', 'success');
        forgotForm.reset();
    });
}
