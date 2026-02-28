<?php
// Fetch classes for schedule tab
$classes_res = $conn->query("SELECT * FROM gym_classes ORDER BY FIELD(schedule_day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time");
$all_admin_classes = $classes_res ? $classes_res->fetch_all(MYSQLI_ASSOC) : [];

// Fetch all users with membership info
$users_res = $conn->query("
    SELECT u.id, u.username, u.email, u.role, u.created_at,
           m.plan, m.status AS mem_status, m.end_date
    FROM users u
    LEFT JOIN memberships m ON m.id = (
        SELECT id FROM memberships WHERE user_id = u.id ORDER BY id DESC LIMIT 1
    )
    ORDER BY u.created_at DESC
");
$all_users = $users_res ? $users_res->fetch_all(MYSQLI_ASSOC) : [];
?>

<div id="admin-panel" class="modal-overlay">
    <span class="close-modal" onclick="document.getElementById('admin-panel').classList.remove('show')">&times;</span>

    <div class="admin-container">
        <h2 class="form-title" style="margin-bottom:20px;">⚙️ EDIT HOMEPAGE</h2>

        <!-- TAB BUTTONS -->
        <div class="admin-tabs">
            <button type="button" class="admin-tab active" onclick="switchTab('general',  this)">📋 General</button>
            <button type="button" class="admin-tab"        onclick="switchTab('hours',    this)">🕐 Hours</button>
            <button type="button" class="admin-tab"        onclick="switchTab('schedule', this)">🏋️ Schedule</button>
            <button type="button" class="admin-tab"        onclick="switchTab('accounts', this)">👥 Accounts</button>
        </div>

        <!-- ===== TAB: GENERAL ===== -->
        <div class="admin-tab-content" id="tab-general">
            <form id="admin-form-general">
                <input type="hidden" name="action" value="update_home" />
                <div class="admin-section">
                    <label class="admin-label">📢 Announcement Banner <small>(leave blank to hide)</small></label>
                    <input class="input" type="text" name="announcement"
                           value="<?php echo htmlspecialchars($content['announcement'] ?? ''); ?>"
                           placeholder="e.g. Gym closed Dec 25!" />
                </div>
                <div class="admin-section">
                    <label class="admin-label">👥 Active Member Count</label>
                    <input class="input" type="number" name="active_members" min="0"
                           value="<?php echo htmlspecialchars($content['active_members'] ?? '120'); ?>" />
                </div>
                <div class="admin-section">
                    <label class="admin-label">🗓️ Upcoming Events</label>
                    <textarea class="input admin-textarea" name="upcoming_events"
                              placeholder="List upcoming events here..."><?php echo htmlspecialchars($content['upcoming_events'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="admin-save-btn">💾 SAVE GENERAL</button>
            </form>
        </div>

        <!-- ===== TAB: HOURS ===== -->
        <div class="admin-tab-content" id="tab-hours" style="display:none;">
            <form id="admin-form-hours">
                <input type="hidden" name="action" value="update_home" />
                <div class="hours-grid">
                    <?php
                    $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
                    foreach ($days as $day):
                        $day_status = $content['status_' . $day] ?? 'open';
                        $is_closed  = $day_status === 'closed';
                    ?>
                    <div class="hours-row">
                        <button type="button"
                            class="day-status-btn <?php echo $is_closed ? 'closed' : 'open'; ?>"
                            data-day="<?php echo $day; ?>"
                            onclick="toggleDayStatus(this)">
                            <?php echo $is_closed ? '🔴 CLOSED' : '🟢 OPEN'; ?>
                        </button>
                        <input type="hidden" name="status_<?php echo $day; ?>"
                               value="<?php echo $day_status; ?>"
                               id="status-input-<?php echo $day; ?>" />
                        <span class="day-name"><?php echo ucfirst($day); ?></span>
                        <input class="input hours-input" type="text"
                               name="hours_<?php echo $day; ?>"
                               id="hours-input-<?php echo $day; ?>"
                               value="<?php echo htmlspecialchars($content['hours_' . $day] ?? ''); ?>"
                               placeholder="e.g. 6 AM - 10 PM"
                               <?php echo $is_closed ? 'disabled' : ''; ?> />
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="admin-save-btn" style="margin-top:20px;">💾 SAVE HOURS</button>
            </form>
        </div>

        <!-- ===== TAB: SCHEDULE ===== -->
        <div class="admin-tab-content" id="tab-schedule" style="display:none;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <span class="admin-label" style="margin:0;">Manage gym class schedule</span>
                <button type="button" class="admin-save-btn"
                        style="width:auto; padding:8px 18px; margin:0;"
                        onclick="toggleAddForm()">➕ Add Class</button>
            </div>

            <!-- ADD CLASS FORM -->
            <div id="add-class-form" style="display:none;" class="add-class-box">
                <p class="admin-label" style="font-weight:bold; color:#92ff77; margin-bottom:12px;">➕ NEW CLASS</p>
                <div class="class-form-grid">
                    <div>
                        <label class="admin-label">Class Name</label>
                        <input class="input" type="text" id="new-class-name" placeholder="e.g. Power Lifting" />
                    </div>
                    <div>
                        <label class="admin-label">Instructor</label>
                        <input class="input" type="text" id="new-instructor" placeholder="e.g. Coach Mojo" />
                    </div>
                    <div>
                        <label class="admin-label">Day</label>
                        <select class="input" id="new-day">
                            <?php foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d): ?>
                            <option><?php echo $d; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Start Time</label>
                        <input class="input" type="time" id="new-start" />
                    </div>
                    <div>
                        <label class="admin-label">End Time</label>
                        <input class="input" type="time" id="new-end" />
                    </div>
                    <div>
                        <label class="admin-label">Max Slots</label>
                        <input class="input" type="number" id="new-slots" value="20" min="1" max="100" />
                    </div>
                </div>
                <button type="button" class="admin-save-btn" style="margin-top:14px;" onclick="addClass()">💾 ADD CLASS</button>
            </div>

            <!-- CLASS LIST -->
            <div class="schedule-list" id="schedule-list">
                <?php foreach ($all_admin_classes as $cls):
                    $cls_status = $cls['status'] ?? 'open';
                ?>
                <div class="schedule-item" id="class-row-<?php echo $cls['id']; ?>">
                    <button type="button"
                        class="class-status-btn <?php echo $cls_status; ?>"
                        onclick="toggleClassStatus(<?php echo $cls['id']; ?>, this)">
                        <?php echo $cls_status === 'closed' ? '🔴' : '🟢'; ?>
                    </button>
                    <div class="schedule-info">
                        <span class="schedule-day-tag"><?php echo $cls['schedule_day']; ?></span>
                        <span class="schedule-name"><?php echo htmlspecialchars($cls['class_name']); ?></span>
                        <span class="schedule-meta">
                            👤 <?php echo htmlspecialchars($cls['instructor']); ?> &nbsp;|&nbsp;
                            🕐 <?php echo date('g:i A', strtotime($cls['start_time'])); ?>–<?php echo date('g:i A', strtotime($cls['end_time'])); ?> &nbsp;|&nbsp;
                            🪑 <?php echo $cls['max_slots']; ?> slots
                        </span>
                    </div>
                    <button type="button" class="delete-class-btn"
                            onclick="deleteClass(<?php echo $cls['id']; ?>, this)">🗑️</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ===== TAB: ACCOUNTS ===== -->
        <div class="admin-tab-content" id="tab-accounts" style="display:none;">

            <!-- STATS ROW -->
            <?php
            $total    = count($all_users);
            $members  = count(array_filter($all_users, fn($u) => $u['role'] === 'member'));
            $admins   = count(array_filter($all_users, fn($u) => $u['role'] === 'admin'));
            $active   = count(array_filter($all_users, fn($u) => ($u['mem_status'] ?? '') === 'active'));
            ?>
            <div class="acc-stats-row">
                <div class="acc-stat"><span class="acc-stat-num"><?php echo $total; ?></span><span class="acc-stat-label">Total</span></div>
                <div class="acc-stat"><span class="acc-stat-num" style="color:#92ff77;"><?php echo $members; ?></span><span class="acc-stat-label">Members</span></div>
                <div class="acc-stat"><span class="acc-stat-num" style="color:#cfb2ff;"><?php echo $admins; ?></span><span class="acc-stat-label">Admins</span></div>
                <div class="acc-stat"><span class="acc-stat-num" style="color:#ffd700;"><?php echo $active; ?></span><span class="acc-stat-label">Active Mem.</span></div>
            </div>

            <!-- SEARCH BAR -->
            <div class="acc-search-wrap">
                <input type="text" id="account-search" class="input acc-search-input"
                       placeholder="🔍  Search by username, email or plan..."
                       oninput="filterAccounts(this.value)" />
            </div>

            <!-- FILTER PILLS -->
            <div class="acc-filter-pills">
                <button type="button" class="acc-pill active" onclick="setFilter('all', this)">All</button>
                <button type="button" class="acc-pill" onclick="setFilter('member', this)">Members</button>
                <button type="button" class="acc-pill" onclick="setFilter('admin', this)">Admins</button>
                <button type="button" class="acc-pill" onclick="setFilter('active', this)">Active</button>
                <button type="button" class="acc-pill" onclick="setFilter('expired', this)">Expired</button>
            </div>

            <!-- ACCOUNTS LIST -->
            <div class="acc-list" id="acc-list">
                <?php foreach ($all_users as $u):
                    $mem_status = $u['mem_status'] ?? 'none';
                    $plan       = $u['plan'] ?? '—';
                    $end_date   = $u['end_date'] ? date('M j, Y', strtotime($u['end_date'])) : '—';
                    $days_left  = $u['end_date'] ? max(0, (int)((strtotime($u['end_date']) - time()) / 86400)) : 0;
                    $is_admin_u = $u['role'] === 'admin';
                ?>
                <div class="acc-row"
                     data-role="<?php echo $u['role']; ?>"
                     data-mem="<?php echo $mem_status; ?>"
                     data-search="<?php echo strtolower($u['username'] . ' ' . $u['email'] . ' ' . $plan); ?>">

                    <!-- AVATAR -->
                    <div class="acc-avatar" style="background:<?php echo $is_admin_u ? 'rgba(151,95,255,0.2)' : 'rgba(0,230,118,0.1)'; ?>; border-color:<?php echo $is_admin_u ? '#9757ff' : '#4a148c'; ?>;">
                        <?php echo $is_admin_u ? '🔐' : '🧠'; ?>
                    </div>

                    <!-- INFO -->
                    <div class="acc-info">
                        <div class="acc-name-row">
                            <span class="acc-username"><?php echo htmlspecialchars($u['username']); ?></span>
                            <span class="acc-role-badge <?php echo $u['role']; ?>"><?php echo strtoupper($u['role']); ?></span>
                            <?php if ($mem_status === 'active'): ?>
                            <span class="acc-mem-badge active"><?php echo $plan; ?></span>
                            <?php elseif ($mem_status === 'expired'): ?>
                            <span class="acc-mem-badge expired">EXPIRED</span>
                            <?php endif; ?>
                        </div>
                        <div class="acc-email"><?php echo htmlspecialchars($u['email']); ?></div>
                        <div class="acc-meta">
                            Joined <?php echo date('M j, Y', strtotime($u['created_at'])); ?>
                            <?php if ($u['end_date']): ?>
                            &nbsp;·&nbsp;
                            <span style="color:<?php echo $days_left < 7 ? '#ff6b6b' : '#92ff77'; ?>">
                                <?php echo $days_left; ?>d left
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <?php if (!$is_admin_u): ?>
                    <div class="acc-actions">
                        <button type="button" class="acc-action-btn edit"
                                onclick="openMembershipEditor(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars($u['username']); ?>', '<?php echo $plan; ?>', '<?php echo $mem_status; ?>')"
                                title="Edit membership">✏️</button>
                        <button type="button" class="acc-action-btn delete"
                                onclick="deleteUser(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars($u['username']); ?>', this)"
                                title="Delete account">🗑️</button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

                <!-- EMPTY STATE -->
                <div id="acc-empty" style="display:none; text-align:center; padding:30px; color:#666; font-family:'Courier New',monospace;">
                    No accounts match your search.
                </div>
            </div>
        </div>

    </div><!-- end admin-container -->
</div><!-- end admin-panel -->

<!-- ===== MEMBERSHIP EDITOR MODAL ===== -->
<div id="membership-editor-modal" class="modal-overlay" style="z-index:100001;">
    <div class="mem-editor-box">
        <button type="button" class="close-modal" onclick="document.getElementById('membership-editor-modal').classList.remove('show')">&times;</button>
        <p class="form-title" style="margin-bottom:4px;">✏️ EDIT MEMBERSHIP</p>
        <p id="mem-editor-username" style="color:#cfb2ff; font-size:0.85rem; font-family:'Courier New',monospace; margin-bottom:20px; text-align:center;"></p>

        <input type="hidden" id="mem-editor-user-id" />

        <label class="admin-label">Plan</label>
        <select class="input" id="mem-editor-plan" style="margin-bottom:14px; cursor:pointer;">
            <option value="Free">Free</option>
            <option value="Premium">Premium</option>
            <option value="VIP">VIP</option>
        </select>

        <label class="admin-label">Status</label>
        <select class="input" id="mem-editor-status" style="margin-bottom:14px; cursor:pointer;">
            <option value="active">Active</option>
            <option value="expired">Expired</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <label class="admin-label">Extend / Set End Date</label>
        <div class="mem-extend-btns">
            <button type="button" class="mem-extend-btn" onclick="extendDays(30)">+1 Month</button>
            <button type="button" class="mem-extend-btn" onclick="extendDays(90)">+3 Months</button>
            <button type="button" class="mem-extend-btn" onclick="extendDays(365)">+1 Year</button>
        </div>
        <input class="input" type="date" id="mem-editor-enddate" style="margin-top:10px;" />

        <button type="button" class="admin-save-btn" style="margin-top:20px;" onclick="saveMembership()">💾 SAVE MEMBERSHIP</button>
    </div>
</div>
