<?php

function insert_task($conn, $data) {
    $sql = "INSERT INTO tasks (title, description, assigned_to, due_date, priority) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function get_all_tasks($conn) {
    $sql = "SELECT * FROM tasks ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return ($stmt->rowCount() > 0) ? $stmt->fetchAll() : 0;
}


function get_all_tasks_due_today($conn) {
    $sql = "SELECT * FROM tasks WHERE due_date = CURDATE() ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return ($stmt->rowCount() > 0) ? $stmt->fetchAll() : 0;
}

function count_tasks_due_today($conn) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE due_date = CURDATE()";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


function get_all_tasks_overdue($conn) {
    $sql = "SELECT * FROM tasks 
            WHERE due_date IS NOT NULL 
            AND due_date != '0000-00-00'
            AND due_date < CURDATE() 
            AND status != 'completed'
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return ($stmt->rowCount() > 0) ? $stmt->fetchAll() : 0;
}

function count_tasks_overdue($conn) {
    $sql = "SELECT COUNT(*) FROM tasks 
            WHERE due_date IS NOT NULL 
            AND due_date != '0000-00-00'
            AND due_date < CURDATE() 
            AND status != 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


function get_all_tasks_no_deadline($conn) {
    $sql = "SELECT * FROM tasks 
            WHERE due_date IS NULL OR due_date = '0000-00-00' 
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return ($stmt->rowCount() > 0) ? $stmt->fetchAll() : 0;
}

function count_tasks_no_deadline($conn) {
    $sql = "SELECT COUNT(*) FROM tasks 
            WHERE due_date IS NULL OR due_date = '0000-00-00'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


function get_all_tasks_by_id($conn, $id) {
    $sql = "SELECT * FROM tasks WHERE assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return ($stmt->rowCount() > 0) ? $stmt->fetchAll() : 0;
}


function get_task_by_id($conn, $id) {
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return ($stmt->rowCount() > 0) ? $stmt->fetch() : 0;
}


function count_tasks($conn) {
    $sql = "SELECT COUNT(*) FROM tasks";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


function update_task($conn, $data) {
    $sql = "UPDATE tasks 
            SET title = ?, description = ?, assigned_to = ?, due_date = ?, priority = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function update_task_status($conn, $data) {
    $sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function update_task_status_and_file($conn, $id, $status, $file_path) {
    $sql = "UPDATE tasks SET status = ?, file_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$status, $file_path, $id]);
}


function delete_task($conn, $data) {
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}


function count_my_tasks($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}


function count_my_tasks_overdue($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks 
            WHERE assigned_to = ?
            AND due_date IS NOT NULL 
            AND due_date != '0000-00-00'
            AND due_date < CURDATE() 
            AND status != 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}


function count_my_tasks_no_deadline($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks 
            WHERE assigned_to = ? 
            AND (due_date IS NULL OR due_date = '0000-00-00')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}


function count_pending_tasks($conn) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function count_in_progress_tasks($conn) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE status = 'in_progress'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function count_completed_tasks($conn) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE status = 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


function count_my_pending_tasks($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE assigned_to = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}

function count_my_in_progress_tasks($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE assigned_to = ? AND status = 'in_progress'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}

function count_my_completed_tasks($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE assigned_to = ? AND status = 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}
?>