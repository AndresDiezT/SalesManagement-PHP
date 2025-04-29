document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-button").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            const url = this.getAttribute("data-url");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "No podrás revertir esta acción.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#D81B60",
                cancelButtonColor: "#AD1457",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                background: "#3D2A40",
                color: "#F8B4C6"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});

console.log(document.getElementById("modalTax"));
console.log(document.getElementById("modalSaleType"));

function openModal(modalId) {
    console.log("cargado correctamente", modalId);
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

window.onclick = function (event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
}