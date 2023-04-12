if(typeof window.formatters === "undefined") {
    window.formatters = {};
}

/** Choices that the user sees to answer the question
 *  that could be formatted differently based on the type
 *  of question it is
 */
window.formatters.modelizeChoices = ({type, choices})=>{

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


window.formatters.preinjectChoicesTemplate = ({type, template})=>{
    if(type==="ranked") {
        template = template.replaceAll("__preinjectTemplateChoices__", `
            <div class="sortable-wrapper">
                <i class="fas fa-sort" title="Can drag and drop to change order"></i>
                <ol class="question-choices sortable-list">
                    
                {{#each choices}}
                    <li class="question-choice ui-state-default" data-choice-index="{{increment this.index}}">
                        {{this.text}}
                    </li>
                    {{/each}}
                        
                        
                </ol> <!-- question-choices-->
            </div>
        `);
    } else {
        template = template.replaceAll("__preinjectTemplateChoices__", `
            <ul class="question-choices" data-question-index="{{questionIndex}}" disabled>
                {{#each choices}}
                <li class="question-choice" data-choice-index="{{increment this.index}}">{{this.text}}</li>
                {{/each}}
            </ul>
        `);
        
    }

    return template
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
    } else if(type==="ranked") {

        // Do not shrink height when dragging
        setTimeout(()=>{
            let height = document.querySelector(".sortable-list").clientHeight;
            document.querySelector(".sortable-list").style.minHeight = parseInt(Math.ceil(height)) + "px";

            let width = document.querySelector(".sortable-list").clientWidth;
            document.querySelector(".sortable-list").style.minWidth = parseInt(Math.ceil(width)) + "px";
        }, 250);

        // Automatically draggable
        $(".sortable-list").sortable({
            axis: "y"
        }); // do not allow helper clone hover beyond the container left or right

        // $(".sortable-list li").draggable({
        //     connectToSortable: '.sortable-list',
        //     cursor: 'pointer',
        //     helper: 'original',
        //     revert: 'invalid',
        //     drag: function (event, ui) { 
        //         document.querySelector(".question-choices").style.listStyle = "none";

        //     },
        //     stop: function(event, ui) {
        //     }
        // });
    }
}