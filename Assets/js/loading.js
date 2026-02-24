// Listen for the Get Started button click
const startBtn = document.getElementById('start-btn');
const loaderOverlay = document.getElementById('loader-overlay');

if (startBtn) {
    startBtn.addEventListener('click', function(e) {
        // 1. Stop the link from jumping to HOME.php immediately
        e.preventDefault(); 
        
        // 2. Fade in the loading screen
        loaderOverlay.classList.add('active');
        
        // 3. Wait 1.5 seconds, then go to HOME.php
        setTimeout(function() {
            window.location.href = "HOME.php";
        }, 1500); 
    });
}

// Fix the "Back Button" bug
window.addEventListener('pageshow', function(event) {
    // If the browser loads the page from its cache, hide the loader
    if (event.persisted && loaderOverlay) {
        loaderOverlay.classList.remove('active');
    }
});