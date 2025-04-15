<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config/file_storage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['escalate'])) {
    $grievance_id = (int)$_POST['grievance_id'];
    $grievances = readData(GRIEVANCES_FILE);
    $found = false;

    foreach ($grievances as &$g) {
        if ($g['id'] === $grievance_id && $g['user_id'] === $_SESSION['user_id'] && $g['status'] === 'pending') {
            if (!isset($g['escalated']) || !$g['escalated']) {
                $g['escalated'] = true;
                $g['escalation_date'] = date('Y-m-d H:i:s');
                addNotification($_SESSION['user_id'], "Grievance #$grievance_id has been escalated.");
                $found = true;
            }
            break;
        }
    }

    if ($found) {
        writeData(GRIEVANCES_FILE, $grievances);
        $_SESSION['success'] = "Grievance escalated successfully.";
    } else {
        $_SESSION['error'] = "Unable to escalate grievance. It may already be escalated or invalid.";
    }
}

header("Location: index.php");
exit();
?>