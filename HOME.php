<?php
session_start();
require_once __DIR__ . '/config/db.php';

$content = [];
$res = $conn->query("SELECT content_key, content_value FROM home_content");
while ($row = $res->fetch_assoc()) {
    $content[$row['content_key']] = $row['content_value'];
}

// Real member count from DB
$count_res = $conn->query("SELECT COUNT(*) AS cnt FROM users WHERE role='member'");
$real_member_count = $count_res ? (int)$count_res->fetch_assoc()['cnt'] : 0;

// Real active membership count
$active_res = $conn->query("SELECT COUNT(*) AS cnt FROM memberships WHERE status='active'");
$real_active_count = $active_res ? (int)$active_res->fetch_assoc()['cnt'] : 0;

$is_admin  = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$is_logged = isset($_SESSION['user_id']);
$user_plan = $_SESSION['plan'] ?? 'Free';
$days      = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
$today     = strtolower(date('l'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojo's | Home</title>
    <link rel="icon" type="image/x-icon" href="Assets/image/icon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bg.css">
    <link rel="stylesheet" href="assets/css/inup.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/membership.css">
</head>
<body>
<?php include 'bg.php'; ?>
<?php $page = "HOME"; include 'navbar.php'; ?>

<?php if (!empty($content['announcement']) && ($user_plan === 'Free' || !$is_logged)): ?>
<div class="announcement-banner" id="announcement-banner">
    <span><?php echo htmlspecialchars($content['announcement']); ?></span>
    <button class="dismiss-btn" onclick="document.getElementById('announcement-banner').style.display='none'">✕</button>
</div>
<?php endif; ?>

<div class="card-container">

    <!-- CARD 1: GYM HOURS -->
    <div class="main-card">
        <p class="card-title">Gym Availability</p>
        <div class="today-datetime">
            <span><?php echo date('l, F j, Y'); ?></span>
            <span><?php echo date('g:i A'); ?></span>
        </div>
        <menu class="card-menu">
            <?php foreach ($days as $day):
                $status    = $content['status_'.$day] ?? 'open';
                $hours     = htmlspecialchars($content['hours_'.$day] ?? '');
                $is_closed = $status === 'closed';
                $is_today  = $day === $today;
                $cls       = $is_today ? 'hours-li today-row' : 'hours-li other-day';
                if ($is_closed) $cls .= ' day-closed';
            ?>
            <li class="<?php echo $cls; ?>">
                <span class="hours-day-label">
                    <?php if ($is_today): ?>
                        <span class="today-indicator">▶</span>
                    <?php else: ?>
                        <span class="status-dot"><?php echo $is_closed ? '🔴' : '🟢'; ?></span>
                    <?php endif; ?>
                    <?php echo ucfirst($day); ?>
                    <?php if ($is_today): ?><span class="today-tag">TODAY</span><?php endif; ?>
                </span>
                <span class="hours-value">
                    <?php echo $is_closed ? '<span class="closed-label">CLOSED</span>' : $hours; ?>
                </span>
            </li>
            <?php endforeach; ?>
        </menu>
    </div>

    <!-- CARD 2: ACTIVE MEMBERS -->
    <div class="main-card" style="justify-content:space-between;">
        <p class="card-title">Active Members</p>

        <div style="text-align:center; flex:1; display:flex; flex-direction:column; justify-content:center;">
            <p class="stat-number"><?php echo $real_member_count; ?></p>
            <p class="stat-label">Minions Enlisted</p>
        </div>


    </div>

    <!-- CARD 3: 30-DAY WORKOUT PLAN -->
    <?php
    $plan_map = ['Premium'=>true,'VIP'=>true,'Quarterly'=>true,'Annual'=>true];
    $can_workout = isset($plan_map[$user_plan]) && $is_logged;
    ?>
    <div class="main-card wplan-card">
        <p class="card-title">30-Day Plan</p>

        <?php if (!$is_logged): ?>
        <div class="wplan-locked">
            <span class="wplan-lock-icon">LOCKED</span>
            <p>Sign in to access the 30-Day Workout Plan.</p>
            <a href="#" id="open-signin-btn-wplan" class="wplan-upgrade-btn">SIGN IN</a>
        </div>

        <?php elseif (!$can_workout): ?>
        <div class="wplan-locked">
            <span class="wplan-lock-icon">👑</span>
            <p>The 30-Day Plan is exclusive to <strong>Premium</strong> and <strong>VIP</strong> members.</p>
            <a href="plans.php" class="wplan-upgrade-btn">UPGRADE NOW</a>
        </div>

        <?php else: ?>
        <!-- DAY SELECTOR -->
        <div class="wplan-header">
            <span class="wplan-badge">ACTIVE</span>
            <div class="wplan-nav">
                <button class="wplan-nav-btn" id="wplan-prev">‹</button>
                <span class="wplan-week-label" id="wplan-week-label">Week 1</span>
                <button class="wplan-nav-btn" id="wplan-next">›</button>
            </div>
        </div>

        <!-- DAY GRID -->
        <div class="wplan-grid" id="wplan-grid"></div>

        <!-- SELECTED DAY DETAIL -->
        <div class="wplan-detail" id="wplan-detail">
            <span class="wplan-day-num" id="wplan-day-num">DAY 1</span>
            <span class="wplan-day-type" id="wplan-day-type"></span>
            <p class="wplan-day-desc" id="wplan-day-desc"></p>
            <div class="wplan-exercises" id="wplan-exercises"></div>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php if (!$is_admin): ?>
<div class="home-actions">
    <a href="plans.php" class="btn-outline-purple">MEMBERSHIP PLANS</a>
    <?php if ($is_logged): ?>
    <a href="dashboard.php" class="btn-outline-green">MY DASHBOARD</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($is_admin): ?>
<div class="home-actions">
    <button class="btn-outline-purple" onclick="document.getElementById('admin-panel').classList.toggle('show')">EDIT HOMEPAGE</button>
</div>
<?php include 'admin_panel.php'; ?>
<?php endif; ?>

<?php include 'Signin.php'; ?>
<script>
const SITE_ROOT = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\').'/'; ?>";

// ── 30-DAY WORKOUT PLAN ──
(function() {
    var plan = [
        // WEEK 1
        { day:1,  type:'PUSH',     color:'#92ff77', desc:'Chest & Triceps', exercises:['4×12 Push-ups','3×10 Dumbbell Press','3×12 Tricep Dips','3×15 Shoulder Press'] },
        { day:2,  type:'PULL',     color:'#cfb2ff', desc:'Back & Biceps',   exercises:['4×10 Pull-ups','3×12 Bent-over Row','3×15 Bicep Curls','3×12 Face Pulls'] },
        { day:3,  type:'LEGS',     color:'#ffd700', desc:'Quads & Glutes',  exercises:['4×12 Squats','3×10 Lunges','3×15 Leg Press','3×20 Calf Raises'] },
        { day:4,  type:'REST',     color:'#555',    desc:'Active Recovery', exercises:['20 min walk','10 min stretching','Foam rolling','Hydrate well'] },
        { day:5,  type:'PUSH',     color:'#92ff77', desc:'Shoulders & Chest',exercises:['4×10 OHP','3×12 Lateral Raises','3×15 Push-ups','3×10 Arnold Press'] },
        { day:6,  type:'PULL',     color:'#cfb2ff', desc:'Back & Arms',     exercises:['4×10 Rows','3×12 Hammer Curls','3×15 Lat Pulldown','3×10 Shrugs'] },
        { day:7,  type:'REST',     color:'#555',    desc:'Full Rest Day',   exercises:['Sleep 8 hours','Light stretching','No gym today','Eat clean'] },
        // WEEK 2
        { day:8,  type:'LEGS',     color:'#ffd700', desc:'Hamstrings & Core',exercises:['4×12 Deadlifts','3×15 Leg Curls','3×20 Glute Bridges','3×30s Planks'] },
        { day:9,  type:'PUSH',     color:'#92ff77', desc:'Chest Power',     exercises:['5×5 Bench Press','4×10 Incline Press','3×12 Cable Fly','3×15 Dips'] },
        { day:10, type:'PULL',     color:'#cfb2ff', desc:'Back Width',      exercises:['4×8 Weighted Pull-ups','3×12 T-bar Row','3×15 Seated Row','3×10 Pullover'] },
        { day:11, type:'CARDIO',   color:'#ff9f45', desc:'Conditioning',    exercises:['20 min HIIT','3×1min Burpees','3×30 Jump Squats','10 min cool-down'] },
        { day:12, type:'LEGS',     color:'#ffd700', desc:'Explosive Legs',  exercises:['4×10 Jump Squats','3×12 Bulgarian Split Squat','3×20 Step-ups','3×15 Hip Thrust'] },
        { day:13, type:'PUSH',     color:'#92ff77', desc:'Tricep Focus',    exercises:['4×12 Skull Crushers','3×15 Tricep Pushdown','3×10 Close-grip Bench','3×12 Dips'] },
        { day:14, type:'REST',     color:'#555',    desc:'Full Rest Day',   exercises:['Sleep 8 hours','Light stretching','No gym today','Eat clean'] },
        // WEEK 3
        { day:15, type:'PULL',     color:'#cfb2ff', desc:'Bicep Focus',     exercises:['4×12 EZ Bar Curl','3×15 Cable Curl','3×10 Preacher Curl','3×12 Reverse Curl'] },
        { day:16, type:'LEGS',     color:'#ffd700', desc:'Full Leg Day',    exercises:['5×5 Squats','4×10 RDL','3×15 Leg Extension','3×20 Calf Raises'] },
        { day:17, type:'PUSH',     color:'#92ff77', desc:'Shoulder Strength',exercises:['5×5 OHP','4×12 Upright Row','3×15 Front Raise','3×12 Rear Delt Fly'] },
        { day:18, type:'CARDIO',   color:'#ff9f45', desc:'Endurance Run',   exercises:['30 min steady run','5 min cool-down','Core circuit 10 min','Stretch 5 min'] },
        { day:19, type:'PULL',     color:'#cfb2ff', desc:'Back Thickness',  exercises:['5×5 Deadlift','4×10 Pendlay Row','3×12 Meadows Row','3×15 Cable Row'] },
        { day:20, type:'PUSH',     color:'#92ff77', desc:'Chest & Shoulders',exercises:['4×10 Flat Bench','3×12 Incline DB','3×15 Cable Crossover','3×10 Pike Push-up'] },
        { day:21, type:'REST',     color:'#555',    desc:'Full Rest Day',   exercises:['Sleep 8 hours','Light stretching','No gym today','Eat clean'] },
        // WEEK 4 — PEAK WEEK
        { day:22, type:'LEGS',     color:'#ffd700', desc:'Peak Legs',       exercises:['5×5 Squats','4×8 Pause Squats','3×12 Hack Squat','3×20 Jump Squats'] },
        { day:23, type:'PUSH',     color:'#92ff77', desc:'Peak Push',       exercises:['5×5 Bench','4×8 DB Press','3×12 Weighted Dips','3×15 Push-up Burnout'] },
        { day:24, type:'PULL',     color:'#cfb2ff', desc:'Peak Pull',       exercises:['5×5 Deadlift','4×8 Weighted Rows','3×12 Pull-up Burnout','3×15 Shrugs'] },
        { day:25, type:'CARDIO',   color:'#ff9f45', desc:'Peak Cardio',     exercises:['5 rounds HIIT','3×1min Battle Ropes','3×20 Box Jumps','10 min cool-down'] },
        { day:26, type:'LEGS',     color:'#ffd700', desc:'Leg Finisher',    exercises:['4×12 Front Squat','3×10 Pistol Squat','3×15 Leg Press','3×20 Hip Thrust'] },
        { day:27, type:'PUSH',     color:'#92ff77', desc:'Shoulder Finisher',exercises:['4×10 Arnold Press','3×12 Lateral Raise','3×15 Face Pull','3×12 Rear Delt'] },
        { day:28, type:'REST',     color:'#555',    desc:'Final Rest',      exercises:['Sleep 9 hours','Full body stretch','Prep for final days','Eat big & clean'] },
        // DAYS 29–30 — FINALE
        { day:29, type:'FULL BODY',color:'#ff6b6b', desc:'Total Body Blast',exercises:['3×10 Squats','3×10 Bench','3×10 Deadlift','3×10 OHP','3×10 Row'] },
        { day:30, type:'FINALE',   color:'#9757ff', desc:'Day 30 — Complete!',exercises:['1×MAX Push-ups','1×MAX Pull-ups','1×MAX Squats','Celebrate. You made it.'] },
    ];

    var STORAGE_KEY = 'mojo_wplan_day';
    var currentWeek = 0;
    var selectedDay = parseInt(localStorage.getItem(STORAGE_KEY) || '1');

    var grid       = document.getElementById('wplan-grid');
    var detail     = document.getElementById('wplan-detail');
    var weekLabel  = document.getElementById('wplan-week-label');
    var prevBtn    = document.getElementById('wplan-prev');
    var nextBtn    = document.getElementById('wplan-next');

    if (!grid) return; // Not logged in / free plan

    function renderWeek(week) {
        currentWeek = Math.max(0, Math.min(3, week));
        weekLabel.textContent = 'Week ' + (currentWeek + 1);
        prevBtn.disabled = currentWeek === 0;
        nextBtn.disabled = currentWeek === 3;

        grid.innerHTML = '';
        var start = currentWeek * 7;
        for (var i = start; i < start + 7; i++) {
            var d    = plan[i];
            var btn  = document.createElement('button');
            btn.className = 'wplan-day-btn' + (d.day === selectedDay ? ' selected' : '') + (d.type === 'REST' ? ' rest' : '');
            btn.dataset.day = d.day;
            btn.innerHTML = '<span class="wplan-btn-num">' + d.day + '</span><span class="wplan-btn-type" style="color:' + d.color + '">' + d.type + '</span>';
            btn.addEventListener('click', function() {
                selectedDay = parseInt(this.dataset.day);
                localStorage.setItem(STORAGE_KEY, selectedDay);
                renderWeek(currentWeek);
                showDetail(selectedDay - 1);
            });
            grid.appendChild(btn);
        }

        // Show detail of selected day if it's in this week
        var selInWeek = selectedDay >= start + 1 && selectedDay <= start + 7;
        if (selInWeek) {
            showDetail(selectedDay - 1);
        } else {
            showDetail(start); // default to first day of week
        }
    }

    function showDetail(idx) {
        var d = plan[idx];
        document.getElementById('wplan-day-num').textContent  = 'DAY ' + d.day;
        document.getElementById('wplan-day-type').textContent = d.type;
        document.getElementById('wplan-day-type').style.color = d.color;
        document.getElementById('wplan-day-type').style.borderColor = d.color;
        document.getElementById('wplan-day-desc').textContent = d.desc;
        var ex = document.getElementById('wplan-exercises');
        ex.innerHTML = d.exercises.map(function(e) {
            return '<div class="wplan-ex-item">› ' + e + '</div>';
        }).join('');
    }

    prevBtn.addEventListener('click', function() { renderWeek(currentWeek - 1); });
    nextBtn.addEventListener('click', function() { renderWeek(currentWeek + 1); });

    // Start on the week containing the selected day
    var startWeek = Math.floor((selectedDay - 1) / 7);
    renderWeek(startWeek);
})();
</script>
<script src="assets/js/signin.js"></script>
<script src="assets/js/admin.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
