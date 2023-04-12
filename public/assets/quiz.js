// Validate other modules loaded
setTimeout(()=>{
    if(typeof window?.formatters === "undefined") {
        alert("ERROR: Formatter modules not loaded. Please contact app developer.");
    }
    if(typeof window?.formatters?.formatQuestionText === "undefined") {
        alert("ERROR: Question Text Formatter module not loaded. Please contact app developer.");
    }
    if(typeof window?.formatters?.modelizeChoices === "undefined") {
        alert("ERROR: Choice Formatter module not loaded. Please contact app developer.");
    }
}, 2000);

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
                pressableChoice = document.querySelector(`[data-choice-index="${keyNum}"]:not(.disabled)`);
                if(pressableChoice) {
                    pressableChoice.click();
                }
            } // if isKeyNum
        }); // keyup
    },

    pressedFlashCard: ()=> {
        let isFromSideA = Boolean(document.querySelector(".side-a:not(.d-none)"))
        if(isFromSideA) {
            document.querySelector(".side-a").classList.add("d-none")
            document.querySelector(".side-b").classList.remove("d-none")
            document.querySelector(".question-flash-card .control-left").classList.add("text-secondary")
            document.querySelector(".question-flash-card .control-right").classList.remove("text-secondary")
        } else {
            document.querySelector(".side-a").classList.remove("d-none")
            document.querySelector(".side-b").classList.add("d-none")
            document.querySelector(".question-flash-card .control-left").classList.remove("text-secondary")
            document.querySelector(".question-flash-card .control-right").classList.add("text-secondary")
        }
    },

    pressedSATADone: ()=>{
        const that = ui;
        let chosens = document.querySelectorAll(`.chosen[data-choice-index]:not(.disabled)`);
        chosens = [...chosens];
        chosens = chosens.map(chosen=>chosen.dataset["choice-index"]);
        that.__handleChoiceOrChoices(chosens);
    },
    pressedRankedDone: ()=>{
        const that = ui;
        let i = 0;
        let failed = false;
        let chosens = document.querySelectorAll(`.question-choice[data-choice-index]`);
        chosens = chosens.forEach(chosen=>{
            i++;
            let domIndex = parseInt(chosen.getAttribute("data-choice-index"));
            if(domIndex!==i) {
                failed=true;
            }
        });
        if(failed){
            let $listWrong = $(".question-choices");
            let $wrapper = $(".wrapper--icon--sortable");
            let $icon = $wrapper.find(".fa-sort");

            // No more dragging because now reviewing?
            // $listWrong.sortable("disable");  // Allow user to interactively learn when reviewing so don't disable dragging during reviewing

            $wrapper.addClass("reviewing"); // No more dragging because now reviewing
            $icon.css("color", "black");
            $icon.css("cursor", "pointer");
            $icon.on("click", (event)=>{
                $icon.toggleClass("active")
            });

            let $listCorrect = $listWrong.clone();
            // TODO: Rearrange
            let $sorted = [];
            let $unsorted = $listCorrect.find(".question-choice");
            $sorted.length = $unsorted.length; // allocate $sorted to memory size of choices length

            $unsorted.each((i,choice)=>{
                let $choice = $(choice);
                let index = parseInt($choice.attr("data-choice-index"));
                $sorted[index] = $choice;
                // console.log({index});
            })
            $listCorrect.html("");
            $listCorrect.append($sorted);
            $wrapper.append($listCorrect);

            // User not allowed to re-rank for better score
            document.querySelector(".btn-rank").remove();

            // Show button and wait for user to confirm OK to go to next question
            let btn = document.createElement("button");
            btn.classList.add("btn")
            btn.classList.add("btn-primary")
            btn.classList.add("float-end")
            btn.addEventListener("click", ()=>{
                that.advanceNextQuestion();
            });
            btn.textContent = "I'm ready";

            document.querySelector(".question-nav").append(btn);

        } else {
            $(".question-choices").sortable("disable");  // User correct
            that.__tallyCorrectChoices++;
            that.advanceNextQuestion(2000);
        }
        // console.log({failed})
    },

    // Here __ are internal properties and methods
    __correctChoice: -1,

    /**
     * 
     * chosens: eg.  "A,B,C" OR "A"
     * corrects: eg.  "A,B,C" OR "A"
     * 
     */
    __handleChoiceOrChoices: (chosens)=> {
        const that = ui;
        // const chosens = document.querySelectorAll(`.chosen[data-choice-index]:not(.disabled)`);
        const corrects = that.__correctChoice.split(",").map(token=>token.trim());

        // Lock it in. No more choosing while it animates your choices/corrects/wrongs
        [...document.querySelectorAll(".question-choice")].forEach(choice=>{
            choice.classList.add("disabled");
        })

        let wasOneWrong = false;
        const chosenLis = [...document.querySelectorAll(".chosen")];
        chosenLis.forEach(aChosen=>{
            const aChosenIndex = aChosen.dataset.choiceIndex;
            let isACorrect = corrects.includes(aChosenIndex);
            if(isACorrect)
                aChosen.classList.add("is-correct");
            else {
                aChosen.classList.add("is-wrong");
                wasOneWrong = true;
            }
        });
        if( ! wasOneWrong ) {
            that.__tallyCorrectChoices++;
            that.advanceNextQuestion(2000);
            
        } else {
            setTimeout(()=>{
                corrects.forEach(aCorrect=>{
                    let shouldveChosen = document.querySelector(`.question-choice[data-choice-index="${aCorrect}"]:not(.chosen)`);
                    if(shouldveChosen) {
                        shouldveChosen.classList.add("shouldve-chosen")
                    }
                })
                
                // Show button and wait for user to confirm OK to go to next question
                let btn = document.createElement("button");
                btn.classList.add("btn")
                btn.classList.add("btn-primary")
                btn.classList.add("float-end")
                btn.addEventListener("click", ()=>{
                    that.advanceNextQuestion();
                });
                btn.textContent = "I'm ready";

                document.querySelector(".question-nav").append(btn);
            }, 700);
        }
        
        // chosens.map(chosen=>chosen.dataset["choice-index"]).sort();
        // corrects.sort();
        // return chosenChoice===that.__correctChoice;
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

            // If a sound or video clip is looping, we should also remove it to clear memory and stop it from playing in the background when the quiz finishes.
            if(that.__pageNumber===2) {
                document.querySelectorAll("video").forEach(videoEl => videoEl.remove());
            }

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
                document.querySelector(".finish-score").innerHTML = `<h3>Score</h3><p>You've got ${((correct/outOf)*100).toFixed(0)}%!</p><p>Questions correct: ${correct}/${outOf}</p>`;
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

            /**
             * @method questionText
             * @param {string} type
             * @param {string} questionText
             */
            questionText: formatters.formatQuestionText({
                type: row[atColumn.E].toLowerCase(), 
                questionText: row[atColumn.C]
            }),

            questionInstruction: row[atColumn.D],
            choices: formatters.modelizeChoices({
                type: row[atColumn.E].toLowerCase(),
                choices: row.slice([atColumn.G])
            }),
            
            // F column and onwards 
            __isSata: row[atColumn.F].split(",").length>1,
            
            questionIndex: i,
            questionsLength: questions.questions.length,
            customBtn: (()=>{
                let isSata = row[atColumn.F].split(",").length>1;
                let type = row[atColumn.E].toLowerCase();

                if(isSata) {
                    return `<button class="btn btn-primary btn-sm float-end" onclick="if(document.querySelector('.chosen')) ui.pressedSATADone(); else { alert('ERROR: You have to make your choices first!'); }">Selected all that apply</button>`
                } else if(type==="ranked") {
                    return `<button class="btn btn-primary btn-sm float-end btn-rank" onclick="ui.pressedRankedDone();">Finished ranking my choices</button>`
                } else {
                    return "";
                }
            })()
        } // Ends interpolate object (for template)

        that.__correctChoice = row[atColumn.F];
        that.__isSata = row[atColumn.F].split(",").length>1;

        // Much of the UI logic depends on the Question type
        let type = row[atColumn.E].toLowerCase();

        // Handlebars
        var template = document.getElementById("template-question");
        var target = document.querySelector(".question");
        // -- //
        var templateQuestionBox = template.innerHTML;
        templateQuestionBox = window.formatters.preinjectChoicesTemplate({type,template:templateQuestionBox});
        var fillQuestionBox = Handlebars.compile(templateQuestionBox);
        var htmlQuestionBox = fillQuestionBox(interpolateObject);
        target.innerHTML = htmlQuestionBox;
        
        // Rearrange choice DOM's
        // var questionChoicesContainer = target.querySelector("ul.question-choices");
        // var questionChoices = questionChoicesContainer.querySelectorAll("li").forEach(li=>li.cloneNode(true));
        // questionChoicesContainer.innerHTML = "";
 
        // console.log({questionChoices});
        // debugger;

        // questionChoices = questionChoices.sort(() => .5 - Math.random());
        // for(let i=0; i<questionChoices.length; i++) {
        //     let questionChoice = questionChoices[i];
        //     questionChoicesContainer.append(questionChoice);
        // }

        that.advanceNextQuestion = (waitAnimation) => {

            // Advance to next question or Finished screen
            setTimeout(()=>{
                that.__questionNumber++;
                if(that.__questionNumber<questions.questions.length)
                    that.showQuestion(that.__questionNumber);
                else
                    that.nextPage();
            }, waitAnimation)
        } // advancedNextQuestion
        
        // Initialize countdown if appropriate
        if(i==0) {
            let timeLeft = document.getElementById("time-left");
            if(timeLeft) {
                window.countdown = setInterval(()=>{
                    let timeLeft = document.getElementById("time-left");
                    if(timeLeft) {
                        let seconds = parseInt(timeLeft.textContent);
                        if(seconds>0) {
                            seconds--;
                            timeLeft.textContent = seconds;
                        } else {
                            clearInterval(window.countdown);
                            alert("Ran out of time!");
                            that.nextPage();
                        }
                    }
                }, 1000)
            }
        } // if i is 0

        console.log({interpolateObject});

        // Hydrate with multiple choice handling, ranking handling, etc
        window.formatters.hydrateChoices({type})

    }, // Ends showQuestion
} // ui

ui.init();