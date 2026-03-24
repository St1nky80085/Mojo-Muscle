// dashboard.js

function showToast(message, type) {
    var toast = document.getElementById('mojo-toast');
    if (!toast) return;
    toast.textContent = message;
    toast.className   = 'mojo-toast ' + (type || '');
    toast.classList.add('show');
    setTimeout(function() { toast.classList.remove('show'); }, 3500);
}

function bookingAction(classId, action, btn) {
    var origText = btn.textContent;
    btn.disabled    = true;
    btn.textContent = action === 'book' ? 'BOOKING...' : 'CANCELLING...';

    var fd = new FormData();
    fd.append('action',   action);
    fd.append('class_id', classId);

    fetch(SITE_ROOT + 'handlers/booking.php', { method: 'POST', body: fd })
        .then(function(r) {
            if (!r.ok) throw new Error('Server error ' + r.status + ' — check XAMPP is running');
            return r.json();
        })
        .then(function(data) {
            showToast((data.status === 'success' ? '✅ ' : '⚠️ ') + data.message, data.status);
            if (data.status === 'success') {
                setTimeout(function() { location.reload(); }, 1200);
            } else {
                btn.disabled    = false;
                btn.textContent = origText;
            }
        })
        .catch(function(err) {
            showToast('❌ ' + err.message, 'error');
            btn.disabled    = false;
            btn.textContent = origText;
        });
}

// Bind all book/cancel buttons
document.querySelectorAll('.book-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        bookingAction(this.dataset.id, 'book', this);
    });
});

document.querySelectorAll('.cancel-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        bookingAction(this.dataset.id, 'cancel', this);
    });
});
