<?php
include 'db_connect.php';

// Lấy danh sách bài hát từ database
try {
    $stmt = $pdo->query("
        SELECT s.song_id, s.title, a.artist_name, s.file_path, s.cover_path
        FROM songs s 
        LEFT JOIN artists a ON s.artist_id = a.artist_id
    ");
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $jsonSongs = json_encode($songs);
} catch (PDOException $e) {
    die("Lỗi khi lấy dữ liệu: " . $e->getMessage());
}

// Lấy bài hát đầu tiên để mặc định
$firstSong = !empty($songs) ? $songs[0] : [
    'title' => 'No Song Available',
    'artist_name' => 'Unknown Artist',
    'file_path' => 'https://ia600607.us.archive.org/13/items/amv-fade-v-utopia-m-4-a-128-k/%5BACID%5D%20-%20LIMITLESS%20MEP%28M4A_128K%29.m4a', // Mặc định bài đầu tiên nếu không có dữ liệu
    'cover_path' => 'https://via.placeholder.com/100'
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify Clone</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Spotify Clone</h2>
            <ul>
                <li>Home</li>
                <li>Search</li>
                <li>Your Library</li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="player">
                <div class="song-info">
                    <img src="<?php echo htmlspecialchars($firstSong['cover_path']); ?>" alt="Album Cover" id="album-cover">
                    <div>
                        <h3 id="song-title"><?php echo htmlspecialchars($firstSong['title']); ?></h3>
                        <p id="artist"><?php echo htmlspecialchars($firstSong['artist_name']); ?></p>
                    </div>
                </div>
                
                <div class="controls">
                    <button id="prev">⏮</button>
                    <button id="play-pause">▶️</button>
                    <button id="next">⏭</button>
                </div>
                
                <div class="progress">
                    <input type="range" id="progress-bar" value="0" min="0" max="100">
                </div>
            </div>
            
            <!-- Playlist -->
            <div class="playlist">
                <h2>Playlist</h2>
                <div class="song-list" id="song-list">
                    <?php
                    if (!empty($songs)) {
                        foreach ($songs as $index => $song) {
                            echo '<div class="song-item" data-index="' . $index . '" data-src="' . htmlspecialchars($song['file_path']) . '">';
                            echo htmlspecialchars($song['title']) . ' - ' . htmlspecialchars($song['artist_name']);
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="song-item">No songs available.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Phát nhạc -->
    <audio id="audio-player" src="<?php echo htmlspecialchars($firstSong['file_path']); ?>"></audio>


    <!-- Truyền danh sách bài hát sang JavaScript -->
    <script>
        const songs = <?php echo $jsonSongs; ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>
