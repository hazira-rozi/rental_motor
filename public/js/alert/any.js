
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonText: 'OK'
        });
});
