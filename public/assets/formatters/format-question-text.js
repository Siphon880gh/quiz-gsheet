if(typeof window.formatters === "undefined") {
    window.formatters = {};
}

/** Question the user sees to prompt their choice,
 *  that could be formatted differently based on the type
 *  of question it is
 */
window.formatters.formatQuestionText = ({type, questionText})=>{

    type = type.toLowerCase();
    // console.log({questionText})
    
    switch(type) {
        case "picture":
            return `
            <div>
                <img src="${questionText}" style="width:50%;">
            </div>
            `;
        case "absolute pitch":
            return `
            <div>
                <video src="${questionText}" width="250" height="250" poster="https://wengindustry.com/tools/quiz-gsheet-hosting/music-sight-reading/mp3/poster/piano.jpg" controls autoplay loop webkit-playsinline playsinline type="audio/mp3"></video>
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
                    <video src="${soundPitchA}" width="250" height="200" poster="${poster}" controls webkit-playsinline playsinline type="audio/mp3"></video>
                </div>
                <div class="relative-pitch-b" style="text-align:center;">
                    <b>2. Then play me. What's the note?</b><br>
                    <video src="${soundPitchB}" width="250" height="200" poster="${poster}" controls webkit-playsinline playsinline type="audio/mp3"></video>
                </div>
            </div>
            `;
        case "flash card":
            let cardSeparator = "====";
            if(!questionText.includes(cardSeparator)) {
                return `<div class='error alert alert-danger'><b>Error:<b/> Flash card question is not formatted correctly at the Google Sheet. Please contact quiz publisher.</div>`
            }
            let [sideA,sideB]=questionText.split("====");

            return `
                <div class="question-flash-card" onclick="ui.pressedFlashCard();">
                    <div class="side-a">
                        ${sideA}
                    </div>
                    <div class="side-b d-none">
                        ${sideB}
                    </div>
                    <i class="fas fa-angle-left control-left text-secondary"></i>
                    <i class="fas fa-angle-right control-right"></i>
                </div>
            `

        default:
            return questionText;
    } // switch
}