// --- SIGN IN POP-UP LOGIC --- //
const openModalBtn = document.getElementById('open-signin-btn'); // The button you click to open
const signinModal = document.getElementById('signin-modal');
const closeModalBtn = document.getElementById('close-modal');

// Open the modal
if (openModalBtn && signinModal) {
    openModalBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Stops the link from changing pages
        signinModal.classList.add('show');
    });
}

// Close the modal when clicking the 'X'
if (closeModalBtn) {
    closeModalBtn.addEventListener('click', function() {
        signinModal.classList.remove('show');
    });
}

// Close the modal if the user clicks the dark background outside the form
window.addEventListener('click', function(e) {
    if (e.target === signinModal) {
        signinModal.classList.remove('show');
    }
});