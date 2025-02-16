<?php

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "DELETE FROM tb_lokasi WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            $execute = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($execute) {
                $_SESSION['success'] = "Data berhasil dihapus";
            } else {
                $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "Kesalahan dalam persiapan query.";
        }
    } else {
        $_SESSION['error'] = "ID tidak valid!";
    }
} else {
    $_SESSION['error'] = "ID tidak ditemukan!";
}

header("Location: ./?route=lokasi");
exit;
