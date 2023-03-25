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
    startQuestions: ()=>{
        const that = ui;
        that.renderQuestion(0)
    },
    renderQuestion: (i)=>{
        // Question
        const question = questions.questions[i];

        // Handlebars
        var template = document.getElementById("template-question");
        var target = document.querySelector(".question");
        // -- //
        var templateQuestionBox = template.innerHTML;
        var fillerQuestionBox = Handlebars.compile(templateQuestionBox);
        var htmlQuestionBox = fillerQuestionBox({q:question});
        target.innerHTML = htmlQuestionBox;
    }
}