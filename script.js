const audioPlayer = document.getElementById('audio-player');
const playPauseBtn = document.getElementById('play-pause');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');
const progressBar = document.getElementById('progress-bar');
const songTitle = document.getElementById('song-title');
const artist = document.getElementById('artist');
const albumCover = document.getElementById('album-cover');
const songItems = document.querySelectorAll('.song-item');

let currentSongIndex = 0;
let isPlaying = false;

// Load thông tin bài hát
function loadSong() {
    const song = songs[currentSongIndex];
    songTitle.textContent = song.title;
    artist.textContent = song.artist_name || 'Unknown Artist';
    albumCover.src = song.cover_path || 'https://via.placeholder.com/100';
    audioPlayer.src = song.file_path; // Link MP3 từ Archive.org
}

// Play/pause song
function togglePlayPause() {
    if (isPlaying) {
        audioPlayer.pause();
        playPauseBtn.textContent = '▶️';
        isPlaying = false;
    } else {
        audioPlayer.play();
        playPauseBtn.textContent = '⏸';
        isPlaying = true;
    }
}

// Next song
function nextSong() {
    currentSongIndex = (currentSongIndex + 1) % songs.length;
    loadSong();
    playSong();
}

// Previous song
function prevSong() {
    currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
    loadSong();
    playSong();
}

// Play song
function playSong() {
    audioPlayer.play();
    playPauseBtn.textContent = '⏸';
    isPlaying = true;
}

// Update progress bar
audioPlayer.addEventListener('timeupdate', () => {
    const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
    progressBar.value = progress || 0;
});

// Seek song
progressBar.addEventListener('input', () => {
    const seekTime = (progressBar.value / 100) * audioPlayer.duration;
    audioPlayer.currentTime = seekTime;
});

// Event listeners
playPauseBtn.addEventListener('click', togglePlayPause);
nextBtn.addEventListener('click', nextSong);
prevBtn.addEventListener('click', prevSong);

// Click vào bài hát để phát
songItems.forEach((item, index) => {
    item.addEventListener('click', () => {
        currentSongIndex = index;
        loadSong();
        playSong();
    });
});
