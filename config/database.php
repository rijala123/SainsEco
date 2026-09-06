<?php
/**
 * Database Connection Configuration
 * Compatible with phpMyAdmin Laragon & cPanel Web Hosting
 */

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'db_eco_clean');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

function getDBConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            @$pdo->exec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            ensureDatabaseTablesCreated($pdo);
        } catch (PDOException $e) {
            // Attempt auto-create database if running locally
            try {
                $rawPdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT, DB_USER, DB_PASS);
                $rawPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                @$pdo->exec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
                ensureDatabaseTablesCreated($pdo);
            } catch (Exception $ex) {
                // Return null if connection fails, API will report friendly error
                return null;
            }
        }
    }
    return $pdo;
}

function ensureDatabaseTablesCreated($pdo) {
    if (!$pdo) return;
    try {
        // 1. Table access_keys
        $pdo->exec("CREATE TABLE IF NOT EXISTS access_keys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            key_code VARCHAR(50) UNIQUE NOT NULL,
            student_name VARCHAR(100) NOT NULL,
            school_class VARCHAR(100) DEFAULT 'Kelas 5 Eco',
            last_active DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 2. Table leaderboard
        $pdo->exec("CREATE TABLE IF NOT EXISTS leaderboard (
            id INT AUTO_INCREMENT PRIMARY KEY,
            access_key VARCHAR(50) NOT NULL,
            student_name VARCHAR(100) NOT NULL,
            school_class VARCHAR(100) DEFAULT 'Kelas 5 Eco',
            score INT NOT NULL DEFAULT 0,
            waste_sorted INT NOT NULL DEFAULT 0,
            max_combo INT NOT NULL DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 3. Table adventure_progress
        $pdo->exec("CREATE TABLE IF NOT EXISTS adventure_progress (
            id INT AUTO_INCREMENT PRIMARY KEY,
            access_key VARCHAR(50) NOT NULL,
            level_number INT NOT NULL,
            score INT NOT NULL DEFAULT 0,
            stars INT NOT NULL DEFAULT 0,
            completed TINYINT(1) DEFAULT 1,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY key_level (access_key, level_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 4. Table teacher_settings
        $pdo->exec("CREATE TABLE IF NOT EXISTS teacher_settings (
            setting_key VARCHAR(50) PRIMARY KEY,
            setting_value TEXT NOT NULL,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Default password if missing
        $stmt = $pdo->query("SELECT COUNT(*) FROM teacher_settings WHERE setting_key = 'password'");
        if ($stmt->fetchColumn() == 0) {
            $pdo->prepare("INSERT INTO teacher_settings (setting_key, setting_value) VALUES ('password', :pass)")
                ->execute([':pass' => password_hash('guru123', PASSWORD_DEFAULT)]);
        }

        // 5. Table waste_items
        $pdo->exec("CREATE TABLE IF NOT EXISTS waste_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            category ENUM('organik', 'anorganik', 'b3', 'kertas', 'botol') NOT NULL,
            icon VARCHAR(50) DEFAULT '🗑️',
            points INT DEFAULT 10,
            fact VARCHAR(255) DEFAULT '',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Seed waste_items if empty
        $stmtWaste = $pdo->query("SELECT COUNT(*) FROM waste_items");
        if ($stmtWaste->fetchColumn() == 0) {
            $initialWaste = [
                // Organik
                ['Sisa Apel', 'organik', '🍎', 10, 'Sisa apel bisa menjadi kompos kaya nutrisi tanah!'],
                ['Kulit Pisang', 'organik', '🍌', 10, 'Kulit pisang kaya kalium untuk menutrisi tanaman.'],
                ['Daun Kering', 'organik', '🍂', 10, 'Daun kering terurai cepat oleh mikroorganisme tanah.'],
                ['Cangkang Telur', 'organik', '🥚', 10, 'Cangkang telur mengandung kalsium tinggi untuk pupuk.'],
                ['Sisa Ikan', 'organik', '🐟', 10, 'Limbah sisa makanan harus segera jadi kompos!'],
                ['Sisa Nasi', 'organik', '🍚', 10, 'Nasi sisa adalah sumber nitrogen bagi tanah.'],
                ['Kulit Jeruk', 'organik', '🍊', 10, 'Kulit jeruk mengandung minyak alami penolak serangga.'],
                ['Ampas Kopi', 'organik', '☕', 10, 'Ampas kopi adalah pupuk nitrogen alami yang bagus.'],
                ['Biji Buah', 'organik', '🌱', 10, 'Biji buah bisa tumbuh menjadi pohon baru!'],
                ['Kulit Kentang', 'organik', '🥔', 10, 'Kulit kentang terurai dan menyuburkan tanah.'],
                // Anorganik
                ['Botol Plastik', 'anorganik', '🍾', 15, 'Botol PET didaur ulang menjadi serat pakaian fleece!'],
                ['Kaleng Aluminium', 'anorganik', '🥫', 15, 'Daur ulang kaleng hemat 95% energi dibanding buat baru!'],
                ['Kardus Bekas', 'anorganik', '📦', 15, '1 ton kertas daur ulang menyelamatkan 17 pohon dewasa!'],
                ['Kantong Plastik', 'anorganik', '🛍️', 15, 'Kantong plastik butuh 500 tahun untuk hancur di TPA.'],
                ['Botol Kaca', 'anorganik', '🍷', 15, 'Kaca didaur ulang 100% tanpa penurunan kualitas!'],
                ['Koran Bekas', 'anorganik', '📰', 15, 'Kertas koran bisa didaur ulang menjadi kertas baru.'],
                ['Gelas Plastik', 'anorganik', '🥤', 15, 'Gelas plastik bisa diubah menjadi pot tanaman mini!'],
                ['Tutup Botol', 'anorganik', '🔵', 15, 'Tutup botol plastik dikumpulkan untuk daur ulang kreatif.'],
                // B3
                ['Baterai Bekas', 'b3', '🔋', 20, 'Baterai mengandung cadmium & merkuri beracun bagi air tanah.'],
                ['Lampu Neon', 'b3', '💡', 20, 'Lampu neon pecah mengeluarkan uap merkuri berbahaya.'],
                ['Botol Obat Serangga', 'b3', '🧪', 20, 'Pestisida residu harus diserahkan ke fasilitas B3.'],
                ['Obat Expired', 'b3', '💊', 20, 'Obat kedaluwarsa mencemari ekosistem sungai.']
            ];
            $insWaste = $pdo->prepare("INSERT INTO waste_items (name, category, icon, points, fact) VALUES (?, ?, ?, ?, ?)");
            foreach ($initialWaste as $item) {
                $insWaste->execute($item);
            }
        }

        // 6. Table game_levels
        $pdo->exec("CREATE TABLE IF NOT EXISTS game_levels (
            id INT AUTO_INCREMENT PRIMARY KEY,
            level_number INT UNIQUE NOT NULL,
            title VARCHAR(255) NOT NULL,
            subtitle VARCHAR(255) DEFAULT '',
            game_type ENUM('matching', 'multichoice', 'filter', 'sequence', 'decision') DEFAULT 'multichoice',
            icon VARCHAR(50) DEFAULT '🧩',
            bg_color VARCHAR(50) DEFAULT '#22C55E',
            illustration VARCHAR(255) DEFAULT '',
            instructions TEXT DEFAULT NULL,
            content_json LONGTEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Seed initial levels 1-5 if game_levels is empty
        $stmtLvl = $pdo->query("SELECT COUNT(*) FROM game_levels");
        if ($stmtLvl->fetchColumn() == 0) {
            $initialLevels = [
                // Level 1: Matching
                [
                    'level_number' => 1,
                    'title' => 'Level 1: Pengenalan Pola',
                    'subtitle' => 'Cocokkan gambar masalah dengan penyebabnya!',
                    'game_type' => 'matching',
                    'icon' => '🧩',
                    'bg_color' => '#22C55E',
                    'illustration' => 'assets/images/level1_bg.png',
                    'instructions' => 'Perhatikan gambar pencemaran sungai di atas, lalu klik 1 Masalah (Kiri) & Penyebab yang cocok (Kanan)!',
                    'content_json' => json_encode([
                        'pairs' => [
                            ['id' => 1, 'prob' => '🚯 Laut Tercemar Sampah Plastik', 'cause' => '🛍️ Konsumsi Plastik Sekali Pakai Berlebihan'],
                            ['id' => 2, 'prob' => '😷 Polusi Asam Kabut Udara Kota', 'cause' => '🏭 Asap Pabrik & Kendaraan Tanpa Filter'],
                            ['id' => 3, 'prob' => '🐟 Ikan Mati Massal di Sungai', 'cause' => '🧪 Pembuangan Limbah B3 Kimia Beracun'],
                            ['id' => 4, 'prob' => '🪵 Bencana Hutan Gundul & Banjir', 'cause' => '🪓 Penebangan Pohon Liar (Deforestasi)']
                        ]
                    ], JSON_UNESCAPED_UNICODE)
                ],

                // Level 2: Multi-choice (Banjir & Sekolah Kotor)
                [
                    'level_number' => 2,
                    'title' => 'Level 2: Memecahkan Masalah',
                    'subtitle' => 'Analisis & temukan penyebab utama masalah lingkungan!',
                    'game_type' => 'multichoice',
                    'icon' => '🗂️',
                    'bg_color' => '#0284C7',
                    'illustration' => 'assets/images/level2_flood.png',
                    'instructions' => 'Pilih jawaban yang tepat tentang penyebab masalah lingkungan!',
                    'content_json' => json_encode([
                        'questions' => [
                            [
                                'id' => 1,
                                'topic' => 'PERMASALAHAN 1:',
                                'question' => '🌊 Apa Penyebab Terjadinya Banjir Besar?',
                                'illustration' => 'assets/images/level2_flood.png',
                                'subtitle' => 'Pilih jawaban yang tepat (Klik tombol pilihan untuk memilih, ada 4 jawaban yang benar)!',
                                'options' => [
                                    ['id' => '1', 'text' => 'Air tidak bisa mengalir (Pipa & selokan tersumbat)', 'correct' => true],
                                    ['id' => '2', 'text' => 'Air meluap karena tidak punya tempat masuk tanah', 'correct' => true],
                                    ['id' => '3', 'text' => 'Hujan deras tanpa penahan', 'correct' => true],
                                    ['id' => '4', 'text' => 'Tanah tidak menyerap air', 'correct' => true],
                                    ['id' => 'wrong1', 'text' => 'Banyak pohon rimbun menyerap air hujan', 'correct' => false]
                                ],
                                'feedback_correct' => "🎉 Sempurna! Semua penyebab benar!\n💡 Dampaknya:\n• Air tidak bisa mengalir → banjir besar! 🌊😱\n• Air meluap karena tidak punya tempat masuk tanah 💦\n• Hujan deras tanpa penahan → air membludak 💧\n• Tanah tidak menyerap air → air langsung mengalir 🏞️",
                                'feedback_wrong' => "⚠️ Ada pilihan yang belum pas! Ingat, banjir terjadi saat air tidak terserap & terhambat mengalir."
                            ],
                            [
                                'id' => 2,
                                'topic' => 'PERMASALAHAN 2:',
                                'question' => '🏫 Mengapa Sekolah Jadi Kotor & Bau?',
                                'illustration' => 'assets/images/level2_school.png',
                                'subtitle' => 'Pilih jawaban yang tepat tentang penyebab sekolah kotor!',
                                'options' => [
                                    ['id' => '1', 'text' => 'Siswa bingung tempat menyimpan sampah, akhirnya berserakan di mana-mana 🤷', 'correct' => true],
                                    ['id' => '2', 'text' => 'Sampah tidak dikumpulkan & dibersihkan dengan baik 🧹❌', 'correct' => true],
                                    ['id' => '3', 'text' => 'Sisa makanan menarik lalat & kuman 🍴🦠', 'correct' => true],
                                    ['id' => 'wrong1', 'text' => 'Siswa rajin piket dan membuang sampah ke tempat sampah', 'correct' => false]
                                ],
                                'feedback_correct' => "🎉 Sempurna! Semua penyebab benar!\n💡 Dampaknya:\n• Sampah berserakan, sekolah jadi kotor & bau 🗑️😫\n• Siswa bingung tempat menyimpan sampah, akhirnya berserakan 🤷\n• Sampah tidak dikumpulkan & dibersihkan dengan baik 🧹❌\n• Sisa makanan menarik lalat & kuman 🍴🦠",
                                'feedback_wrong' => "⚠️ Masih ada yang keliru! Sekolah kotor terjadi saat sampah tidak dikelola dengan benar."
                            ]
                        ]
                    ], JSON_UNESCAPED_UNICODE)
                ],

                // Level 3: Filter Solusi
                [
                    'level_number' => 3,
                    'title' => 'Level 3: Filter Solusi Inti',
                    'subtitle' => 'Atasi penumpukan sampah di sekolah dengan memilih solusi terbaik!',
                    'game_type' => 'filter',
                    'icon' => '🔍',
                    'bg_color' => '#F59E0B',
                    'illustration' => 'assets/images/level3_bg.png',
                    'instructions' => 'Filter & Pilih Solusi Paling Utama & Penting!',
                    'content_json' => json_encode([
                        'scenarios' => [
                            [
                                'id' => 1,
                                'title' => '🏫 Skenario 1: Penumpukan Sampah Plastik & Makanan di Kantin Sekolah',
                                'desc' => 'Setiap jam istirahat, sampah plastik bekas bungkus jajan dan sisa makanan menumpuk tinggi di tempat sampah sekolah sampai meluber.',
                                'options' => [
                                    ['text' => '🌱 Sediakan tempat sampah pilah (Organik & Plastik) & galakkan gerakan bawa tempat makan/tumbler sendiri', 'correct' => true, 'feedback' => '🎉 Tepat Sekali! Mengurangi dari sumbernya & memilah sampah adalah solusi paling penting & berdampak panjang!'],
                                    ['text' => '🔥 Membakar seluruh tumpukan sampah plastik di halaman sekolah setiap sore', 'correct' => false, 'feedback' => '⚠️ Salah! Membakar sampah menghasilkan asap beracun (dioksin) yang berbahaya bagi pernapasan anak sekolah!'],
                                    ['text' => '🗑️ Membiarkan sampah menumpuk dan menunggu tertiup angin', 'correct' => false, 'feedback' => '⚠️ Kurang tepat! Membiarkan sampah akan mengundang lalat, kecoa, dan menimbulkan bau menyengat!']
                                ]
                            ],
                            [
                                'id' => 2,
                                'title' => '🍂 Skenario 2: Penumpukan Daun Kering di Kebun Sekolah',
                                'desc' => 'Pohon-pohon di sekolah meluruhkan banyak daun kering hingga menumpuk tebal di halaman.',
                                'options' => [
                                    ['text' => '💨 Membuang seluruh daun kering ke dalam selokan saluran air', 'correct' => false, 'feedback' => '⚠️ Salah! Membuang daun ke selokan akan menyumbat saluran air dan menyebabkan banjir!'],
                                    ['text' => '🪴 Olah daun kering menjadi pupuk komposting organik untuk tanaman sekolah', 'correct' => true, 'feedback' => '🎉 Sempurna! Daun kering adalah bahan organik terbaik untuk nutrisi tanah kebun sekolah!'],
                                    ['text' => '🛍️ Membungkus daun kering dengan 100 kantong plastik lalu dibuang begitu saja', 'correct' => false, 'feedback' => '⚠️ Kurang tepat! Menggunakan banyak kantong plastik justru menambah pencemaran sampah plastik!']
                                ]
                            ],
                            [
                                'id' => 3,
                                'title' => '📦 Skenario 3: Penumpukan Kardus & Kertas Bekas di Ruang Kelas',
                                'desc' => 'Banyak kardus dan kertas tugas lama yang menumpuk tak terpakai di belakang kelas.',
                                'options' => [
                                    ['text' => '♻️ Kumpulkan kertas & kardus untuk disalurkan ke bank sampah / tempat daur ulang', 'correct' => true, 'feedback' => '🎉 Luar Biasa! Kertas dan kardus dapat didaur ulang 100% menjadi barang berguna baru!'],
                                    ['text' => '🌊 Membuang kertas ke dalam toilet atau sungai dekat sekolah', 'correct' => false, 'feedback' => '⚠️ Salah! Membuang kertas ke toilet menyumbat saluran pipa air!']
                                ]
                            ]
                        ]
                    ], JSON_UNESCAPED_UNICODE)
                ],

                // Level 4: Sequence
                [
                    'level_number' => 4,
                    'title' => 'Level 4: Urutan Langkah',
                    'subtitle' => 'Susun urutan penanganan sampah yang benar & rapi!',
                    'game_type' => 'sequence',
                    'icon' => '📋',
                    'bg_color' => '#7C3AED',
                    'illustration' => 'assets/images/level4_bg.png',
                    'instructions' => 'Pilih urutan langkah penanganan sampah yang paling tepat!',
                    'content_json' => json_encode([
                        'problem_title' => '📋 Masalah: Tempat sampah penuh dengan sampah plastik dan daun 🗑️🍂',
                        'options' => [
                            ['seq_id' => '1', 'badge' => 'A', 'text' => 'Pilah → Buang → Kumpulkan', 'correct' => false],
                            ['seq_id' => '2', 'badge' => 'B', 'text' => 'Kumpulkan → Pilah → Buang', 'correct' => true],
                            ['seq_id' => '3', 'badge' => 'C', 'text' => 'Kumpulkan → Buang → Pilah', 'correct' => false]
                        ],
                        'feedback_correct' => "🎉 Sempurna! Urutan langkah 100% tepat!\n💡 Alasan Urutan Yang Benar:\n1. 🧹 **Kumpulkan**: Kumpulkan seluruh sampah yang berserakan terlebih dahulu.\n2. ♻️ **Pilah**: Pisahkan sampah organik (daun) dan anorganik (plastik).\n3. 🗑️ **Buang**: Buang atau olah ke tempat penampungan yang sesuai!",
                        'feedback_wrong' => "⚠️ Urutan kurang tepat! Ingat: Kumpulkan semua sampah dulu, baru dipilah, kemudian dibuang sesuai jenisnya!"
                    ], JSON_UNESCAPED_UNICODE)
                ],

                // Level 5: Decision
                [
                    'level_number' => 5,
                    'title' => 'Level 5: Decision Game',
                    'subtitle' => 'Simulasi tindakan nyata pahlawan penyelamat bumi!',
                    'game_type' => 'decision',
                    'icon' => '🌟',
                    'bg_color' => '#EC4899',
                    'illustration' => 'assets/images/level5_bg.png',
                    'instructions' => 'Pilih Tindakan Nyata Yang Paling Berdampak Positif Bagi Bumi!',
                    'content_json' => json_encode([
                        'scenarios' => [
                            [
                                'id' => 1,
                                'title' => '🛒 Skenario 1: Belanja di Minimarket / Pasar',
                                'desc' => 'Saat kamu diajak berbelanja keperluan sekolah, kasir menawarkan kantong plastik sekali pakai. Apa tindakan nyatamu?',
                                'choices' => [
                                    ['text' => '👜 Mengeluarkan kantong kain ramah lingkungan sendiri dari tas', 'points' => 200, 'isBest' => true, 'msg' => '🎉 Sempurna! Kamu menghemat 1 sampah plastik dari laut dan melindungi hewan laut!'],
                                    ['text' => '🛍️ Menerima 3 kantong plastik sekali pakai gratis', 'points' => -50, 'isBest' => false, 'msg' => '⚠️ Kurang tepat! Kantong plastik sekali pakai butuh 500 tahun untuk hancur di alam.']
                                ]
                            ],
                            [
                                'id' => 2,
                                'title' => '🏫 Skenario 2: Minum Saat Jam Istirahat Sekolah',
                                'desc' => 'Kamu merasa haus setelah berolahraga di lapangan sekolah. Tindakan paling tepat yang kamu lakukan adalah...',
                                'choices' => [
                                    ['text' => '🧴 Minum dari Tumbler botol minum isi ulang sendiri', 'points' => 200, 'isBest' => true, 'msg' => '🎉 Pilihan sangat bijak! Kamu menghemat uang jajan & mengurangi tumpukan sampah botol plastik!'],
                                    ['text' => '🥤 Membeli 2 botol air kemasan plastik sekali pakai', 'points' => -50, 'isBest' => false, 'msg' => '⚠️ Kurang tepat! Botol plastik bekas minuman akan menumpuk di tempat sampah sekolah.']
                                ]
                            ],
                            [
                                'id' => 3,
                                'title' => '🍂 Skenario 3: Memeriksakan Sampah Daun di Halaman',
                                'desc' => 'Halaman sekolah penuh dengan guguran daun kering. Apa tindakan nyata terbaik?',
                                'choices' => [
                                    ['text' => '🌱 Kumpulkan daun dan masukkan ke komposter untuk dijadikan pupuk', 'points' => 200, 'isBest' => true, 'msg' => '🎉 Luar biasa! Pupuk kompos hasil olahan daun menutrisi kebun sekolah jadi subur!'],
                                    ['text' => '💨 Membakar tumpukan daun hingga berasap tebal', 'points' => -100, 'isBest' => false, 'msg' => '⚠️ Salah! Asap pembakaran daun mencemari udara & mengganggu pernapasan warga sekolah.']
                                ]
                            ]
                        ]
                    ], JSON_UNESCAPED_UNICODE)
                ]
            ];

            $insLvl = $pdo->prepare("INSERT INTO game_levels (level_number, title, subtitle, game_type, icon, bg_color, illustration, instructions, content_json) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($initialLevels as $lvl) {
                $insLvl->execute([
                    $lvl['level_number'],
                    $lvl['title'],
                    $lvl['subtitle'],
                    $lvl['game_type'],
                    $lvl['icon'],
                    $lvl['bg_color'],
                    $lvl['illustration'],
                    $lvl['instructions'],
                    $lvl['content_json']
                ]);
            }
        }

    } catch (Exception $e) {
        // Silently log or ignore migration errors
    }
}

