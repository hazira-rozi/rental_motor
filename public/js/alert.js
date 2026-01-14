// flash_alert.js
document.addEventListener("DOMContentLoaded", function () {
    if (!window.flashData) return;

    const { success, error, errors } = window.flashData;

    if (success) {
        Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: success,
            timer: 2500,
            showConfirmButton: false,
        });
    }

    if (error) {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: error,
            confirmButtonText: "OK",
        });
    }

    if (errors && errors.length > 0) {
        const html =
            '<ul style="text-align:left;">' +
            errors.map((err) => `<li>${err}</li>`).join("") +
            "</ul>";

        Swal.fire({
            icon: "error",
            title: "Validasi Gagal",
            html: html,
            confirmButtonText: "OK",
        });
    }

});

// --- 2. LOGIKA LOGOUT (Event Delegation) ---
    // Gunakan document.addEventListener agar tombol tetap terdeteksi di halaman manapun
    document.addEventListener("click", function (e) {
        // Cek apakah yang diklik adalah elemen dengan class .btn-logout atau di dalam elemen tersebut
        const btn = e.target.closest(".btn-logout");
        
        if (btn) {
            e.preventDefault(); // Menghentikan link agar tidak langsung pindah/home
            
            Swal.fire({
                title: "Yakin untuk keluar?",
                text: "Pilih Logout untuk mengakhiri sesi.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#4e73df",
                cancelButtonColor: "#e6370bff",
                confirmButtonText: "Logout",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById("logout-form");
                    if (form) {
                        form.submit();
                    } else {
                        // Jika form tidak ketemu, kita buat form darurat lewat JS
                        const tempForm = document.createElement('form');
                        tempForm.method = 'POST';
                        tempForm.action = '/logout'; // Sesuaikan dengan route logout Anda
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.content || "";
                        
                        tempForm.appendChild(csrfInput);
                        document.body.appendChild(tempForm);
                        tempForm.submit();
                    }
                }
            });
        }
    });



