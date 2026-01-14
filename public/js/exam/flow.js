const ExamFlow = (() => {
    let flowIndex = 0;
    let questions = [];
    let answers = {};
    let isViewingInstruction = false;
    let audio = null;
    let isListeningMode = false;

    function init() {
        audio = document.getElementById("mainAudio");
        fetchQuestions();

        const btnStart = document.getElementById("btn-start-exam");
        if (btnStart) {
            btnStart.onclick = (e) => {
                e.preventDefault();
                startExam();
            };
        }

        document.getElementById("btn-next").onclick = () => next();
        document.getElementById("btn-prev").onclick = () => prev();
    }

    async function fetchQuestions() {
        try {
            const res = await fetch(`/exam/api/${window.EXAM_SESSION_ID}/questions`);
            const data = await res.json();
            if (data.questions) {
                questions = data.questions;
                const btn = document.getElementById("btn-start-exam");
                btn.disabled = false;
                btn.innerHTML = "Start Exam";
            }
        } catch (err) { console.error("Error fetching questions:", err); }
    }

    function startExam() {
        document.getElementById("start-screen").setAttribute("style", "display: none !important");
        document.getElementById("exam-screen").classList.remove("d-none");
        document.getElementById("exam-footer").classList.remove("d-none");

        flowIndex = 0;
        const firstQ = questions[0];
        
        // Cek mode instruksi diawal
        isListeningMode = firstQ?.section.toLowerCase() === "listening";
        isViewingInstruction = !!firstQ?.instruction_data;

        if (audio) {
            audio.preload = "auto";
            audio.play().catch(() => console.warn("Audio autoplay blocked"));
            audio.ontimeupdate = checkAudioCues;
        }

        playCurrent();
    }

    function playCurrent() {
        const q = questions[flowIndex];
        if (!q) return;

        isListeningMode = q.section.toLowerCase() === "listening";
        const instrArea = document.getElementById("instruction");
        const qArea = document.getElementById("question-area");
        
        // Update Navigasi
        document.getElementById("section-name").innerText = q.section;
        document.getElementById("navigation-column").classList.toggle("d-none", isListeningMode);
        document.getElementById("btn-next").classList.toggle("d-none", isListeningMode);
        document.getElementById("btn-prev").classList.toggle("d-none", isListeningMode);

        if (isViewingInstruction && q.instruction_data) {
            instrArea.innerHTML = `<div class="instruction-box">${q.instruction_data}</div>`;
            instrArea.classList.remove("d-none");
            qArea.classList.add("d-none");
        } else {
            instrArea.classList.add("d-none");
            qArea.classList.remove("d-none");
            renderQuestion(q);
        }
        renderQuestionMap();
    }

    function renderQuestion(q) {
        document.getElementById("question-number").innerText = `Question ${q.number}`;
        document.getElementById("question-text").innerHTML = q.content_html || "Listen to audio...";
        
        let html = "";
        let opts = typeof q.options === "string" ? JSON.parse(q.options) : q.options;
        Object.entries(opts).forEach(([key, val]) => {
            const isSelected = answers[q.id] === key;
            html += `<div class="answer-option ${isSelected ? 'selected' : ''}" onclick="ExamFlow.handleSelect('${q.id}', '${key}')">
                        <strong class="mr-2">${key}.</strong> ${val}
                    </div>`;
        });
        document.getElementById("options-container").innerHTML = html;
    }

    function checkAudioCues() {
        if (!isListeningMode || !audio) return;
        const time = audio.currentTime;

        // Cari soal pertama di section ini yang punya cue_start
        const firstQ = questions.find(q => q.section.toLowerCase() === "listening" && q.cue_start !== null);

        // Jika audio belum sampai di cue soal pertama, paksa tampilkan instruksi
        if (firstQ && time < firstQ.cue_start) {
            if (!isViewingInstruction) {
                isViewingInstruction = true;
                playCurrent();
            }
            return;
        }

        // Cari soal aktif berdasarkan waktu audio
        const activeIdx = questions.findLastIndex(q => 
            q.section.toLowerCase() === "listening" && q.cue_start !== null && time >= q.cue_start
        );

        if (activeIdx !== -1 && (activeIdx !== flowIndex || isViewingInstruction)) {
            flowIndex = activeIdx;
            isViewingInstruction = false;
            playCurrent();
        }
    }

    function renderQuestionMap() {
        const container = document.getElementById("question-map");
        if (!container) return;
        container.innerHTML = questions.map((q, i) => {
            let cls = `map-btn ${answers[q.id] ? 'answered' : ''} ${flowIndex === i ? 'active' : ''}`;
            return `<button onclick="ExamFlow.jumpTo(${i})" class="${cls}">${i + 1}</button>`;
        }).join("");
    }

    function handleSelect(qId, val) {
        answers[qId] = val;
        renderQuestion(questions[flowIndex]);
        renderQuestionMap();
        saveAnswer(qId, val);
    }

    function jumpTo(i) {
        if (!isListeningMode) { flowIndex = i; isViewingInstruction = false; playCurrent(); }
    }

    function next() {
        if (isViewingInstruction) { isViewingInstruction = false; playCurrent(); }
        else if (flowIndex < questions.length - 1) { flowIndex++; playCurrent(); }
    }

    function prev() {
        if (!isListeningMode && flowIndex > 0) { flowIndex--; playCurrent(); }
    }

    async function saveAnswer(qId, val) {
        const csrf = document.querySelector('meta[name="csrf-token"]');
        fetch(`/exam/api/${window.EXAM_SESSION_ID}/answer`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrf?.content || "" },
            body: JSON.stringify({ question_id: qId, selected: val })
        });
    }

    return { init, startExam, handleSelect, jumpTo, next, prev };
})();

document.addEventListener("DOMContentLoaded", () => ExamFlow.init());