$(document).ready(function () {
    /**
     * 1. Fungsi Import Question
     */
    window.showImportAlert = function (packageId) {
        Swal.fire({
            title: "Import Soal (Excel)",
            html: `Pilih file .xlsx<br><small class="text-danger">Sheet wajib: <b>listening, structure, reading</b></small>`,
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Upload & Import",
            confirmButtonColor: "#1cc88a",
            input: "file",
            inputAttributes: {
                accept: ".xlsx, .xls",
                "aria-label": "Upload excel",
            },
        }).then((result) => {
            if (result.value) {
                const formData = new FormData();
                formData.append("file", result.value);
                formData.append(
                    "_token",
                    $('meta[name="csrf-token"]').attr("content")
                );

                // Loading State
                Swal.fire({
                    title: "Mohon Tunggu",
                    text: "Sedang memproses import data...",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                $.ajax({
                    url: `/admin/packages/${packageId}/questions/import`,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire(
                            "Berhasil!",
                            response.message,
                            "success"
                        ).then(() => location.reload());
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON
                            ? xhr.responseJSON.message
                            : "Terjadi kesalahan sistem";
                        Swal.fire("Gagal!", msg, "error");
                    },
                });
            }
        });
    };

    /**
     * 2. Konfirmasi Hapus Data (Event Delegation)
     */
    $(document).on("click", ".btn-delete-trigger", function () {
        const id = $(this).data("id");
        const number = $(this).data("number");

        Swal.fire({
            title: "Hapus Soal Nomor " + number + "?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e74a3b",
            cancelButtonColor: "#858796",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById("delete-form-" + id);
                if (form) {
                    form.submit();
                } else {
                    console.error(
                        "Form delete tidak ditemukan untuk ID: " + id
                    );
                }
            }
        });
    });

    //Preview soal
    $(document).on("click", ".btn-preview-swal", function () {
        // Ambil ID langsung dari atribut data tombol
        const questionId = $(this).data("id");
        const packageId = $(this).data("package");

        if (!questionId || !packageId) {
            Swal.fire("Error", "ID Soal atau ID Paket tidak terbaca.", "error");
            return;
        }

        Swal.fire({
            title: "Memuat Soal...",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        // Sesuaikan URL dengan prefix admin jika ada
        $.ajax({
            url: `/admin/packages/${packageId}/questions/${questionId}/ajax`,
            method: "GET",
            dataType: "json",
            success: function (q) {
                Swal.close();

                // Render HTML (gunakan kode render yang sebelumnya kita bahas)
                let contentHtml = q.passage_html
                    ? renderReading(q)
                    : renderGeneral(q);

                Swal.fire({
                    title: `Preview No. ${q.number}`,
                    html: contentHtml,
                    width: "90%",
                    showCloseButton: true,
                    confirmButtonText: "Tutup",
                    confirmButtonColor: "#858796",
                });
            },
            error: function (xhr) {
                Swal.close();
                console.error(xhr.responseText); // Cek detail error di F12 Console
                Swal.fire(
                    "Gagal!",
                    "Error " + xhr.status + ": Gagal mengambil data soal.",
                    "error"
                );
            },
        });
    });

    // Fungsi helper agar kode lebih rapi
    function renderReading(q) {
        return `
    <div class="container-fluid text-left">
        <div class="row">
            <div class="col-lg-7 border-right" style="max-height: 450px; overflow-y: auto; background: #fdfdfd; padding: 15px;">
                <div style="font-family: 'Courier New', monospace; font-size: 15px;">${
                    q.passage_html
                }</div>
            </div>
            <div class="col-lg-5" style="max-height: 450px; overflow-y: auto; padding: 15px;">
                <div class="font-weight-bold mb-3">${q.content_html}</div>
                <ul class="list-group">
                    ${Object.entries(q.options)
                        .map(
                            ([k, v]) =>
                                `<li class="list-group-item p-2 small"><b>${k}.</b> ${v}</li>`
                        )
                        .join("")}
                </ul>
            </div>
        </div>
    </div>`;
    }

    function renderGeneral(q) {
        return `
    <div class="container-fluid text-left">
        <div class="p-3 bg-white border rounded">
            <div class="h6 font-weight-bold mb-4">${q.content_html}</div>
            <div class="row">
                ${Object.entries(q.options)
                    .map(
                        ([k, v]) =>
                            `<div class="col-md-6 mb-2 border-bottom p-2"><b>${k}.</b> ${v}</div>`
                    )
                    .join("")}
            </div>
        </div>
    </div>`;
    }
});
