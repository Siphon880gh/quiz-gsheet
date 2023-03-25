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
    init: () =>{
        Handlebars.registerHelper("increment", (val=>{
            return parseInt(val) + 1;
        }))
    },
    startQuestions: ()=>{
        const that = ui;
        that.nextQuestion(0)
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
    // __ are internal properties and methods
    __correctIndex: -1,
    __isCorrect: (questionIndex, choice)=> {
        // TODO
    }
} // ui

ui.init();