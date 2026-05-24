<?php

function get_all_my_notifications($conn, $id) {
    $sql = "SELECT 
                n.*,
                t.priority
            FROM notifications n
            LEFT JOIN tasks t 
                ON n.message LIKE '%' + t.title + '%'
            WHERE n.recipient = ?
            ORDER BY n.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function count_notification($conn, $id){
    $sql = "SELECT COUNT(*) 
            FROM notifications 
            WHERE recipient = ? AND is_read = 0";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetchColumn();
}

function insert_notification($conn, $data){
    $sql = "INSERT INTO notifications 
            (message, recipient, type, is_read, created_at)
            VALUES (?, ?, ?, 0, GETDATE())";

    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);
}

function notification_make_read($conn, $recipient_id, $notification_id){
    $sql = "UPDATE notifications 
            SET is_read = 1 
            WHERE id = ? AND recipient = ?";

    $stmt = $conn->prepare($sql);
    return $stmt->execute([$notification_id, $recipient_id]);
}
?>