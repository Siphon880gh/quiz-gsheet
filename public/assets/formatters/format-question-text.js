if(typeof window.formatters === "undefined") {
    window.formatters = {};
}

/** 
 * 
 * Question the user sees to prompt their choice,
 * that could be formatted differently based on the type
 * of question it is
 * 
 * @param {string} type Already is lower cased
 * 
 */
window.formatters.getQuestionsSubtemplate = ({type, questionText})=>{

    // Can't do this because you want newline === as separators
    // questionText = questionText.replaceAll("\n", "<br/>");

    switch(type) {
        case "picture":
            return `
            <div>
                <img class="picture-type" src="${questionText}">
            </div>
            `;
        case "video":
            return `
            <div>
                <video class="video-type" src="${questionText}" type="audio/mp3" controls autoplay loop webkit-playsinline playsinline></video>
            </div>
            `;
        case "absolute pitch":
            return `
            <div>
                <video src="${questionText}" width="250" height="250" poster="https://wengindustry.com/tools/quiz-gsheet-hosting/music-sight-reading/mp3/poster/piano.jpg" type="audio/mp3" controls autoplay loop webkit-playsinline playsinline></video>
            </div>
            `;
        case "relative pitch":
            let wrangled = questionText.split(",");
            if(wrangled.length!==3) {
                return "<div class='error alert alert-danger'><b>Error:<b/> Relative pitch question is not formatted correctly at the Google Sheet. Please contact quiz publisher.</div>"
            }

            const poster = "https://wengindustry.com/tools/quiz-gsheet-hosting/music-sight-reading/mp3/poster/piano.jpg";
            const [identifiedPitchA, soundPitchA, soundPitchB] = wrangled.map(wranglee=>wranglee.trim());
            return `
            <div class="relative-pitch" style="display:flex; justify-content: space-around;">
                <div class="relative-pitch-a" style="text-align:center;">
                    <b>1. Play me first. Note <span style="font-size:120%;">${identifiedPitchA}</span></b><br>
                    <video src="${soundPitchA}" width="250" height="200" poster="${poster}" type="audio/mp3" controls webkit-playsinline playsinline></video>
                </div>
                <div class="relative-pitch-b" style="text-align:center;">
                    <b>2. Then play me. What's the note?</b><br>
                    <video src="${soundPitchB}" width="250" height="200" poster="${poster}" type="audio/mp3" controls webkit-playsinline playsinline></video>
                </div>
            </div>
            `;
        case "flash card":
            let cardSeparator = "===";
            if(!questionText.includes(cardSeparator)) {
                return `<div class='error alert alert-danger'><b>Error:<b/> Flash card question is not formatted correctly at the Google Sheet. Please contact quiz publisher.</div>`
            }
            let [sideA,sideB]=questionText.split(/^[=]{3,}$/m);
            sideA = sideA.trim().replaceAll("\n", "<br/>");
            sideB = sideB.trim().replaceAll("\n", "<br/>");

            return `
                <div class="question-flash-card" onclick="ui.pressedFlashCard();">
                    <div class="side-a">
                        ${sideA}
                    </div>
                    <div class="side-b d-none">
                        ${sideB}
                    </div>
                    <div class="flash-card-nav">
                        <i class="fas fa-angle-left control-left text-secondary"></i>
                        <i class="fas fa-angle-right control-right"></i>
                    </div>
                </div>
            `

        default:
            questionText = questionText.replaceAll("\n", "<br/>");
            return questionText;
    } // switch
} // getQuestionsSubtemplate