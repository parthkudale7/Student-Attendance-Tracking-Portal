<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['role']) || !isset($data['descriptor'])) {
    echo json_encode(['success' => false, 'message' => 'Missing role or face descriptor.']);
    exit;
}

$role = $data['role'];
$input_descriptor = $data['descriptor'];

if ($role !== 'admin' && $role !== 'faculty') {
    echo json_encode(['success' => false, 'message' => 'Face Login is only available for Admin and Faculty.']);
    exit;
}

if (!is_array($input_descriptor) || count($input_descriptor) !== 128) {
    echo json_encode(['success' => false, 'message' => 'Invalid face descriptor format.']);
    exit;
}

function euclidean_distance($a, $b) {
    if (count($a) !== count($b)) return false;
    $sum = 0;
    for ($i = 0; $i < count($a); $i++) {
        $sum += pow($a[$i] - $b[$i], 2);
    }
    return sqrt($sum);
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = ? AND status = 'active' AND face_descriptor IS NOT NULL");
    $stmt->execute([$role]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        echo json_encode(['success' => false, 'message' => 'No registered faces found for this role.']);
        exit;
    }

    $best_match = null;
    $min_distance = 1.0; // max possible distance is larger, but 1.0 is a good starting upper bound for identical faces
    $THRESHOLD = 0.45; // Face-api.js typically uses 0.6 as default, but 0.45 is stricter for login

    foreach ($users as $user) {
        $db_descriptor = json_decode($user['face_descriptor'], true);
        if (is_array($db_descriptor) && count($db_descriptor) === 128) {
            $dist = euclidean_distance($input_descriptor, $db_descriptor);
            if ($dist !== false && $dist < $min_distance) {
                $min_distance = $dist;
                $best_match = $user;
            }
        }
    }

    if ($best_match && $min_distance < $THRESHOLD) {
        // Authentication successful
        session_start();
        $_SESSION['user_id'] = $best_match['id'];
        $_SESSION['name'] = $best_match['name'];
        $_SESSION['role'] = $best_match['role'];
        $_SESSION['email'] = $best_match['email'];

        echo json_encode([
            'success' => true,
            'message' => 'Face recognized successfully!',
            'redirect' => "../{$best_match['role']}/dashboard.php",
            'user' => [
                'id' => $best_match['id'],
                'name' => $best_match['name'],
                'role' => $best_match['role']
            ],
            'distance' => $min_distance
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Face not recognized. Please try again or use password.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}
?>
