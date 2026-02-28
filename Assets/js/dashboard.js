// =============================================
// THE MOJO MUSCLE - dashboard.js
// Requires: showToast() from signin.js (included via Signin.php)
//           getSiteRoot() from signin.js
// =============================================

document.querySelectorAll('.book-btn').forEach(function(btn) {
    btn.addEventListener('click', async function() {
        var classId = this.dataset.id;
        this.textContent = 'BOOKING...'; this.disabled = true;
        try {
            var fd = new FormData();
            fd.append('action', 'book'); fd.append('class_id', classId);
            var res  = await fetch(getSiteRoot() + 'handlers/booking.php', { method: 'POST', body: fd });
            var data = JSON.parse(await res.text());
            showToast((data.status === 'success' ? '✅ ' : '⚠️ ') + data.message, data.status);
            if (data.status === 'success') setTimeout(function() { location.reload(); }, 1200);
            else { this.textContent = 'BOOK CLASS'; this.disabled = false; }
        } catch(e) {
            showToast('❌ Error. Check XAMPP.', 'error');
            this.textContent = 'BOOK CLASS'; this.disabled = false;
        }
    });
});

document.querySelectorAll('.cancel-btn').forEach(function(btn) {
    btn.addEventListener('click', async function() {
        var classId = this.dataset.id;
        this.textContent = 'CANCELLING...'; this.disabled = true;
        try {
            var fd = new FormData();
            fd.append('action', 'cancel'); fd.append('class_id', classId);
            var res  = await fetch(getSiteRoot() + 'handlers/booking.php', { method: 'POST', body: fd });
            var data = JSON.parse(await res.text());
            showToast((data.status === 'success' ? '✅ ' : '⚠️ ') + data.message, data.status);
            if (data.status === 'success') setTimeout(function() { location.reload(); }, 1200);
            else { this.textContent = 'CANCEL'; this.disabled = false; }
        } catch(e) {
            showToast('❌ Error.', 'error');
            this.textContent = 'CANCEL'; this.disabled = false;
        }
    });
});
