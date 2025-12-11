<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$dataset_id = $_GET['id'] ?? null;

if ($dataset_id) {
    $del = pg_query_params($koneksi, "DELETE FROM dataset WHERE dataset_id = $1 AND uploader_by = $2", [$dataset_id, $user_id]);
    if ($del) {
        header('Location: dataset.php?msg=deleted');
        exit;
    } else {
        header('Location: dataset.php?msg=error');
        exit;
    }
} else {
    header('Location: dataset.php?msg=error');
    exit;
}
