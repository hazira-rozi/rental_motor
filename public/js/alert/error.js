document.addEventListener("DOMContentLoaded", function () {
    const errorMessage = document.body.getAttribute("data-error-message");
    Swal.fire({
        icon: "error",
        title: "Gagal",
        text: errorMessage,
        timer: 2500,
        showConfirmButton: false,
    });
});
