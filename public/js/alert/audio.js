document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-form").forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // cegah submit default

            Swal.fire({
                title: "Hapus audio?",
                text: "Audio nonaktif akan dihapus permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form jika dikonfirmasi
                }
            });
        });
    });
});
