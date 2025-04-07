<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["success_message"]) || isset($_SESSION["error_message"])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {";

    if (isset($_SESSION["success_message"])) {
        echo "Swal.fire({
                title: 'Exito!',
                text: '" . addslashes($_SESSION["success_message"]) . "',
                confirmButtonColor: '#4CAF50',
                background: '#1E1E2F',
                color: '#C8E6C9',
                iconColor: '#C8E6C9',
                imageUrl: 'https://media.giphy.com/media/j0HjChGV0J44KrrlGv/giphy.gif',
                imageWidth: 160,
                imageHeight: 160,
                imageAlt: 'GIF de éxito',
                timer: 5000,
                timerProgressBar: true,
            });";
        unset($_SESSION["success_message"]);
    }

    if (isset($_SESSION["error_message"])) {
        echo "Swal.fire({
                title: 'Error!',
                text: '" . $_SESSION["error_message"] . "',
                icon: 'error',
                confirmButtonColor: '#EF5350',
                background: '#1E1E2F',
                color: '#FFCDD2',
                iconColor: '#EF5350',
                imageUrl: 'https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExMTFpdXJkbHEwZHc5eDVzcWF2NmNrbzdjd2kzbHAzZGtyMXZ1NzV1ZCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/CoND5j6Bn1QZUgm1xX/giphy.gif',
                imageWidth: 160,
                imageHeight: 160,
                imageAlt: 'GIF de error',
                timer: 5000,
                timerProgressBar: true,
            });";
        unset($_SESSION["error_message"]);
    }

    echo "});
    </script>";
}
?>