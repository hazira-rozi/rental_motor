document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("audio-upload-form");
    const input = document.getElementById("audio-input");
    const progressBox = document.getElementById("upload-progress-box");
    const bar = document.getElementById("upload-progress-bar");

    if (!form || !input || !progressBox || !bar) {
        console.error("Audio upload element missing");
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const file = input.files[0];
        if (!file) return;

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        xhr.open("POST", form.action, true);
        xhr.setRequestHeader(
            "X-CSRF-TOKEN",
            document.querySelector('meta[name="csrf-token"]').content
        );

        // TAMPILKAN PROGRESS
        progressBox.classList.remove("d-none");
        bar.style.width = "0%";
        bar.innerText = "0%";

        xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                bar.style.width = percent + "%";
                bar.innerText = percent + "%";
            }
        };

        xhr.onload = function () {
            console.log("Status:", xhr.status); // Cek status code di console
            console.log("Response:", xhr.responseText);

            if (xhr.status === 200) {
                // ... kode sukses ...
            } else {
                alert("Upload gagal dengan status: " + xhr.status);
            }
            if (xhr.status === 200) {
                bar.classList.remove("progress-bar-animated");
                bar.classList.add("bg-success");
                bar.innerText = "Selesai";

                setTimeout(() => {
                    window.location.reload();
                }, 800);
            } else {
                alert("Upload gagal");
            }
        };

        xhr.onerror = function () {
            alert("Upload error");
        };

        xhr.send(formData);
    });
});
