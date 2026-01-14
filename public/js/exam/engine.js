const ExamEngine = (() => {
    const state = {
        audio: null,
        questions: [],
        currentIndex: -1,
        started: false,
    };

    const SESSION_ID = window.EXAM_SESSION_ID;

    function init() {
        state.audio = document.getElementById("mainAudio");

        document
            .getElementById("btn-start-exam")
            .addEventListener("click", start);
    }

    async function start() {
        if (state.started) return;
        state.started = true;

        const res = await fetch(`/exam/api/${SESSION_ID}/listening/config`);
        if (!res.ok) {
            console.error("Listening config failed");
            return;
        }

        const cfg = await res.json();

        state.questions = cfg.questions;

        console.log("Listening questions:", state.questions.length);

        showExamScreen();
        renderInitialBlock();
        renderFirstPlaceholder();

        state.audio.src = cfg.audio_url;
        state.audio.addEventListener("timeupdate", onTimeUpdate);
        state.audio.play();
    }

    function renderInitialBlock() {
        const container = document.getElementById("exam-screen");
        container.innerHTML = "";

        for (let i = 0; i < state.questions.length; i++) {
            const q = state.questions[i];

            // berhenti saat ketemu cue pertama yang punya waktu
            if (q.cue_start !== null) break;

            container.innerHTML += renderQuestion(q);
            state.currentIndex = i;
        }
    }

    function showExamScreen() {
        document.getElementById("start-screen")?.classList.add("d-none");
        document.getElementById("exam-screen")?.classList.remove("d-none");
    }

    function onTimeUpdate() {
        const t = state.audio.currentTime;

        const nextIndex = state.questions.findIndex(
            (q, i) => i >= state.currentIndex && t >= q.cue_start
        );

        if (nextIndex === -1 || nextIndex === state.currentIndex) return;

        state.currentIndex = nextIndex;
        renderFromIndex(nextIndex);
    }

    function renderFirstPlaceholder() {
        const container = document.getElementById("exam-screen");
        container.innerHTML = "";

        const q = state.questions[0];
        if (!q) return;

        container.innerHTML = renderQuestion(q, false);
        state.currentIndex = 0;
    }

    function renderFromIndex(startIndex) {
        const container = document.getElementById("exam-screen");
        container.innerHTML = "";

        for (let i = startIndex; i < state.questions.length; i++) {
            const q = state.questions[i];

            // stop saat cue kosong berikutnya
            if (i !== startIndex && q.cue_start === null) break;

            container.innerHTML += renderQuestion(q);
        }
    }

    function renderQuestion(q, active = true) {
        let options = "";

        for (const key in q.options) {
            options += `
            <div class="option-item ${active ? "" : "disabled"}">
                <input type="radio" name="q${q.id}" ${active ? "" : "disabled"}>
                ${q.options[key]}
            </div>
        `;
        }

        return `
        <div class="listening-question ${active ? "active" : ""}">
            <div class="number">${q.number}</div>
            <div class="content">${q.content_html}</div>
            <div class="options">${options}</div>
        </div>
    `;
    }

    return { init };
})();

document.addEventListener("DOMContentLoaded", ExamEngine.init);
