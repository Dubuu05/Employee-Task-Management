<?php

function get_all_users($conn) {
    $sql = "SELECT * FROM users WHERE role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["employee"]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/* =========================
   INSERT USER (SAFE VERSION)
   ========================= */
function insert_user($conn, $data) {
    try {
        $sql = "INSERT INTO users (full_name, username, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);

    } catch (PDOException $e) {
        return false;
    }
}


/* UPDATE USER */
function update_user($conn, $data) {
    $sql = "UPDATE users
            SET full_name = ?, username = ?, password = ?, role = ?
            WHERE id = ? AND role = ?";

    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}


/* DELETE USER */
function delete_user($conn, $data) {
    $sql = "DELETE FROM users WHERE id = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}


/* GET USER */
function get_user_by_id($conn, $id) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}


/* UPDATE PROFILE */
function update_profile($conn, $data) {
    $sql = "UPDATE users SET full_name = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}


/* COUNT USERS */
function count_users($conn) {
    $sql = "SELECT COUNT(*) FROM users WHERE role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["employee"]);

    return $stmt->fetchColumn();
}


/* =========================
   CHECK DUPLICATE USERNAME
   ========================= */
function account_exists($conn, $username) {
    $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);

    return $stmt->fetchColumn() > 0;
}
?>