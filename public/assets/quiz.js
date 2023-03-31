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
                pressableChoice = document.querySelector(`[data-choice="${keyNum}"]:not(.disabled)`);
                if(pressableChoice) {
                    pressableChoice.click();
                }
            } // if isKeyNum
        }); // keyup
    },

    pressedSATADone: ()=>{
        const that = ui;
        let chosens = document.querySelectorAll(`.chosen[data-choice]:not(.disabled)`);
        chosens = [...chosens];
        chosens = chosens.map(chosen=>chosen.dataset.choice);
        that.__handleChoices(chosens);
    },

    // Here __ are internal properties and methods
    __correctChoice: -1,

    /**
     * 
     * chosens: eg.  "A,B,C" OR "A"
     * corrects: eg.  "A,B,C" OR "A"
     * 
     */
    __handleChoices: (chosens)=> {
        const that = ui;
        // const chosens = document.querySelectorAll(`.chosen[data-choice]:not(.disabled)`);
        const corrects = that.__correctChoice.split(",").map(token=>token.trim());


        // Lock it in. No more choosing while it animates your choices/corrects/wrongs
        [...document.querySelectorAll(".question-choice")].forEach(choice=>{
            choice.classList.add("disabled");
        })

        // const numberCorrects = corrects.length; // Eg. Correct choice field: "A,B,C" OR "A"
        // const numberChosens = chosens.length; // Eg. User chosen: "A,B,C" OR "A"
        // if(numberCorrects && numberChosens) {
            // if(JSON.stringify(corrects)==JSON.stringify(chosens)) {
                let wasOneWrong = false;
                const chosenLis = [...document.querySelectorAll(".chosen")];
                chosenLis.forEach(aChosen=>{
                    const aChosenIndex = aChosen.dataset.choice;
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
                            let shouldveChosen = document.querySelector(`.question-choice[data-choice="${aCorrect}"]:not(.chosen)`);
                            if(shouldveChosen)
                                shouldveChosen.classList.add("shouldve-chosen")
                        })
                        that.advanceNextQuestion(4000);
                    }, 700);
                }
            // } else {
            //     alert("Incorrect!");
            // }
        // }
        
        chosens.map(chosen=>chosen.dataset.choice).sort();
        corrects.sort();
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
                document.querySelector(".finish-score").innerHTML = `<b>Score</b>:&nbsp;${correct}/${outOf}`;
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
            questionText: ()=>{
                /** Question the user sees to prompt their choice,
                 *  that could be formatted differently based on the type
                 *  of question it is
                 */
                const type = row[atColumn.E]
                const questionText = row[atColumn.C];
                if(type.toLowerCase()==="picture")
                    return `
                    <div>
                        <img src="${questionText}" style="width:50%;">
                        <p/>
                        <p>Select correct answer:</p>
                    </div>
                    `;
                else if(type.toLowerCase()==="absolute pitch")
                    return `
                    <div>
                        <video src="${questionText}" width="250" height="250" poster="https://wengindustry.com/tools/quiz-gsheet/hosting/music-sight-reading/mp3/poster/piano.jpg" controls autoplay loop webkit-playsinline playsinline type="audio/mp3"></video>
                        <p/>
                        <p>Identify correct pitch:</p>
                    </div>
                    `;
                else
                    return questionText; // as plain text
            },
            choices: row.slice([atColumn.G]), // F column and onwards
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

        // Hydrate with multiple choice handling
        document.querySelector(".question .question-choices").addEventListener("click", (event)=>{

            if(event.target.matches(".question-choice:not(.disabled)")) {
                const that = ui;
                event.target.classList.toggle("chosen");

                // One choice acceptable
                if(!that.__isSata && document.querySelector(".chosen")) {
                    that.__handleChoices([event.target.dataset.choice])
                } // otherwise wait for user to click SATA done button
            }
        }) // Ends hydration
    }, // Ends showQuestion
} // ui

ui.init();