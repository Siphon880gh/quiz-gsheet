// Data loader
const headers = payload[0];
const rows = payload.slice(1);

// Setup business logic
const questions = {
    questions: rows,
    shuffle: ()=>{
        const that = questions;
        that.questions = that.questions.sort(() => .5 - Math.random());
    }
}

const ui = {
    // Init UI readiness for dynamic rendering
    // Init keyboard pressing choices
    init: () =>{
        Handlebars.registerHelper("increment", (val=>{
            return parseInt(val) + 1;
        }));

        document.body.addEventListener('keyup', function(e) {
            const isKeyNum = !isNaN(parseInt(e.key));
            if(isKeyNum) {
                const keyNum = parseInt(e.key);
                pressableChoice = document.querySelector(`[data-choice-index="${keyNum}"]:not(.disabled)`);
                if(pressableChoice) {
                    pressableChoice.click();
                }
            } // if isKeyNum
        }); // keyup
    },

    pressedSATADone: ()=>{
        const that = ui;
        let chosens = document.querySelectorAll(`.chosen[data-choice-index]:not(.disabled)`);
        chosens = [...chosens];
        chosens = chosens.map(chosen=>chosen.dataset["choice-index"]);
        that.__handleChoiceOrChoices(chosens);
    },

    // Here __ are internal properties and methods
    __correctChoice: -1,

    /**
     * 
     * chosens: eg.  "A,B,C" OR "A"
     * corrects: eg.  "A,B,C" OR "A"
     * 
     */
    __handleChoiceOrChoices: (chosens)=> {
        const that = ui;
        // const chosens = document.querySelectorAll(`.chosen[data-choice-index]:not(.disabled)`);
        const corrects = that.__correctChoice.split(",").map(token=>token.trim());

        // Lock it in. No more choosing while it animates your choices/corrects/wrongs
        [...document.querySelectorAll(".question-choice")].forEach(choice=>{
            choice.classList.add("disabled");
        })

        let wasOneWrong = false;
        const chosenLis = [...document.querySelectorAll(".chosen")];
        chosenLis.forEach(aChosen=>{
            const aChosenIndex = aChosen.dataset.choiceIndex;
            let isACorrect = corrects.includes(aChosenIndex);
            if(isACorrect)
                aChosen.classList.add("is-correct");
            else {
                aChosen.classList.add("is-wrong");
                wasOneWrong = true;
            }
        });
        if(!wasOneWrong) {
            that.__tallyCorrectChoices++;
            that.advanceNextQuestion(2000);
        } else {
            setTimeout(()=>{
                corrects.forEach(aCorrect=>{
                    let shouldveChosen = document.querySelector(`.question-choice[data-choice-index="${aCorrect}"]:not(.chosen)`);
                    if(shouldveChosen) {
                        shouldveChosen.classList.add("shouldve-chosen")
                    }
                })
                that.advanceNextQuestion(4000);
            }, 700);
        }
        
        // chosens.map(chosen=>chosen.dataset["choice-index"]).sort();
        // corrects.sort();
        // return chosenChoice===that.__correctChoice;
    },
    __tallyCorrectChoices: 0,
    __pageNumber: 0,
    __questionNumber: 0,


    nextPage: () =>{
        const that = ui;
        const incrementPage = ()=>{
            // Hide all sections, advance page number, then show only section that matches page number
            document.querySelectorAll("[data-page]").forEach(pageEl=>pageEl.classList.add("d-none"));
            that.__pageNumber++;

            // If a sound or video clip is looping, we should also remove it to clear memory and stop it from playing in the background when the quiz finishes.
            if(that.__pageNumber===2) {
                document.querySelectorAll("video").forEach(videoEl => videoEl.remove());
            }

            document.querySelector(`[data-page="${that.__pageNumber}"]`).classList.remove("d-none");
        }
        
        switch(that.__pageNumber) {
            case 0:
                that.showQuestion(0); // Start at Question 0th
                incrementPage();
                break;
            case 1:
                const correct = that.__tallyCorrectChoices;
                const outOf = questions.questions.length;
                document.querySelector(".finish-score").innerHTML = `<h3>Score</h3><p>You've got ${((correct/outOf)*100).toFixed(0)}%!</p><p>Questions correct: ${correct}/${outOf}</p>`;
                incrementPage();
                break;
        }
    },
    showQuestion: (i)=>{
        const that = ui;

        // Question
        const row = questions.questions[i];
        let atColumn = {
            A:0,
            B:1,
            C:2,
            D:3,
            E:4,
            F:5,
            G:6,
            H:7,
            I:8,
            J:9,
            K:10,
            L:11,
            M:12,
            N:13,
            O:14,
            P:15,
            Q:16,
            R:17
        }
        const interpolateObject = {
            questionTitle: row[atColumn.B],
            questionText: (()=>{
                /** Question the user sees to prompt their choice,
                 *  that could be formatted differently based on the type
                 *  of question it is
                 */
                const type = row[atColumn.E]
                const questionText = row[atColumn.C];
                // console.log({questionText})
                
                switch(type.toLowerCase()) {
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
                        let [identifiedPitchA, soundPitchA, soundPitchB] = wrangled.map(wranglee=>wranglee.trim());
                        return `
                        <div class="relative-pitch" style="display:flex; justify-content: space-around;">
                            <div class="relative-pitch-a" style="text-align:center;">
                                <b>1. Play me first. Note <span style="font-size:120%;">${identifiedPitchA}</span></b><br>
                                <video src="${soundPitchA}" width="250" height="200" poster="https://wengindustry.com/tools/quiz-gsheet-hosting/music-sight-reading/mp3/poster/piano.jpg" controls webkit-playsinline playsinline type="audio/mp3"></video>
                            </div>
                            <div class="relative-pitch-b" style="text-align:center;">
                                <b>2. Then play me. What's the note?</b><br>
                                <video src="${soundPitchB}" width="250" height="200" poster="https://wengindustry.com/tools/quiz-gsheet-hosting/music-sight-reading/mp3/poster/piano.jpg" controls webkit-playsinline playsinline type="audio/mp3"></video>
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
                            <div class='side-a' onclick="event.target.classList.add('d-none'); document.querySelector('.side-b').classList.remove('d-none');">${sideA}</div>
                            <div class='side-b d-none' onclick="event.target.classList.add('d-none'); document.querySelector('.side-a').classList.remove('d-none');">${sideB}</div>
                        `

                    default:
                        return questionText;
                } // switch
            })(),
            questionInstruction: row[atColumn.D],
            choices: row.slice([atColumn.G]).map((choiceRaw,i)=>{
                return {
                    index: i,
                    text: choiceRaw
                }
            }).sort(() => .5 - Math.random()), 
            
            // F column and onwards 
            __isSata: row[atColumn.F].split(",").length>1,

            questionIndex: i,
            questionsLength: questions.questions.length
        } // Ends interpolate object (for template)
        that.__correctChoice = row[atColumn.F];
        that.__isSata = row[atColumn.F].split(",").length>1;

        // Handlebars
        var template = document.getElementById("template-question");
        var target = document.querySelector(".question");
        // -- //
        var templateQuestionBox = template.innerHTML;
        var fillQuestionBox = Handlebars.compile(templateQuestionBox);
        var htmlQuestionBox = fillQuestionBox(interpolateObject);
        target.innerHTML = htmlQuestionBox;
        
        // Rearrange choice DOM's
        // var questionChoicesContainer = target.querySelector("ul.question-choices");
        // var questionChoices = questionChoicesContainer.querySelectorAll("li").forEach(li=>li.cloneNode(true));
        // questionChoicesContainer.innerHTML = "";
 
        // console.log({questionChoices});
        // debugger;

        // questionChoices = questionChoices.sort(() => .5 - Math.random());
        // for(let i=0; i<questionChoices.length; i++) {
        //     let questionChoice = questionChoices[i];
        //     questionChoicesContainer.append(questionChoice);
        // }

        that.advanceNextQuestion = (waitAnimation) => {

            // Advance to next question or Finished screen
            setTimeout(()=>{
                that.__questionNumber++;
                if(that.__questionNumber<questions.questions.length)
                    that.showQuestion(that.__questionNumber);
                else
                    that.nextPage();
            }, waitAnimation)
        } // advancedNextQuestion
        
        // Initialize countdown if appropriate
        if(i==0) {
            let timeLeft = document.getElementById("time-left");
            if(timeLeft) {
                window.countdown = setInterval(()=>{
                    let timeLeft = document.getElementById("time-left");
                    if(timeLeft) {
                        let seconds = parseInt(timeLeft.textContent);
                        if(seconds>0) {
                            seconds--;
                            timeLeft.textContent = seconds;
                        } else {
                            clearInterval(window.countdown);
                            alert("Ran out of time!");
                            that.nextPage();
                        }
                    }
                }, 1000)
            }
        } // if i is 0

        console.log({interpolateObject});

        // Hydrate with multiple choice handling
        document.querySelector(".question .question-choices").addEventListener("click", (event)=>{

            if(event.target.matches(".question-choice:not(.disabled)")) {
                const that = ui;
                event.target.classList.toggle("chosen");

                // One choice acceptable
                if(!that.__isSata && document.querySelector(".chosen")) {
                    that.__handleChoiceOrChoices([event.target.dataset.choiceIndex])
                } // otherwise wait for user to click SATA done button
            }
        }) // Ends hydration
    }, // Ends showQuestion
} // ui

ui.init();