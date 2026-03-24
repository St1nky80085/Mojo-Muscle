var startBtn      = document.getElementById('start-btn');
var loaderOverlay = document.getElementById('loader-overlay');

if (startBtn) {
    startBtn.addEventListener('click', function(e) {
        e.preventDefault();
        loaderOverlay.classList.add('active');
        setTimeout(function() {
            window.location.href = 'HOME.php';
        }, 1200);
    });
}

// Remove loader if user hits back button (browser cache)
window.addEventListener('pageshow', function(e) {
    if (e.persisted && loaderOverlay) {
        loaderOverlay.classList.remove('active');
    }
});
