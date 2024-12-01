<?php
session_start();
if (isset($_SESSION['log'])) {
    echo "Sesi aktif. Anda login.";
} else {
    echo "Sesi tidak aktif. Silakan login.";
}
?>
