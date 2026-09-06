<?php
/**
 * REST API Controller for Enhanced Eco Clean Game
 * Handles Access Key, Leaderboard, Educational Content, & 5-Level Eco Adventure Progress
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/api.php';

handleCorsHeaders();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Parse JSON payload if sent via application/json
$rawInput = file_get_contents('php://input');
$jsonInput = json_decode($rawInput, true) ?? [];

$db = getDBConnection();

switch ($action) {
    case 'validate_key':
        $keyCode = trim($jsonInput['key_code'] ?? $_POST['key_code'] ?? '');
        if (empty($keyCode)) {
            sendJsonResponse(['success' => false, 'message' => 'Kunci akses tidak boleh kosong!'], 400);
        }

        if (!$db) {
            sendJsonResponse([
                'success' => true,
                'message' => 'Kunci Akses valid (Mode Offline)!',
                'data' => [
                    'key_code' => strtoupper($keyCode),
                    'student_name' => 'Pahlawan Eco ' . substr(md5($keyCode), 0, 4),
                    'school_class' => 'Kelas 5 Eco'
                ]
            ]);
        }

        $stmt = $db->prepare("SELECT * FROM access_keys WHERE key_code = :key");
        $stmt->execute([':key' => strtoupper($keyCode)]);
        $user = $stmt->fetch();

        if ($user) {
            $upd = $db->prepare("UPDATE access_keys SET last_active = NOW() WHERE id = :id");
            $upd->execute([':id' => $user['id']]);

            sendJsonResponse([
                'success' => true,
                'message' => 'Kunci Akses Valid!',
                'data' => [
                    'key_code' => $user['key_code'],
                    'student_name' => $user['student_name'],
                    'school_class' => $user['school_class'],
                    'last_active' => $user['last_active']
                ]
            ]);
        } else {
            sendJsonResponse([
                'success' => false,
                'message' => 'Kunci Akses "' . htmlspecialchars($keyCode) . '" tidak terdaftar! Buat kunci baru di bawah.'
            ], 404);
        }
        break;

    case 'generate_key':
        $studentName = trim($jsonInput['student_name'] ?? $_POST['student_name'] ?? '');
        $schoolClass = trim($jsonInput['school_class'] ?? $_POST['school_class'] ?? 'Kelas 5 Eco');

        if (empty($studentName)) {
            sendJsonResponse(['success' => false, 'message' => 'Nama Siswa wajib diisi!'], 400);
        }

        $randomCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $newKey = "ECO-" . $randomCode;

        if ($db) {
            $stmt = $db->prepare("INSERT INTO access_keys (key_code, student_name, school_class) VALUES (:key, :name, :class)");
            $stmt->execute([
                ':key' => $newKey,
                ':name' => $studentName,
                ':class' => $schoolClass
            ]);
        }

        sendJsonResponse([
            'success' => true,
            'message' => 'Kunci Akses berhasil dibuat!',
            'data' => [
                'key_code' => $newKey,
                'student_name' => $studentName,
                'school_class' => $schoolClass
            ]
        ]);
        break;

    // ─────────────────────────────────────────────────────────────────────────
    // GET LEADERBOARD  — gabungkan skor dari leaderboard (game pilah) +
    //                    adventure_progress (level 1–5) per pemain
    // ─────────────────────────────────────────────────────────────────────────
    // ─────────────────────────────────────────────────────────────────────────
    // GET LEADERBOARD  — gabungkan skor dari leaderboard (game pilah) +
    //                    adventure_progress (level 1–5) per pemain
    // ─────────────────────────────────────────────────────────────────────────
    case 'get_leaderboard':
        $search = trim($_GET['search'] ?? '');

        if (!$db) {
            sendJsonResponse([
                'success' => true,
                'data' => []
            ]);
        }

        // Auto-register missing keys from leaderboard & adventure_progress into access_keys table
        $db->exec("
            INSERT IGNORE INTO access_keys (key_code, student_name, school_class)
            SELECT DISTINCT access_key, COALESCE(NULLIF(student_name, ''), 'Pahlawan Eco'), COALESCE(NULLIF(school_class, ''), 'Kelas 5 Eco')
            FROM leaderboard WHERE access_key IS NOT NULL AND access_key != ''
        ");
        $db->exec("
            INSERT IGNORE INTO access_keys (key_code, student_name, school_class)
            SELECT DISTINCT access_key, 'Pahlawan Eco', 'Kelas 5 Eco'
            FROM adventure_progress WHERE access_key IS NOT NULL AND access_key != ''
        ");

        // Ambil skor gabungan per access_key:
        // game_score   = skor terbaik dari game pilah sampah
        // level_score  = total skor dari level adventure 1-5
        // total_score  = gabungan keduanya
        $sql = "
            SELECT
                all_keys.key_code,
                COALESCE(NULLIF(ak.student_name, ''), gs.student_name, 'Pahlawan Eco') AS student_name,
                COALESCE(NULLIF(ak.school_class, ''), gs.school_class, 'Kelas 5 Eco') AS school_class,
                COALESCE(gs.game_score, 0)  AS game_score,
                COALESCE(lp.level_score, 0) AS level_score,
                (COALESCE(gs.game_score, 0) + COALESCE(lp.level_score, 0)) AS total_score,
                COALESCE(gs.waste_sorted, 0) AS waste_sorted,
                COALESCE(lp.levels_done, 0)  AS levels_done,
                COALESCE(gs.max_combo, 0)     AS max_combo
            FROM (
                SELECT key_code FROM access_keys
                UNION
                SELECT access_key AS key_code FROM leaderboard
                UNION
                SELECT access_key AS key_code FROM adventure_progress
            ) all_keys
            LEFT JOIN access_keys ak ON ak.key_code = all_keys.key_code
            LEFT JOIN (
                SELECT access_key,
                       MAX(student_name) AS student_name,
                       MAX(school_class) AS school_class,
                       MAX(score) AS game_score,
                       MAX(waste_sorted) AS waste_sorted,
                       MAX(max_combo) AS max_combo
                FROM leaderboard
                WHERE waste_sorted > 0 OR score > 0
                GROUP BY access_key
            ) gs ON gs.access_key = all_keys.key_code
            LEFT JOIN (
                SELECT access_key,
                       SUM(high_score) AS level_score,
                       COUNT(DISTINCT level_number) AS levels_done
                FROM adventure_progress
                WHERE is_completed = 1
                GROUP BY access_key
            ) lp ON lp.access_key = all_keys.key_code
            WHERE (COALESCE(gs.game_score,0) + COALESCE(lp.level_score,0)) > 0
        ";

        $params = [];
        if (!empty($search)) {
            $sql .= " AND (ak.student_name LIKE :search OR ak.school_class LIKE :search OR ak.key_code LIKE :search OR gs.student_name LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY total_score DESC, levels_done DESC LIMIT 50";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        // Hitung badge & rank
        $rank = 1;
        foreach ($rows as &$row) {
            $row['rank'] = $rank++;
            $ts = intval($row['total_score']);
            if ($ts >= 3000)       $row['badge_title'] = 'Grand Eco Champion 🥇';
            elseif ($ts >= 2000)   $row['badge_title'] = 'Master Eco Hero 🥈';
            elseif ($ts >= 1000)   $row['badge_title'] = 'Pahlawan Lingkungan 🥉';
            elseif ($ts >= 500)    $row['badge_title'] = 'Penjaga Bumi 🌟';
            else                   $row['badge_title'] = 'Sahabat Lingkungan 🌱';
        }

        sendJsonResponse([
            'success' => true,
            'count' => count($rows),
            'data' => $rows
        ]);
        break;

    // ─────────────────────────────────────────────────────────────────────────
    // SAVE SCORE (game pilah sampah)
    // ─────────────────────────────────────────────────────────────────────────
    case 'save_score':
        $accessKey   = trim($jsonInput['access_key']   ?? $_POST['access_key']   ?? 'ECO-GUEST');
        $studentName = trim($jsonInput['student_name'] ?? $_POST['student_name'] ?? 'Pahlawan Eco');
        $schoolClass = trim($jsonInput['school_class'] ?? $_POST['school_class'] ?? 'Kelas 5 Eco');
        $score       = intval($jsonInput['score']       ?? $_POST['score']       ?? 0);
        $wasteSorted = intval($jsonInput['waste_sorted'] ?? $_POST['waste_sorted'] ?? 0);
        $maxCombo    = intval($jsonInput['max_combo']   ?? $_POST['max_combo']   ?? 0);

        if ($score >= 1400) { $badge = 'Grand Eco Champion 🥇'; }
        elseif ($score >= 1000) { $badge = 'Master Pilah Daur Ulang 🥈'; }
        elseif ($score >= 700)  { $badge = 'Pahlawan Organik 🥉'; }
        elseif ($score >= 400)  { $badge = 'Penjaga Bumi 🌟'; }
        else                    { $badge = 'Sahabat Lingkungan 🌱'; }

        $userRank = 1;
        $totalScore = $score;

        if ($db) {
            // Auto-insert/update access_keys record
            $akStmt = $db->prepare("
                INSERT INTO access_keys (key_code, student_name, school_class)
                VALUES (:key, :name, :class)
                ON DUPLICATE KEY UPDATE
                    student_name = IF(student_name = '' OR student_name IS NULL OR student_name = 'Pahlawan Eco', VALUES(student_name), student_name),
                    school_class = IF(school_class = '' OR school_class IS NULL, VALUES(school_class), school_class),
                    last_active = NOW()
            ");
            $akStmt->execute([':key' => $accessKey, ':name' => $studentName, ':class' => $schoolClass]);

            // Insert score record
            $stmt = $db->prepare("INSERT INTO leaderboard (student_name, school_class, access_key, score, waste_sorted, max_combo, badge_title)
                                  VALUES (:name, :class, :key, :score, :waste, :combo, :badge)");
            $stmt->execute([
                ':name'  => $studentName,
                ':class' => $schoolClass,
                ':key'   => $accessKey,
                ':score' => $score,
                ':waste' => $wasteSorted,
                ':combo' => $maxCombo,
                ':badge' => $badge
            ]);

            // Hitung total skor gabungan (Game Pilah + Levels 1-5)
            $totStmt = $db->prepare("
                SELECT (COALESCE(gs.game_score, 0) + COALESCE(lp.level_score, 0)) AS total_score
                FROM (SELECT MAX(score) AS game_score FROM leaderboard WHERE access_key = :k1) gs,
                     (SELECT SUM(high_score) AS level_score FROM adventure_progress WHERE access_key = :k2 AND is_completed = 1) lp
            ");
            $totStmt->execute([':k1' => $accessKey, ':k2' => $accessKey]);
            $totRow = $totStmt->fetch();
            if ($totRow && intval($totRow['total_score']) > 0) {
                $totalScore = intval($totRow['total_score']);
            }
        }

        sendJsonResponse([
            'success' => true,
            'message' => 'Skor berhasil disimpan dan digabungkan di Klasemen!',
            'data' => [
                'game_score'   => $score,
                'total_score'  => $totalScore,
                'waste_sorted' => $wasteSorted,
                'max_combo'    => $maxCombo,
                'badge'        => $badge
            ]
        ]);
        break;

    // ─────────────────────────────────────────────────────────────────────────
    // GET MAP PROGRESS
    // ─────────────────────────────────────────────────────────────────────────
    case 'get_map_progress':
        $accessKey = trim($_GET['access_key'] ?? $jsonInput['access_key'] ?? 'ECO-GUEST');

        $levelsProgress = [];
        for ($i = 1; $i <= 5; $i++) {
            $levelsProgress[$i] = [
                'level_number' => $i,
                'unlocked'     => ($i === 1),
                'completed'    => false,
                'stars'        => 0,
                'high_score'   => 0
            ];
        }

        if ($db) {
            $stmt = $db->prepare("SELECT * FROM adventure_progress WHERE access_key = :key");
            $stmt->execute([':key' => $accessKey]);
            $rows = $stmt->fetchAll();

            foreach ($rows as $row) {
                $lvl = intval($row['level_number']);
                if ($lvl >= 1 && $lvl <= 5) {
                    $levelsProgress[$lvl]['completed']  = (bool)$row['is_completed'];
                    $levelsProgress[$lvl]['stars']      = intval($row['stars_earned']);
                    $levelsProgress[$lvl]['high_score'] = intval($row['high_score']);

                    if ($row['is_completed'] && $lvl < 5) {
                        $levelsProgress[$lvl + 1]['unlocked'] = true;
                    }
                }
            }
        }

        sendJsonResponse([
            'success'    => true,
            'access_key' => $accessKey,
            'levels'     => array_values($levelsProgress)
        ]);
        break;

    // ─────────────────────────────────────────────────────────────────────────
    // SAVE LEVEL RESULT  — simpan progress DAN update leaderboard
    // ─────────────────────────────────────────────────────────────────────────
    case 'save_level_result':
        $accessKey   = trim($jsonInput['access_key']   ?? $_POST['access_key']   ?? 'ECO-GUEST');
        $levelNumber = intval($jsonInput['level_number'] ?? $_POST['level_number'] ?? 1);
        $stars       = intval($jsonInput['stars_earned'] ?? $_POST['stars_earned'] ?? 1);
        $score       = intval($jsonInput['score']        ?? $_POST['score']        ?? 100);

        if ($db) {
            // 1. Simpan / update adventure_progress
            $stmt = $db->prepare("
                INSERT INTO adventure_progress (access_key, level_number, stars_earned, high_score, is_completed)
                VALUES (:key, :lvl, :stars, :score, 1)
                ON DUPLICATE KEY UPDATE
                    stars_earned  = GREATEST(stars_earned, VALUES(stars_earned)),
                    high_score    = GREATEST(high_score, VALUES(high_score)),
                    is_completed  = 1
            ");
            $stmt->execute([
                ':key'   => $accessKey,
                ':lvl'   => $levelNumber,
                ':stars' => $stars,
                ':score' => $score
            ]);

            // 2. Ambil data pemain dari access_keys
            $userStmt = $db->prepare("SELECT student_name, school_class FROM access_keys WHERE key_code = :key");
            $userStmt->execute([':key' => $accessKey]);
            $userInfo = $userStmt->fetch();

            if ($userInfo) {
                // 3. Hitung total skor gabungan semua level + game
                $totStmt = $db->prepare("
                    SELECT
                        COALESCE(gs.game_score, 0)  AS game_score,
                        COALESCE(lp.level_score, 0) AS level_score
                    FROM access_keys ak
                    LEFT JOIN (SELECT access_key, MAX(score) AS game_score FROM leaderboard WHERE access_key = :key1 GROUP BY access_key) gs ON gs.access_key = ak.key_code
                    LEFT JOIN (SELECT access_key, SUM(high_score) AS level_score FROM adventure_progress WHERE access_key = :key2 GROUP BY access_key) lp ON lp.access_key = ak.key_code
                    WHERE ak.key_code = :key3
                ");
                $totStmt->execute([':key1' => $accessKey, ':key2' => $accessKey, ':key3' => $accessKey]);
                $totRow = $totStmt->fetch();

                $totalScore = intval($totRow['game_score'] ?? 0) + intval($totRow['level_score'] ?? 0);

                // 4. Badge berdasarkan total skor
                if ($totalScore >= 3000)       $badge = 'Grand Eco Champion 🥇';
                elseif ($totalScore >= 2000)   $badge = 'Master Eco Hero 🥈';
                elseif ($totalScore >= 1000)   $badge = 'Pahlawan Lingkungan 🥉';
                elseif ($totalScore >= 500)    $badge = 'Penjaga Bumi 🌟';
                else                           $badge = 'Sahabat Lingkungan 🌱';

                // 5. Simpan/update leaderboard (1 row per access_key untuk level-score)
                //    Gunakan kolom badge_title sebagai marker 'level_record'
                $lbChk = $db->prepare("SELECT id FROM leaderboard WHERE access_key = :key AND badge_title LIKE '%Eco%' AND waste_sorted = 0 LIMIT 1");
                $lbChk->execute([':key' => $accessKey]);
                $existing = $lbChk->fetch();

                if ($existing) {
                    // Update existing level-record row
                    $lbUpd = $db->prepare("
                        UPDATE leaderboard
                        SET score = (SELECT COALESCE(SUM(high_score),0) FROM adventure_progress WHERE access_key = :key),
                            badge_title = :badge,
                            student_name = :name,
                            school_class = :class
                        WHERE id = :id
                    ");
                    $lbUpd->execute([
                        ':key'   => $accessKey,
                        ':badge' => $badge,
                        ':name'  => $userInfo['student_name'],
                        ':class' => $userInfo['school_class'],
                        ':id'    => $existing['id']
                    ]);
                } else {
                    // Insert new level-record row
                    $lbIns = $db->prepare("
                        INSERT INTO leaderboard (access_key, student_name, school_class, score, waste_sorted, max_combo, badge_title)
                        SELECT :key, :name, :class,
                               COALESCE(SUM(high_score), 0), 0, 0, :badge
                        FROM adventure_progress WHERE access_key = :key2
                    ");
                    $lbIns->execute([
                        ':key'   => $accessKey,
                        ':name'  => $userInfo['student_name'],
                        ':class' => $userInfo['school_class'],
                        ':badge' => $badge,
                        ':key2'  => $accessKey
                    ]);
                }
            }
        }

        sendJsonResponse([
            'success' => true,
            'message' => "Level $levelNumber Selesai! Kamu meraih $stars Bintang ⭐!",
            'data' => [
                'level_number'         => $levelNumber,
                'stars_earned'         => $stars,
                'score'                => $score,
                'next_level_unlocked'  => min(5, $levelNumber + 1)
            ]
        ]);
        break;

    case 'get_materials':
        if (!$db) {
            sendJsonResponse(['success' => false, 'message' => 'Database tidak terhubung!'], 500);
        }

        $stmt = $db->query("SELECT * FROM educational_materials ORDER BY id ASC");
        $materials = $stmt->fetchAll();

        foreach ($materials as &$item) {
            if (is_string($item['quiz_options'])) {
                $item['quiz_options'] = json_decode($item['quiz_options'], true);
            }
        }

        sendJsonResponse([
            'success' => true,
            'data' => $materials
        ]);
        break;

    case 'submit_quiz':
        $materialId  = intval($jsonInput['material_id']  ?? $_POST['material_id']  ?? 0);
        $answerIndex = intval($jsonInput['answer_index']  ?? $_POST['answer_index']  ?? -1);

        if (!$db) {
            sendJsonResponse(['success' => false, 'message' => 'Koneksi database gagal'], 500);
        }

        $stmt = $db->prepare("SELECT * FROM educational_materials WHERE id = :id");
        $stmt->execute([':id' => $materialId]);
        $item = $stmt->fetch();

        if (!$item) {
            sendJsonResponse(['success' => false, 'message' => 'Materi tidak ditemukan!'], 404);
        }

        $isCorrect = ($answerIndex === intval($item['quiz_correct_index']));

        sendJsonResponse([
            'success'       => true,
            'is_correct'    => $isCorrect,
            'correct_index' => intval($item['quiz_correct_index']),
            'message'       => $isCorrect
                ? 'Hebat! Jawaban kamu benar (+100 Bonus Poin).'
                : 'Kurang tepat! Coba pelajari lagi materinya ya.'
        ]);
        break;

    default:
        sendJsonResponse([
            'success'   => true,
            'app_name'  => APP_NAME,
            'version'   => APP_VERSION,
            'endpoints' => [
                'POST ?action=validate_key',
                'POST ?action=generate_key',
                'GET  ?action=get_leaderboard',
                'POST ?action=save_score',
                'GET  ?action=get_map_progress',
                'POST ?action=save_level_result',
                'GET  ?action=get_materials',
                'POST ?action=submit_quiz'
            ]
        ]);
        break;
}
