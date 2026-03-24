// =============================================
// THE MOJO MUSCLE - admin.js
// =============================================

// --- TAB SWITCHER ---
function switchTab(tabName, btnEl) {
    document.querySelectorAll('.admin-tab-content').forEach(function(t) {
        t.style.display = 'none';
    });
    document.querySelectorAll('.admin-tab').forEach(function(b) {
        b.classList.remove('active');
    });
    var target = document.getElementById('tab-' + tabName);
    if (target) target.style.display = 'block';
    if (btnEl)  btnEl.classList.add('active');
}

// --- TOGGLE ADD CLASS FORM ---
function toggleAddForm() {
    var f = document.getElementById('add-class-form');
    if (!f) return;
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

// --- GENERAL FORM ---
var generalForm = document.getElementById('admin-form-general');
if (generalForm) {
    generalForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        await saveAdminForm(this, 'SAVE GENERAL');
    });
}

// --- HOURS FORM ---
var hoursForm = document.getElementById('admin-form-hours');
if (hoursForm) {
    hoursForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        await saveAdminForm(this, 'SAVE HOURS');
    });
}

// --- SHARED SAVE FUNCTION ---
async function saveAdminForm(formEl, btnLabel) {
    var btn = formEl.querySelector('.admin-save-btn');
    var origText = btn.textContent;
    btn.textContent = '💾 SAVING...'; btn.disabled = true;
    try {
        var fd = new FormData(formEl);
        var res = await fetch(SITE_ROOT + 'handlers/admin_update.php', { method: 'POST', body: fd });
        var text = await res.text();
        var data;
        try { data = JSON.parse(text); }
        catch(e) { console.error('PHP output:\n' + text); showToast('Server error — see Console.', 'error'); btn.textContent = origText; btn.disabled = false; return; }
        if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(function() { location.reload(); }, 1200);
        } else { showToast(data.message, 'error'); }
    } catch(err) { showToast('Save failed. Check XAMPP.', 'error'); }
    btn.textContent = origText; btn.disabled = false;
}

// --- DAY STATUS TOGGLE ---
function toggleDayStatus(btn) {
    var day        = btn.dataset.day;
    var input      = document.getElementById('status-input-' + day);
    var hoursInput = document.getElementById('hours-input-' + day);
    var isOpen     = btn.classList.contains('open');
    if (isOpen) {
        btn.classList.replace('open', 'closed');
        btn.textContent = '🔴 CLOSED';
        input.value = 'closed';
        if (hoursInput) hoursInput.disabled = true;
    } else {
        btn.classList.replace('closed', 'open');
        btn.textContent = '🟢 OPEN';
        input.value = 'open';
        if (hoursInput) hoursInput.disabled = false;
    }
}

// --- TOGGLE CLASS STATUS ---
async function toggleClassStatus(classId, btn) {
    var isClosed  = btn.textContent.trim() === '🔴';
    var newStatus = isClosed ? 'open' : 'closed';
    try {
        var fd = new FormData();
        fd.append('action', 'toggle_class_status');
        fd.append('class_id', classId);
        fd.append('new_status', newStatus);
        var res  = await fetch(SITE_ROOT + 'handlers/admin_update.php', { method: 'POST', body: fd });
        var data = JSON.parse(await res.text());
        if (data.status === 'success') {
            btn.textContent = newStatus === 'closed' ? '🔴' : '🟢';
            showToast('Class is now ' + newStatus.toUpperCase(), 'success');
        } else { showToast(data.message, 'error'); }
    } catch(e) { showToast('Error. Check XAMPP.', 'error'); }
}

// --- ADD CLASS ---
async function addClass() {
    var name       = document.getElementById('new-class-name').value.trim();
    var instructor = document.getElementById('new-instructor').value.trim();
    var day        = document.getElementById('new-day').value;
    var start      = document.getElementById('new-start').value;
    var end        = document.getElementById('new-end').value;
    var slots      = document.getElementById('new-slots').value;

    if (!name || !instructor || !start || !end) { showToast('Fill in all fields.', 'error'); return; }
    if (start >= end) { showToast('End time must be after start time.', 'error'); return; }

    try {
        var fd = new FormData();
        fd.append('action', 'add_class');
        fd.append('class_name', name);
        fd.append('instructor', instructor);
        fd.append('schedule_day', day);
        fd.append('start_time', start);
        fd.append('end_time', end);
        fd.append('max_slots', slots);
        var res  = await fetch(SITE_ROOT + 'handlers/admin_update.php', { method: 'POST', body: fd });
        var data = JSON.parse(await res.text());
        if (data.status === 'success') {
            showToast('Class added!', 'success');
            var list = document.getElementById('schedule-list');
            var row  = document.createElement('div');
            row.className = 'schedule-item';
            row.id = 'class-row-' + data.class_id;
            row.innerHTML =
                '<button type="button" class="class-status-btn open" onclick="toggleClassStatus(' + data.class_id + ', this)">🟢</button>' +
                '<div class="schedule-info">' +
                    '<span class="schedule-day-tag">' + data.schedule_day + '</span>' +
                    '<span class="schedule-name">' + escHtml(data.class_name) + '</span>' +
                    '<span class="schedule-meta">👤 ' + escHtml(data.instructor) + ' | 🕐 ' + formatTime(data.start_time) + '–' + formatTime(data.end_time) + ' | 🪑 ' + data.max_slots + ' slots</span>' +
                '</div>' +
                '<button type="button" class="delete-class-btn" onclick="deleteClass(' + data.class_id + ', this)">🗑️</button>';
            list.appendChild(row);
            document.getElementById('new-class-name').value = '';
            document.getElementById('new-instructor').value = '';
            document.getElementById('new-start').value = '';
            document.getElementById('new-end').value   = '';
            document.getElementById('new-slots').value = '20';
            toggleAddForm();
        } else { showToast(data.message, 'error'); }
    } catch(e) { console.error(e); showToast('❌ Error adding class.', 'error'); }
}

// --- DELETE CLASS ---
async function deleteClass(classId) {
    if (!confirm('Delete this class? Cannot be undone.')) return;
    try {
        var fd = new FormData();
        fd.append('action', 'delete_class');
        fd.append('class_id', classId);
        var res  = await fetch(SITE_ROOT + 'handlers/admin_update.php', { method: 'POST', body: fd });
        var data = JSON.parse(await res.text());
        if (data.status === 'success') {
            showToast('Class deleted.', 'success');
            var row = document.getElementById('class-row-' + classId);
            if (row) { row.style.opacity = '0'; row.style.transition = 'opacity 0.3s'; setTimeout(function() { row.remove(); }, 300); }
        } else { showToast(data.message, 'error'); }
    } catch(e) { showToast('❌ Error.', 'error'); }
}

// --- HELPERS ---
function formatTime(t) {
    if (!t) return '';
    var p = t.split(':'), h = parseInt(p[0]), m = p[1];
    var ap = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return h + ':' + m + ' ' + ap;
}
function escHtml(str) {
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

// =============================================
// ACCOUNTS TAB
// =============================================

var currentFilter = 'all';

// Search
function filterAccounts(query) {
    query = query.toLowerCase().trim();
    applyFilters(query, currentFilter);
}

// Filter pills
function setFilter(filter, btn) {
    currentFilter = filter;
    document.querySelectorAll('.acc-pill').forEach(function(p) { p.classList.remove('active'); });
    btn.classList.add('active');
    var searchVal = document.getElementById('account-search');
    applyFilters(searchVal ? searchVal.value.toLowerCase() : '', filter);
}

// Apply both search + filter
function applyFilters(query, filter) {
    var rows  = document.querySelectorAll('.acc-row');
    var shown = 0;
    rows.forEach(function(row) {
        var searchData = row.dataset.search || '';
        var role       = row.dataset.role   || '';
        var mem        = row.dataset.mem    || '';

        var matchSearch = !query || searchData.includes(query);
        var matchFilter = filter === 'all'    ? true
                        : filter === 'member' ? role === 'member'
                        : filter === 'admin'  ? role === 'admin'
                        : filter === 'active' ? mem  === 'active'
                        : filter === 'expired'? mem  === 'expired'
                        : true;

        var visible = matchSearch && matchFilter;
        row.style.display = visible ? 'flex' : 'none';
        if (visible) shown++;
    });

    var empty = document.getElementById('acc-empty');
    if (empty) empty.style.display = shown === 0 ? 'block' : 'none';
}

// Delete user
async function deleteUser(userId, username, btn) {
    if (!confirm('Delete account "' + username + '"?\nThis will also remove their membership and bookings.')) return;

    btn.disabled = true; btn.textContent = '...';
    try {
        var fd = new FormData();
        fd.append('action', 'delete_user');
        fd.append('user_id', userId);
        var res  = await fetch(SITE_ROOT + 'handlers/admin_update.php', { method: 'POST', body: fd });
        var data = JSON.parse(await res.text());
        if (data.status === 'success') {
            showToast('Account deleted: ' + username + '" deleted.', 'success');
            var row = btn.closest('.acc-row');
            if (row) {
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.3s';
                setTimeout(function() { row.remove(); }, 300);
            }
        } else {
            showToast(data.message, 'error');
            btn.disabled = false; btn.textContent = '🗑️';
        }
    } catch(e) {
        showToast('Error. Check XAMPP.', 'error');
        btn.disabled = false; btn.textContent = '🗑️';
    }
}

// =============================================
// MEMBERSHIP EDITOR
// =============================================

function openMembershipEditor(userId, username, plan, status) {
    document.getElementById('mem-editor-user-id').value  = userId;
    document.getElementById('mem-editor-username').textContent = '👤 ' + username;
    document.getElementById('mem-editor-plan').value   = plan   || 'Free';
    document.getElementById('mem-editor-status').value = status || 'active';

    // Default end date = today + 30 days
    var d = new Date(); d.setDate(d.getDate() + 30);
    document.getElementById('mem-editor-enddate').value = d.toISOString().split('T')[0];

    document.getElementById('membership-editor-modal').classList.add('show');
}

function extendDays(days) {
    var input = document.getElementById('mem-editor-enddate');
    var base  = input.value ? new Date(input.value) : new Date();
    base.setDate(base.getDate() + days);
    input.value = base.toISOString().split('T')[0];
}

async function saveMembership() {
    var userId  = document.getElementById('mem-editor-user-id').value;
    var plan    = document.getElementById('mem-editor-plan').value;
    var status  = document.getElementById('mem-editor-status').value;
    var endDate = document.getElementById('mem-editor-enddate').value;

    if (!endDate) { showToast('Please set an end date.', 'error'); return; }

    var btn = document.querySelector('#membership-editor-modal .admin-save-btn');
    btn.textContent = '💾 SAVING...'; btn.disabled = true;

    try {
        var fd = new FormData();
        fd.append('action',   'update_membership');
        fd.append('user_id',  userId);
        fd.append('plan',     plan);
        fd.append('status',   status);
        fd.append('end_date', endDate);

        var res  = await fetch(SITE_ROOT + 'handlers/admin_update.php', { method:'POST', body:fd });
        var data = JSON.parse(await res.text());

        if (data.status === 'success') {
            showToast(data.message, 'success');
            document.getElementById('membership-editor-modal').classList.remove('show');
            setTimeout(function() { location.reload(); }, 1000);
        } else {
            showToast(data.message, 'error');
        }
    } catch(e) {
        showToast('❌ Error saving membership.', 'error');
    }
    btn.textContent = '💾 SAVE MEMBERSHIP'; btn.disabled = false;
}
