if(typeof window.formatters === "undefined") {
    window.formatters = {};
}

/** Choices that the user sees to answer the question
 *  that could be formatted differently based on the type
 *  of question it is
 */
window.formatters.modelMyChoices = ({type, choices})=>{

    // Contextualize the choices for DOM rendering

    if(type==="mix and match") {
        choices = choices.map((choiceRaw,i)=>{
            if(!choiceRaw.includes("====")) {
                alert("ERROR: The mix and match choice columns are incorrectly formatted. Please contact quiz publisher.")
            }
            return {
                index: i,
                sideA: choiceRaw.split("====")[0].replaceAll("\n", "<br/>"),
                sideB: choiceRaw.split("====")[1].replaceAll("\n", "<br/>")
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


window.formatters.injectChoicesSubtemplate = ({type, mainTemplate})=>{
    if(type==="ranked") {
        mainTemplate = mainTemplate.replaceAll("__inject__choices__subtemplate__", `
            <div class="wrapper--icon--sortable">
                <i class="fas fa-sort" title="Can drag and drop to change order"></i>
                <ol class="question-choices sortable-list">
                    
                {{#each choicesModel}}
                    <li class="question-choice ui-state-default" data-choice-index="{{increment this.index}}">
                        {{{cvtNLToBr this.text}}}
                    </li>
                {{/each}}
                        
                        
                </ol> <!-- question-choices-->
            </div>
        `);
    } else if(type==="mix and match") { 
        // Key and Hole ~ Draggable and Droparea. The droparea constraints to a smaller area in the Droppable.
        mainTemplate = mainTemplate.replaceAll("__inject__choices__subtemplate__", `
            <div class="wrapper--mix-match-containers d-none">
                    
                {{#each choicesModel}}
                    <div class="mix-match-container">
                        <div data-value="k-{{increment index}}" class="mix-match-draggable">
                            <p>{{{cvtNLToBr sideA}}}</p>
                        </div>
                        <div data-value="h-{{increment index}}" class="mix-match-droppable">
                            <div class="mix-match-droparea"></div>
                                <p>{{{cvtNLToBr sideB}}}</p>
                            </div>
                        </div>
                {{/each}}
                        
            </div>
            <script id="trigger-set-innerHTML-done"></script>
        `);
    } else {
        mainTemplate = mainTemplate.replaceAll("__inject__choices__subtemplate__", `
            <ul class="question-choices" data-question-index="{{questionIndex}}" disabled>
                {{#each choicesModel}}
                    <li class="question-choice" data-choice-index="{{increment this.index}}">{{{this.text}}}</li>
                {{/each}}
            </ul>
        `);
    }

    return mainTemplate
} // getChoicesSubtemplate


window.formatters.getConfirmChoiceSubtemplate = ({type, isSata})=>{

    if(isSata) {
        return `<button class="btn btn-primary btn-sm float-end" onclick="if(document.querySelector('.chosen')) ui.pressedSATADone(); else { alert('ERROR: You have to make your choices first!'); }">Selected all that apply</button>`
    } else if(type==="ranked") {
        return `<button class="btn btn-primary btn-sm float-start btn-rank" onclick="ui.pressedRankedDone();">Finished ordering</button>`
    } else if(type==="mix and match") {
        return `<button class="btn btn-primary btn-sm float-end btn-match" onclick="ui.pressedMatchDone();">Finished matching</button>`
    } else {
        return "";
    }

};

window.formatters.repaintChoicesAfterRender = ({type}) => {
    // console.log({type})

    // In place in case needed in the future
    if(type==="mix and match") {

        let wait = (cb)=>{
            let looper = setTimeout(()=>{
                if(document.getElementById("trigger-set-innerHTML-done")) {
                    clearTimeout(looper);
                    document.getElementById("trigger-set-innerHTML-done").remove();
                    cb();
                }
            },200) // setTimeout
        }

        let exec = (type) => {
            // Standardization 1:
            // Standardize widths/heights of dropareas to the max width and height of the draggable choices in order to make visually appealing yet concealing the answers
            (()=>{
                let maxWidth = 0;
                let maxHeight = 0;
                let keysCount = $(`[data-value^="k-"]`).length;

                $(`[data-value^="k-"]`).each((i,k)=>{
                    let currentWidth = $(k).width();
                    // console.log({currentWidth})

                    if(currentWidth>maxWidth) {
                        maxWidth = currentWidth;
                    }
                    let currentHeight = $(k).height();
                    if(currentHeight>maxHeight) {
                        maxHeight = currentHeight;
                    }

                    if(i===keysCount-1) {
                        $(".mix-match-droparea").width(maxWidth);
                        $(".mix-match-droparea").height(maxHeight);
                    }
                }); // each k
            })();

            // Standardization 2:
            // Standardize widths/heights of Droppable container at the right column to the biggest width and height
            (()=>{
                let maxWidth = 0;
                let keysCount = $(`[data-value^="h-"]`).length;

                $(`[data-value^="h-"]`).each((i,h)=>{
                    let currentWidth = $(h).width();
                    // console.log({currentWidth})

                    if(currentWidth>maxWidth) {
                        maxWidth = currentWidth;
                    }

                    if(i===keysCount-1) {
                        $(".mix-match-droppable").width(maxWidth);
                    }
                }); // each k
            })();

            // Shuffle key column (aka draggables)
            let $unmixed = $(`[data-value^="k-"]`);
            let $mixed = [];
            $unmixed.each((i,el)=>{
                let $cloned = $(el).clone();
                $(el).remove();
                $mixed.push($cloned);
            });
            $mixed = $mixed.sort(() => .5 - Math.random());
            // console.log($mixed);

            $(".mix-match-container").each((i, c)=>{
                $(c).prepend($mixed[i])
            })

            // Unhide the initially hidden (Didn't want to see original order flashed before shuffled order)
            $(".wrapper--mix-match-containers").removeClass("d-none");

            // Hydrate to become interactable
            $('.mix-match-draggable').draggable({
                //revert: 'invalid',
                cursor: 'move'
            });
            $('.mix-match-droppable').droppable({
                  drop: function(event, ui) {
                        const droppable = event.target;
                        const draggable = ui.draggable;

                      $(this).addClass('ui-state-highlight');
                      draggable.position({
                          of: $(this),
                          my: 'left top',
                          at: 'left+6 top+6'
                      });

                      $(draggable).data("user-matched-to", parseInt($(droppable).data("value").split("-")[1]))
                  } // drop
            });

        } // exec


        wait(exec);

    } // mix and match

} // repaintChoicesAfteRender

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
        // Moved to after repainting
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