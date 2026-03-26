// signin.js

const signinModal = document.getElementById('signin-modal');
const openBtn     = document.getElementById('open-signin-btn');
const openBtn2    = document.getElementById('open-signin-btn-wplan');
const closeBtn    = document.getElementById('close-modal');

function openSigninModal(e) {
    e.preventDefault();
    signinModal.classList.add('show');
    setTimeout(function() {
        if (typeof resizeFormContainer === 'function') resizeFormContainer();
    }, 50);
}
if (openBtn)  openBtn.addEventListener('click',  openSigninModal);
if (openBtn2) openBtn2.addEventListener('click', openSigninModal);
if (closeBtn) closeBtn.addEventListener('click', function() { signinModal.classList.remove('show'); });
window.addEventListener('click', function(e) { if (e.target === signinModal) signinModal.classList.remove('show'); });

// Open modal pre-selecting a plan
function openSigninWithPlan(plan) {
    document.getElementById('selected-plan').value = plan;
    var display = document.getElementById('selected-plan-display');
    var label   = document.getElementById('selected-plan-label');
    if (plan !== 'Free') {
        var icons = { Premium: '⭐', VIP: '👑' };
        label.textContent = (icons[plan] || '') + ' ' + plan.toUpperCase() + ' PLAN';
        display.style.display = 'block';
    } else {
        display.style.display = 'none';
    }
    document.getElementById('nav-signup').checked = true;
    signinModal.classList.add('show');
}

// Toast
function showToast(message, type) {
    var toast = document.getElementById('mojo-toast');
    if (!toast) return;
    toast.textContent = message;
    toast.className   = 'mojo-toast ' + (type || '');
    toast.classList.add('show');
    setTimeout(function() { toast.classList.remove('show'); }, 3500);
}

// Register
var registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var pwd  = document.getElementById('pwd-sign-up').value;
        var conf = document.getElementById('confirm-pwd-sign-up').value;
        if (pwd !== conf) { showToast('Passwords do not match.', 'error'); return; }
        var btn = document.getElementById('register-btn');
        btn.disabled = true; btn.textContent = 'ENROLLING...';
        fetch(SITE_ROOT + 'handlers/register.php', { method:'POST', body: new FormData(registerForm) })
            .then(function(r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function(res) {
                showToast(res.message, res.status);
                if (res.status === 'success') {
                    registerForm.reset();
                    document.getElementById('selected-plan-display').style.display = 'none';
                    var bar = document.getElementById('strength-bar');
                    var lbl = document.getElementById('strength-label');
                    if (bar) { bar.style.width = '0%'; }
                    if (lbl) { lbl.textContent = ''; }
                    document.getElementById('nav-login').checked = true;
                }
            })
            .catch(function(err) { showToast('Connection error: ' + err.message, 'error'); })
            .finally(function() { btn.disabled = false; btn.textContent = 'BECOME A MINION'; });
    });
}

// Login
var loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var btn = document.getElementById('login-btn');
        btn.disabled = true; btn.textContent = 'ENTERING...';
        fetch(SITE_ROOT + 'handlers/login.php', { method:'POST', body: new FormData(loginForm) })
            .then(function(r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function(res) {
                showToast(res.message, res.status);
                if (res.status === 'success') {
                    setTimeout(function() {
                        window.location.href = res.role === 'admin' ? SITE_ROOT + 'HOME.php' : SITE_ROOT + 'dashboard.php';
                    }, 1000);
                }
            })
            .catch(function(err) { showToast('Connection error: ' + err.message, 'error'); })
            .finally(function() { btn.disabled = false; btn.textContent = 'ENTER LAIR'; });
    });
}

// Forgot password
var forgotForm = document.getElementById('forgot-form');
if (forgotForm) {
    forgotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var btn = document.getElementById('forgot-btn');
        btn.disabled = true; btn.textContent = 'SENDING...';
        fetch(SITE_ROOT + 'handlers/forgot_password.php', { method:'POST', body: new FormData(forgotForm) })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                showToast(res.message, res.status);
                if (res.status === 'success') { forgotForm.reset(); }
            })
            .catch(function() { showToast('Something went wrong.', 'error'); })
            .finally(function() { btn.disabled = false; btn.textContent = 'SEND RECOVERY BEAM'; });
    });
}
