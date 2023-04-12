if(typeof window.formatters === "undefined") {
    window.formatters = {};
}

/** Choices that the user sees to answer the question
 *  that could be formatted differently based on the type
 *  of question it is
 */
window.formatters.modelizeChoices = ({type, choices})=>{

    // Contextualize the choices for DOM rendering

    if(type==="mix and match") {
        choices = choices.map((choiceRaw,i)=>{
            if(!choiceRaw.includes("====")) {
                alert("ERROR: The mix and match choice columns are incorrectly formatted. Please contact quiz publisher.")
            }
            return {
                index: i,
                sideA: choiceRaw.split("====")[0].trim(),
                sideB: choiceRaw.split("====")[1].trim(),
            }
        });
    } else {
        choices = choices.map((choiceRaw,i)=>{
            return {
                index: i,
                text: choiceRaw
            }
        })        
    }
    
    // Shuffle the choices
    choices = choices.sort(() => .5 - Math.random());

    return choices;
}


window.formatters.preinjectChoicesTemplate = ({type, template})=>{
    if(type==="ranked") {
        template = template.replaceAll("__preinjectTemplateChoices__", `
            <div class="wrapper--icon--sortable">
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
    } else if(type==="mix and match") {
        template = template.replaceAll("__preinjectTemplateChoices__", `
            <div class="wrapper--mix-match-containers">
                    
                {{#each choices}}
                    <div class="mix-match-container">
                        <div data-value="t{{increment index}}" class="mix-match-draggable">
                            <p>{{sideA}}</p>
                        </div>
                        <div data-value="s{{increment index}}" class="mix-match-droppable">
                            <div class="mix-match-droparea"></div>
                                <p>{{sideB}}</p>
                            </div>
                        </div>
                {{/each}}
                        
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
    
    if(type==="ranked") {

        // Do not shrink height when dragging
        setTimeout(()=>{
            let height = document.querySelector(".sortable-list").clientHeight;
            document.querySelector(".sortable-list").style.minHeight = parseInt(Math.ceil(height)) + "px";

            let width = document.querySelector(".sortable-list").clientWidth;
            document.querySelector(".sortable-list").style.minWidth = parseInt(Math.ceil(width)) + "px";
        }, 250);

        // Automatically draggable from sortable API
        $(".sortable-list").sortable({
            axis: "y"
        }); // do not allow helper clone hover beyond the container left or right
        
    } else if(type==="mix and match") {
        // Thanks to: https://jsfiddle.net/esedic/3ud6e7xz/

        $('.mix-match-draggable').draggable({
            //revert: 'invalid',
            cursor: 'move'
        });
        $('.mix-match-droppable').droppable({
              drop: function(event, ui) {
                  $(this).addClass('ui-state-highlight');
                  ui.draggable.position({
                      of: $(this),
                      my: 'left top',
                      at: 'left+6 top+6'
                  });
                     /* A/B Split Design */
                    //   my: 'right top',
                    //   at: 'right-6 top+6'
                  $('.draggable').each(function(i) { 
                   $(this).data('value', i+1); 
                }).filter(':first').trigger('listData');
              }
        });
        
        $('.mix-match-draggable').on('listData', function() {
            $('.mix-match-draggable').each(function() {
               console.log( $(this).text() + ' - ' + $(this).data('value') ); 
            });
        });
    }  else {
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
    } // else
    
} // hydrateChoices