<?php

// =======================
// INSERT TASK
// =======================
function insert_task($conn, $data) {
    $sql = "INSERT INTO tasks
            (title, description, assigned_to, due_date, priority, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'pending', GETDATE())";

    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}


// =======================
// GET ALL TASKS
// =======================
function get_all_tasks($conn) {
    $sql = "SELECT * FROM tasks ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// =======================
// UPDATE STATUS (FIX ERROR MO)
// =======================
function update_task_status($conn, $data) {
    $sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}


// =======================
// DELETE TASK
// =======================
function delete_task($conn, $data) {
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}


// =======================
// TASK BY ID (FIX ERROR MO)
// =======================
function get_task_by_id($conn, $id) {
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// =======================
// DUE TODAY
// =======================
function count_tasks_due_today($conn) {
    $sql = "SELECT COUNT(*) FROM tasks
            WHERE due_date IS NOT NULL
            AND CAST(due_date AS date) = CAST(GETDATE() AS date)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


// =======================
// OVERDUE (FIXED MSSQL SAFE)
// =======================
function count_tasks_overdue($conn) {
    $sql = "SELECT COUNT(*) FROM tasks
            WHERE due_date IS NOT NULL
            AND status <> 'completed'
            AND CAST(due_date AS date) < CAST(GETDATE() AS date)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


// =======================
// NO DEADLINE
// =======================
function count_tasks_no_deadline($conn) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE due_date IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


// =======================
// ALL TASK COUNT
// =======================
function count_tasks($conn) {
    $sql = "SELECT COUNT(*) FROM tasks";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}


// =======================
// USER TASKS
// =======================
function count_my_tasks($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks WHERE assigned_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}


// =======================
// USER OVERDUE
// =======================
function count_my_tasks_overdue($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks
            WHERE assigned_to = ?
            AND due_date IS NOT NULL
            AND status <> 'completed'
            AND CAST(due_date AS date) < CAST(GETDATE() AS date)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}


// =======================
// USER NO DEADLINE
// =======================
function count_my_tasks_no_deadline($conn, $id) {
    $sql = "SELECT COUNT(*) FROM tasks
            WHERE assigned_to = ?
            AND due_date IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}


// =======================
// STATUS COUNTS
// =======================
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


// =======================
// TASK LISTS (FILTERS)
// =======================
function get_all_tasks_due_today($conn) {
    $sql = "SELECT * FROM tasks
            WHERE due_date IS NOT NULL
            AND CAST(due_date AS date) = CAST(GETDATE() AS date)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_tasks_overdue($conn) {
    $sql = "SELECT * FROM tasks
            WHERE due_date IS NOT NULL
            AND status <> 'completed'
            AND CAST(due_date AS date) < CAST(GETDATE() AS date)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_tasks_no_deadline($conn) {
    $sql = "SELECT * FROM tasks WHERE due_date IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_tasks_by_id($conn, $id) {
    $sql = "SELECT * FROM tasks WHERE assigned_to = ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>