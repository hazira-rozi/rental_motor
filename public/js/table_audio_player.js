document.addEventListener('DOMContentLoaded', () => {

    // ===== GLOBAL AUDIO =====
    const globalAudio = document.getElementById('global-audio');
    const globalBtn = document.querySelector('.global-play-btn');
    const globalSlider = document.getElementById('global-slider');
    const globalCurrent = document.getElementById('global-current');
    const globalTotal = document.getElementById('global-total');

    if(globalBtn){
        globalBtn.addEventListener('click', () => {
            stopAllTableAudios();
            if(globalAudio.src !== globalBtn.dataset.src){
                globalAudio.src = globalBtn.dataset.src;
            }
            if(globalAudio.paused){
                globalAudio.play();
                globalBtn.querySelector('i').classList.replace('fa-play','fa-pause');
            } else {
                globalAudio.pause();
                globalBtn.querySelector('i').classList.replace('fa-pause','fa-play');
            }
        });

        globalAudio.addEventListener('ended', () => {
            globalBtn.querySelector('i').classList.replace('fa-pause','fa-play');
        });

        globalAudio.addEventListener('loadedmetadata', () => {
            globalTotal.textContent = formatTime(globalAudio.duration);
        });

        globalAudio.addEventListener('timeupdate', () => {
            globalSlider.value = (globalAudio.currentTime / globalAudio.duration) * 100;
            globalCurrent.textContent = formatTime(globalAudio.currentTime);
        });

        globalSlider.addEventListener('input', () => {
            globalAudio.currentTime = (globalSlider.value / 100) * globalAudio.duration;
        });
    }

    // ===== TABLE AUDIOS =====
    let currentTableAudio = null;
    let currentTableBtn = null;

    document.querySelectorAll('.table-play-btn').forEach(btn => {
        const row = btn.closest('tr');
        const slider = row.querySelector('.table-slider');
        const time = row.querySelector('.table-time');
        const audio = new Audio(btn.dataset.src);

        btn.addEventListener('click', () => {
            // STOP AUDIO LAIN
            if(currentTableAudio && currentTableAudio !== audio){
                currentTableAudio.pause();
                currentTableBtn.querySelector('i').classList.replace('fa-pause','fa-play');
            }
            if(globalAudio && !globalAudio.paused){
                globalAudio.pause();
                if(globalBtn) globalBtn.querySelector('i').classList.replace('fa-pause','fa-play');
            }

            // PLAY / PAUSE
            if(audio.paused){
                audio.play();
                btn.querySelector('i').classList.replace('fa-play','fa-pause');
            } else {
                audio.pause();
                btn.querySelector('i').classList.replace('fa-pause','fa-play');
            }

            currentTableAudio = audio;
            currentTableBtn = btn;

            audio.ontimeupdate = () => {
                slider.value = (audio.currentTime / audio.duration) * 100;
                time.textContent = formatTime(audio.currentTime);
            };

            audio.onended = () => {
                btn.querySelector('i').classList.replace('fa-pause','fa-play');
            };
        });

        slider.addEventListener('input', () => {
            audio.currentTime = (slider.value / 100) * audio.duration;
        });
    });

    function stopAllTableAudios(){
        document.querySelectorAll('.table-play-btn').forEach(btn=>{
            const row = btn.closest('tr');
            const audioEl = new Audio(btn.dataset.src);
            audioEl.pause();
            btn.querySelector('i').classList.replace('fa-pause','fa-play');
        });
    }

    function formatTime(sec){
        const m = Math.floor(sec/60);
        const s = Math.floor(sec%60);
        return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    }

    const initialSrc = audio.dataset.src;
    if (initialSrc) {
        loadAudio(initialSrc, titleEl?.textContent);
    }
});