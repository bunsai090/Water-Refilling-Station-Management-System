<?php
// Update profile functionality
require_once '../../config/db.php';
require_once '../../config/auth_check.php';

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $admin_id = $_SESSION['admin_id'];
    
    // Validate required fields
    if (empty($data['full_name']) || empty($data['username']) || empty($data['email'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Full name, username, and email are required'
        ]);
        exit;
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Check if username is already taken by another user
        $checkStmt = $pdo->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
        $checkStmt->execute([$data['username'], $admin_id]);
        if ($checkStmt->fetch()) {
            throw new Exception('Username is already taken');
        }
        
        // Check if email is already taken by another user
        $checkStmt = $pdo->prepare("SELECT id FROM admins WHERE email = ? AND id != ?");
        $checkStmt->execute([$data['email'], $admin_id]);
        if ($checkStmt->fetch()) {
            throw new Exception('Email is already taken');
        }
        
        // Update basic profile info
        $stmt = $pdo->prepare("
            UPDATE admins 
            SET full_name = ?, username = ?, email = ?, phone = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['full_name'],
            $data['username'],
            $data['email'],
            $data['phone'] ?? null,
            $admin_id
        ]);
        
        // Update password if provided
        if (!empty($data['new_password'])) {
            // Verify current password
            $verifyStmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
            $verifyStmt->execute([$admin_id]);
            $admin = $verifyStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!password_verify($data['current_password'], $admin['password'])) {
                throw new Exception('Current password is incorrect');
            }
            
            // Update with new password
            $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
            $pwdStmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $pwdStmt->execute([$hashedPassword, $admin_id]);
        }
        
        // Update session name
        $_SESSION['admin_name'] = $data['full_name'];
        
        // Commit transaction
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
