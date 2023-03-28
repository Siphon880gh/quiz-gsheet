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
    init: () =>{
        Handlebars.registerHelper("increment", (val=>{
            return parseInt(val) + 1;
        }))
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
        const question = questions.questions[i];
        const interpolateObject = {
            title: question[2],
            problem: ()=>{
                const type = question[1]
                const problem = question[3];
                if(type==="Picture")
                    return `<img src="${problem}" style="width:50%;">`;
                else
                    return problem; // as plain text
            },
            choice1: question[4],
            choice2: question[5],
            choice3: question[6],
            choice4: question[7],
            questionIndex: i,
            questionsLength: questions.questions.length
        }
        that.__correctChoice = question[8]


        // Handlebars
        var template = document.getElementById("template-question");
        var target = document.querySelector(".question");
        // -- //
        var templateQuestionBox = template.innerHTML;
        var fillQuestionBox = Handlebars.compile(templateQuestionBox);
        var htmlQuestionBox = fillQuestionBox(interpolateObject);
        target.innerHTML = htmlQuestionBox;

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
                } else {
                    event.target.classList.add("is-wrong");

                    // I see I chose wrong, then I see what are correct
                    setTimeout(()=>{
                        const shouldveChosen = document.querySelector(`.question-choice[data-choice="${that.__correctChoice}"]`);
                        shouldveChosen.classList.add("shouldve-chosen")
                    }, 700);
                } // Ends inner if

                // Advance to next question or Finished screen
                setTimeout(()=>{
                    that.__questionNumber++;
                    if(that.__questionNumber<questions.questions.length)
                        that.showQuestion(that.__questionNumber);
                    else
                        that.nextPage();
                }, 2000)

            }  // Ends outer if
        })
    },
} // ui

ui.init();