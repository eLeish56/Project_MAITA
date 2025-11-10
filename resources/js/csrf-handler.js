// Fungsi untuk refresh CSRF token
function refreshCSRFToken() {
    return fetch('/csrf-token')
        .then(response => response.json())
        .then(data => {
            // Update token di meta tag
            $('meta[name="csrf-token"]').attr('content', data.token);
            // Update token di semua form
            $('input[name="_token"]').val(data.token);
            // Update Ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': data.token
                }
            });
            return data.token;
        });
}

// Setup interval untuk refresh token setiap 30 menit
setInterval(refreshCSRFToken, 30 * 60 * 1000);

// Refresh token setelah halaman load
$(document).ready(function() {
    refreshCSRFToken();
});

// Handle error 419 global
$(document).ajaxError(function(event, xhr, settings, error) {
    if (xhr.status === 419) {
        toastr.warning('Sesi telah berakhir, menyegarkan halaman...');
        // Refresh token dan coba lagi
        refreshCSRFToken().then(() => {
            // Reload halaman setelah 2 detik
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });
    }
});