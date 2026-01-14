
$(document).ready(function() {
    let sound = null;
    let isUserSeeking = false;
    let timerInterval = null; // Gunakan interval sebagai cadangan untuk stabilitas timer

    function loadAudio(url, title) {
        if (sound !== null) {
            sound.unload();
            cancelAnimationFrame(timerInterval);
        }

        $('#player-active-title').text(title);
        
        // Tampilkan info Buffering di samping judul
        setStatus('buffering');
        resetUI();

        sound = new Howl({
            src: [url],
            html5: false,    // KUNCI KECEPATAN: Audio diputar sambil didownload (streaming)
            preload: true,
            autoplay: false,
            onload: function() {
                $('#total-time').text(formatTime(Math.round(sound.duration())));
                setStatus('ready');
            },
            onplay: function() {
                $('#play-icon').removeClass('fa-play').addClass('fa-pause');
                // Jalankan loop update timer
                timerInterval = requestAnimationFrame(updateFrame);
            },
            onpause: function() {
                $('#play-icon').removeClass('fa-pause').addClass('fa-play');
                cancelAnimationFrame(timerInterval);
            },
            onseek: function() {
                // KUNCI FIX TIMER: Setelah digeser, pastikan frame update berjalan kembali
                if (sound.playing()) {
                    cancelAnimationFrame(timerInterval);
                    timerInterval = requestAnimationFrame(updateFrame);
                }
            },
            onstop: function() {
                resetUI();
            },
            onend: function() {
                resetUI();
            },
            onloaderror: function() {
                setStatus('error');
            }
        });
    }

    // Fungsi untuk mengubah teks status di samping judul
    function setStatus(state) {
        const statusEl = $('#player-status');
        const playBtn = $('#btn-play');
        const progress = $('#audio-progress');

        if (state === 'buffering') {
            statusEl.removeClass('d-none badge-success badge-danger').addClass('badge-warning').html('<i class="fas fa-spinner fa-spin mr-1"></i> Buffering...');
            playBtn.addClass('disabled').prop('disabled', true);
            progress.attr('disabled', 'disabled').css('opacity', '0.5');
            $('#total-time').text('--:--');
        } else if (state === 'ready') {
            statusEl.removeClass('badge-warning d-none').addClass('badge-success').html('<i class="fas fa-check mr-1"></i> Ready');
            playBtn.removeClass('disabled').prop('disabled', false);
            progress.removeAttr('disabled').css('opacity', '1');
            
            // Hilangkan badge 'Ready' setelah 2 detik agar tidak memenuhi judul
            setTimeout(() => { statusEl.fadeOut(); }, 2000);
        } else if (state === 'error') {
            statusEl.removeClass('badge-warning').addClass('badge-danger').html('<i class="fas fa-exclamation-triangle mr-1"></i> Error');
        }
    }

    // Fungsi Update Progress
    function updateFrame() {
        if (sound && sound.playing() && !isUserSeeking) {
            let seek = sound.seek() || 0;
            let duration = sound.duration() || 0;
            
            if (duration > 0) {
                let progress = (seek / duration) * 100;
                $('#audio-progress').val(progress);
                $('#current-time').text(formatTime(Math.round(seek)));
            }
            // Loop terus selama audio playing
            timerInterval = requestAnimationFrame(updateFrame);
        }
    }

    // --- Event Handler ---

    // Saat slider digeser (sedang di-drag)
    $('#audio-progress').on('input', function() {
        isUserSeeking = true; 
        if (sound) {
            let per = $(this).val() / 100;
            let currentTemp = sound.duration() * per;
            $('#current-time').text(formatTime(Math.round(currentTemp)));
        }
    });

    // Saat slider dilepas (selesai digeser)
    $('#audio-progress').on('change', function() {
        if (sound) {
            let per = $(this).val() / 100;
            let destination = sound.duration() * per;
            
            sound.seek(destination); 
            
            // Jika sedang pause lalu digeser, update teks current-time secara manual sekali
            if (!sound.playing()) {
                $('#current-time').text(formatTime(Math.round(destination)));
            }
        }
        isUserSeeking = false;
    });

    // Tombol Maju/Mundur 5 detik
    $('#btn-back, #btn-forward').click(function() {
        if (sound) {
            let offset = $(this).attr('id') === 'btn-forward' ? 5 : -5;
            let newTime = (sound.seek() || 0) + offset;
            
            sound.seek(Math.max(0, Math.min(newTime, sound.duration())));
            
            // Paksa update teks timer jika dalam keadaan pause
            if (!sound.playing()) {
                $('#current-time').text(formatTime(Math.round(sound.seek())));
            }
        }
    });

    // Tombol Play/Pause
    $('#btn-play').click(function() {
        if (!sound) return;
        sound.playing() ? sound.pause() : sound.play();
    });

    // Reset & Format (Tetap sama)
    function resetUI() {
        $('#play-icon').removeClass('fa-pause').addClass('fa-play');
        $('#audio-progress').val(0);
        $('#current-time').text('00:00');
        cancelAnimationFrame(timerInterval);
    }

    function formatTime(secs) {
        let minutes = Math.floor(secs / 60) || 0;
        let seconds = (secs - minutes * 60) || 0;
        return minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    }

    // Tabel click (Tetap sama)
    $(document).on('click', '.btn-play-table', function() {
        loadAudio($(this).data('url'), $(this).data('title'));
    });

    // Auto-load awal
    const defSrc = $('#active-audio-source').data('src');
    const defTitle = $('#active-audio-source').data('title');
    if (defSrc) loadAudio(defSrc, defTitle);
});
