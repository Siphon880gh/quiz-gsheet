if(typeof window.formatters === "undefined") {
    window.formatters = {};
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