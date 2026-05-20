<?php

function get_all_users($conn) {
    $sql = "SELECT * FROM users WHERE role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["employee"]);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $users; // always array
}


function insert_user($conn, $data) {
    $sql = "INSERT INTO users (full_name, username, password, role)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function update_user($conn, $data) {
    $sql = "UPDATE users
            SET full_name = ?, username = ?, password = ?, role = ?
            WHERE id = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function delete_user($conn, $data) {
    $sql = "DELETE FROM users WHERE id = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function get_user_by_id($conn, $id) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}


function update_profile($conn, $data) {
    $sql = "UPDATE users SET full_name = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function count_users($conn) {
    $sql = "SELECT COUNT(*) FROM users WHERE role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["employee"]);

    return $stmt->fetchColumn();
}

?>