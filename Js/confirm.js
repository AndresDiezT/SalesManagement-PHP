document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-button").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            const url = this.getAttribute("data-url");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "No podrás revertir esta acción.",
                showCancelButton: true,
                confirmButtonColor: "#D81B60",
                cancelButtonColor: "#AD1457",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                background: "#3D2A40",
                color: "#F8B4C6",
                imageUrl: 'https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExcnAycGdnZDk1dXRxMHM4Y2o4cmMyZmZneWphZHR2NTM2M2gwenNlbiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/E8qjvPiKigjMI1KPME/giphy.gif',
                imageWidth: 180,
                imageHeight: 180,
                imageAlt: 'GIF de mensaje importante',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});