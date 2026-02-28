// =============================================
// THE MOJO MUSCLE - Profile Settings JS
// File: assets/js/profile.js
// =============================================

function showToast(message, type) {
    type = type || 'success';
    var toast = document.getElementById('mojo-toast');
    if (!toast) return;
    toast.textContent = message;
    toast.className = 'mojo-toast ' + type + ' show';
    setTimeout(function() { toast.classList.remove('show'); }, 4000);
}

function getSiteRoot() {
    if (typeof SITE_ROOT !== 'undefined') return SITE_ROOT;
    var parts = window.location.pathname.split('/');
    return window.location.origin + '/' + parts[1] + '/';
}

async function postSettings(action, formEl) {
    var fd = new FormData(formEl);
    fd.append('action', action);
    var res  = await fetch(getSiteRoot() + 'handlers/profile_update.php', { method: 'POST', body: fd });
    var text = await res.text();
    try { return JSON.parse(text); }
    catch(e) { console.error(text); throw new Error('Server error — check Console.'); }
}

function disableBtn(btn, label) {
    btn.disabled = true;
    btn.dataset.original = btn.textContent;
    btn.textContent = label;
}
function enableBtn(btn) {
    btn.disabled = false;
    btn.textContent = btn.dataset.original;
}

// --- UPDATE USERNAME ---
var usernameForm = document.getElementById('update-username-form');
if (usernameForm) {
    usernameForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var btn = this.querySelector('button');
        disableBtn(btn, 'SAVING...');
        try {
            var data = await postSettings('update_username', this);
            if (data.status === 'success') {
                showToast('✅ ' + data.message, 'success');
                // Update navbar name live
                var navName = document.querySelector('.nav-username');
                if (navName && data.username) navName.textContent = data.username;
            } else {
                showToast('⚠️ ' + data.message, 'error');
            }
        } catch(err) { showToast('❌ ' + err.message, 'error'); }
        enableBtn(btn);
    });
}

// --- UPDATE EMAIL ---
var emailForm = document.getElementById('update-email-form');
if (emailForm) {
    emailForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var btn = this.querySelector('button');
        disableBtn(btn, 'SAVING...');
        try {
            var data = await postSettings('update_email', this);
            showToast((data.status === 'success' ? '✅ ' : '⚠️ ') + data.message, data.status);
            if (data.status === 'success') this.reset();
        } catch(err) { showToast('❌ ' + err.message, 'error'); }
        enableBtn(btn);
    });
}

// --- CHANGE PASSWORD ---
var pwdForm = document.getElementById('reset-password-form');
if (pwdForm) {
    pwdForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var newPwd  = this.querySelector('[name="new_password"]').value;
        var confPwd = this.querySelector('[name="confirm_password"]').value;
        if (newPwd.length < 8 || !/[A-Z]/.test(newPwd) || !/[0-9]/.test(newPwd)) {
            showToast('Password: 8+ chars, 1 uppercase, 1 number.', 'error'); return;
        }
        if (newPwd !== confPwd) { showToast('Passwords do not match!', 'error'); return; }

        var btn = this.querySelector('button');
        disableBtn(btn, 'SAVING...');
        try {
            var data = await postSettings('change_password', this);
            showToast((data.status === 'success' ? '✅ ' : '⚠️ ') + data.message, data.status);
            if (data.status === 'success') this.reset();
        } catch(err) { showToast('❌ ' + err.message, 'error'); }
        enableBtn(btn);
    });
}

// --- DELETE ACCOUNT ---
var deleteBtn    = document.getElementById('delete-account-btn');
var confirmModal = document.getElementById('confirm-delete-modal');
var confirmBtn   = document.getElementById('confirm-delete-btn');
var cancelBtn    = document.getElementById('cancel-delete-btn');

if (deleteBtn) {
    deleteBtn.addEventListener('click', function() {
        confirmModal.classList.add('show');
    });
}
if (cancelBtn) {
    cancelBtn.addEventListener('click', function() {
        confirmModal.classList.remove('show');
        document.getElementById('delete-confirm-pwd').value = '';
    });
}
window.addEventListener('click', function(e) {
    if (e.target === confirmModal) {
        confirmModal.classList.remove('show');
        document.getElementById('delete-confirm-pwd').value = '';
    }
});
if (confirmBtn) {
    confirmBtn.addEventListener('click', async function() {
        var pwd = document.getElementById('delete-confirm-pwd').value;
        if (!pwd) { showToast('Enter your password to confirm.', 'error'); return; }
        disableBtn(confirmBtn, 'DELETING...');
        try {
            var fd = new FormData();
            fd.append('action', 'delete_account');
            fd.append('password', pwd);
            var res  = await fetch(getSiteRoot() + 'handlers/profile_update.php', { method: 'POST', body: fd });
            var data = JSON.parse(await res.text());
            if (data.status === 'success') {
                showToast('👋 ' + data.message, 'success');
                setTimeout(function() { window.location.href = getSiteRoot() + 'HOME.php'; }, 2000);
            } else {
                showToast('⚠️ ' + data.message, 'error');
                enableBtn(confirmBtn);
            }
        } catch(err) {
            showToast('❌ Error. Try again.', 'error');
            enableBtn(confirmBtn);
        }
    });
}
