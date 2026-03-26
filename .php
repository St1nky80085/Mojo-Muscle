<?php
// CSS Verification Tool - delete after use
$cssDir = __DIR__ . '/assets/css/';
$files  = ['style.css', 'inup.css', 'home.css', 'profile.css', 'membership.css', 'contact.css', 'about.css', 'admin.css'];

echo '<style>body{font-family:monospace;background:#0d0d0d;color:#e0e0e0;padding:20px;} .ok{color:#92ff77;} .fail{color:#ff4d4d;} .info{color:#cfb2ff;} pre{background:#111;border:1px solid #2a0a5e;padding:12px;border-radius:6px;overflow:auto;font-size:12px;}</style>';
echo '<h2 style="color:#cfb2ff;letter-spacing:3px;">CSS CHECK</h2>';

$allGood = true;
foreach ($files as $f) {
    $path = $cssDir . $f;
    if (!file_exists($path)) {
        echo "<p class='fail'>$f — NOT FOUND at $path</p>";
        $allGood = false;
        continue;
    }
    $content = file_get_contents($path);
    $size    = round(filesize($path)/1024, 1);
    $glass   = strpos($content, 'backdrop-filter') !== false ? '<span class="ok">YES</span>' : '<span class="fail">NO</span>';
    echo "<p><span class='info'>$f</span> ({$size}KB) | glassmorphism: $glass</p>";
}

echo '<hr style="border-color:#2a0a5e;margin:20px 0;">';
echo '<p class="ok">NOTE: modal-overlay only needs display:none in ONE css file (style.css or inup.css). Having it in both is fine. Not having it in home.css is CORRECT.</p>';

// Show inup.css first 30 lines
$inup = file_get_contents($cssDir . 'inup.css');
echo '<p class="info">inup.css first 20 lines:</p>';
$lines = array_slice(explode("\n", $inup), 0, 20);
echo '<pre>' . htmlspecialchars(implode("\n", $lines)) . '</pre>';
?>
