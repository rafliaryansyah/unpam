// Display current date
const todayEl = document.getElementById('today');
if (todayEl) {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    todayEl.textContent = now.toLocaleDateString('id-ID', options);
}

// Background music toggle
const musicToggle = document.getElementById('musicToggle');
const bgm = document.getElementById('bgm');

if (musicToggle && bgm) {
    musicToggle.addEventListener('click', () => {
        if (bgm.paused) {
            bgm.play();
            bgm.muted = false;
            musicToggle.textContent = 'Pause Music';
        } else {
            bgm.pause();
            musicToggle.textContent = 'Play Music';
        }
    });
}

// Search function
function doSearch(event) {
    event.preventDefault();
    const query = document.getElementById('q').value;
    if (query.trim()) {
        alert('Pencarian untuk: ' + query + '\n(Fitur demo saja)');
    }
    return false;
}

