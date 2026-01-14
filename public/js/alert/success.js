document.addEventListener("DOMContentLoaded", function () {
    const successMessage = document.body.getAttribute("data-success-message");
    Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: successMessage,
        timer: 2500,
        showConfirmButton: false,
    });
});
