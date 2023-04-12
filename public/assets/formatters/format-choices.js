if(typeof window.formatters === "undefined") {
    window.formatters = {};
}

window.formatters.preinjectChoicesTemplate = ({type, template})=>{
    template = template.replaceAll("__preinjectTemplateChoices__", `
    <ul class="question-choices" data-question-index="{{questionIndex}}" disabled>
        {{#each choices}}
        <li class="question-choice" data-choice-index="{{increment this.index}}">{{this.text}}</li>
        {{/each}}
    </ul>
    `);

    return template
}

/** Choices that the user sees to answer the question
 *  that could be formatted differently based on the type
 *  of question it is
 */
window.formatters.formatChoices = ({type, choices})=>{

    // Contextualize the choices for DOM rendering
    choices = choices.map((choiceRaw,i)=>{
        return {
            index: i,
            text: choiceRaw
        }
    });
    
    // Shuffle the choices
    choices = choices.sort(() => .5 - Math.random());

    return choices;
}

window.formatters.hydrateChoices = ({type}) => {
    if(type!=="ranked" && type!=="mix and match") {
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
    } 
}