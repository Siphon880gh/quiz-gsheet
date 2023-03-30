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

    // Here __ are internal properties and methods
    __correctChoice: -1,
    __isCorrectChoice: (chosenChoice)=> {
        const that = ui;
        return chosenChoice===that.__correctChoice;
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
                const type = row[atColumn.D]
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
            choices: row.slice([atColumn.F]), // F column and onwards

            questionIndex: i,
            questionsLength: questions.questions.length
        }
        that.__correctChoice = row[atColumn.E];

        // Handlebars
        var template = document.getElementById("template-question");
        var target = document.querySelector(".question");
        // -- //
        var templateQuestionBox = template.innerHTML;
        var fillQuestionBox = Handlebars.compile(templateQuestionBox);
        var htmlQuestionBox = fillQuestionBox(interpolateObject);
        target.innerHTML = htmlQuestionBox;

        function advanceNextQuestion(waitAnimation) {

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
            // Clicked a choice and not area around the choices
            if(event.target.matches(".question-choice:not(.disabled)")) {
                const that = ui;
                let chosenChoice = event.target.dataset.choice;
                document.querySelectorAll(".question-choice").forEach(choiceEl=>choiceEl.classList.add("disabled"));

                if(that.__isCorrectChoice(chosenChoice)) {
                    event.target.classList.add("is-correct");
                    that.__tallyCorrectChoices++;
                    advanceNextQuestion(2000);
                } else {
                    event.target.classList.add("is-wrong");
                    
                    // I see I chose wrong, then I see what are correct
                    setTimeout(()=>{
                        const shouldveChosen = document.querySelector(`.question-choice[data-choice="${that.__correctChoice}"]`);
                        shouldveChosen.classList.add("shouldve-chosen")
                        advanceNextQuestion(4000);
                    }, 700);
                } // Ends inner if

            }  // Ends outer if
        })
    },
} // ui

ui.init();