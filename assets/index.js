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
    __correctIndex: -1,
    __isCorrect: (questionIndex, choice)=> {
        // TODO
    },
    __pageNumber: 0,


    nextPage: () =>{
        const that = ui;
        const incrementPage = ()=>{
            // Hide all sections, advance page number, then show only section that matches page number
            document.querySelector("[data-page]").classList.add("d-none");
            that.__pageNumber++;
            document.querySelector(`[data-page="${that.__pageNumber}"]`).classList.remove("d-none");
        }
        
        switch(that.__pageNumber) {
            case 0:
                that.nextQuestion(0); // Start at Question 0th
                incrementPage();
                break;
            case 1:
                incrementPage();
                break;
            case 2:
                incrementPage();
                break;
        }
    },
    nextQuestion: (i)=>{
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
        that.__correctIndex = question[8]


        // Handlebars
        var template = document.getElementById("template-question");
        var target = document.querySelector(".question");
        // -- //
        var templateQuestionBox = template.innerHTML;
        var fillQuestionBox = Handlebars.compile(templateQuestionBox);
        var htmlQuestionBox = fillQuestionBox(interpolateObject);
        target.innerHTML = htmlQuestionBox;
    },
} // ui

ui.init();