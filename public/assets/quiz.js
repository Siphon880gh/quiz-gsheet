// Validate other modules loaded
setTimeout(()=>{
    if(typeof window?.formatters === "undefined") {
        alert("ERROR: Formatter modules not loaded. Please contact app developer.");
    }
    if(typeof window?.formatters?.getQuestionsSubtemplate === "undefined") {
        alert("ERROR: Question Text Formatter module not loaded. Please contact app developer.");
    }
    if(typeof window?.formatters?.injectChoicesSubtemplate === "undefined") {
        alert("ERROR: Choice Formatter module not loaded. Please contact app developer.");
    }
}, 2000);

// Dev experience: Enums
const atColumn = {
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

// Data loader
const headers = payload[0];
const rows = payload.slice(1);

// Setup business logic
const questions = {
    questions: rows.filter(row=>(parseInt(row[0])!==-1&&row.length!==0)),
    shuffle: ()=>{
        const that = questions;
        that.questions = that.questions.sort(() => .5 - Math.random());
    },
    doubleQuestions: ()=>{
        const that = questions;
        that.questions = that.questions.concat(that.questions);
        that.questions = that.questions.sort(() => .5 - Math.random());
    },

    tripleThisQuestion: (index)=>{
        const that = questions;
        const sameQuestion = that.questions[index]
        that.questions = that.questions.concat(sameQuestion,sameQuestion,sameQuestion);
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

        /* Transitioning out: Obsoleted by a precompile stage of `replaceAll("\n", "<br/>")` */
        Handlebars.registerHelper("newlineBr", (val=>{
            return val.replaceAll("\n", "<br/>")
        }));

        // Can use keyboard to answer questions
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

    getQuestionIndex: function() {
        return this.__questionNumber;
    },

    advanceNextQuestion: (waitAnimation) => {
        const that = ui;

        // Advance to next question or Finished screen
        setTimeout(()=>{
            // Remove previous playing audios/videos/iframe-embeds 
            document.querySelectorAll("iframe, audio, video").forEach(el=>{el.remove()});
            that.__questionNumber++;
            if(that.__questionNumber<questions.questions.length)
                that.showQuestion(that.__questionNumber);
            else
                that.nextPage();
        }, waitAnimation)
    }, // advancedNextQuestion

    pressedFlashCard: ()=> {
        let isFromSideA = Boolean(document.querySelector(".side-a:not(.d-none)"))
        if(isFromSideA) {
            document.querySelector(".side-a").classList.add("d-none")
            document.querySelector(".side-b").classList.remove("d-none")
            document.querySelector(".question-flash-card .control-left").classList.remove("flash-card-side-active")
            document.querySelector(".question-flash-card .control-right").classList.add("flash-card-side-active")
        } else {
            document.querySelector(".side-a").classList.remove("d-none")
            document.querySelector(".side-b").classList.add("d-none")
            document.querySelector(".question-flash-card .control-left").classList.add("flash-card-side-active")
            document.querySelector(".question-flash-card .control-right").classList.remove("flash-card-side-active")
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
            btn.classList.add("float-start")
            btn.addEventListener("click", ()=>{
                that.advanceNextQuestion();
            });
            btn.textContent = "I'm ready";

            document.querySelector(".question-nav").append(btn);

        } else {
            // User correct
            $(".question-choices").sortable("disable");
            $(".btn-rank").attr("disabled", true);
            that.__tallyCorrectChoices++;
            that.advanceNextQuestion(2000);
        }
        // console.log({failed})
    }, // pressedRankedDone
    
    pressedMatchDone: ()=>{
        const that = ui;
        let failed = false;

        /**
         * 
         * @function checkMatchingAnswers 
         * Checks each match on whether it's correctly paired by the user
         * If paired incorrectly, add a red border. Then checkAnswers will eventually return false because there was an incorrect match.
         * If paired correctly, add a green border.
         * 
         * @return {boolean} If all matched correctly, return true. If even one match incorrect, return false. Expect 100% match to be correct.
         * 
         */
        let checkMatchingAnswers = () => {
            let failedOnce = false;

            // Validate border and capture failed or passed
            $('.mix-match-droppable').each((index,droppable)=>{
                let i = $(droppable).data("value").split("-")[1];
                let j = $(droppable).data("contained-draggable-id-is");
                i=parseInt(i)
                j=parseInt(j)
                // console.log({i,j})
                if(i!==j) {
                    $(`.mix-match-droppable[data-value="h-${i}"]`).addClass("wrong")
                    failedOnce = true;
                } else {
                    $(`.mix-match-droppable[data-value="h-${i}"]`).addClass("correct")
                }
            })

            return failedOnce;
        } // checkMatchingAnswers
        
        failed = checkMatchingAnswers();

        if(failed){

            // User not allowed to re-match for better score
            document.querySelector(".btn-match").remove();

            // Show button and wait for user to confirm OK to go to next question
            let btn = document.createElement("button");
            btn.classList.add("btn")
            btn.classList.add("btn-primary")
            btn.classList.add("float-end")
            btn.addEventListener("click", ()=>{
                that.advanceNextQuestion(1);
            });
            btn.textContent = "I'm ready";
            document.querySelector(".question-nav").append(btn);

            // Show button to check other attempts at matching (won't count towards score anymore because had been wrong)
            let btnPracticeAttempt = document.createElement("button");
            btnPracticeAttempt.classList.add("btn")
            btnPracticeAttempt.classList.add("btn-secondary")
            btnPracticeAttempt.classList.add("float-end")
            btnPracticeAttempt.classList.add("me-2")
            btnPracticeAttempt.classList.add("btn-match-practice")
            btnPracticeAttempt.addEventListener("click", ()=>{
                checkMatchingAnswers();
            });
            btnPracticeAttempt.textContent = "Check practice attempt";
            document.querySelector(".question-nav").append(btnPracticeAttempt);

        } else {
            // User never failed at matching
            // User correct
            $('.mix-match-droppable').droppable("disable");
            $('.mix-match-draggable').draggable("disable");
            $(".btn-match").attr("disabled", true);

            that.__tallyCorrectChoices++;
            that.advanceNextQuestion(1000);
        }
        // console.log({failed})
    }, // pressedMatchDone

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
        if(chosenLis.length !== corrects.length) {
            // alert("Mismatched counts");
            corrects.forEach(answerKeyIndex=>{
                // console.log(answerKeyIndex);
                const checkIndexDom = document.querySelector(`.question-choice[data-choice-index="${answerKeyIndex}"]`);
                if(!$(checkIndexDom).hasClass("chosen")) {
                    $(checkIndexDom).addClass("shouldve-chosen");
                    wasOneWrong = true;
                }
            })
        }
        if( ! wasOneWrong ) { // is correct
            if(document.querySelector(".btn-sata"))
                document.querySelector(".btn-sata").setAttribute("disabled", true);
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
                if(document.querySelector(".btn-sata"))
                    document.querySelector(".btn-sata").remove();
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
    __questionNumber: -1,


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
    showQuestion: function(i) {
        const that = ui;
        if(i===0) {
            this.__questionNumber = 0;
        }

        // Question
        let row = questions.questions[i];

        if(row.length-1<atColumn.F) {
            console.log({row})
            console.error("Error: Row missing crucial columns before templateContext");
            alert("A question is not formatted correctly at the Google Sheet. Please contact quiz publisher.");
            debugger;
        }

        const templateContext = {
            questionTitle: row[atColumn.B],

            questionInstruction: row[atColumn.D],

            choicesModel: ()=>{
                try {
                    let model = formatters.modelMyChoices({ type: row[atColumn.E].toLowerCase(), choices: row.slice(atColumn.G) });
                    return model;
                } catch(err) {
                    console.group("Errored loading into choices model")
                    console.log(row.toString());
                    console.log(JSON.stringify(row));
                    console.log(err);
                    console.groupEnd();
                }
            },
            
            questionSubtemplate: ()=>{
                try {
                    let params = {
                        type: row[atColumn.E].toLowerCase(), 
                        questionText: row[atColumn.C]
                    }
                    return formatters.getQuestionsSubtemplate(params);
                } catch(err) {
                    console.group("Errored loading into choices model")
                    console.log(row.toString());
                    console.log(JSON.stringify(row));
                    console.log(err);
                    console.groupEnd();
                    return "";
                }
            },
            
            __isSata: (()=>{
                
                // F column and onwards 
                if(row.length-1<atColumn.F) {
                    console.log({row})
                    console.error("Error: Row missing crucial columns at __isSata");
                    alert("A question is not formatted correctly at the Google Sheet. Please contact quiz publisher.");
                    debugger;
                }
                return row[atColumn.F].split(",").length>1;
            })(),
            
            questionIndex: i,
            questionsLength: questions.questions.length,
            confirmChoiceSubtemplate: formatters.getConfirmChoiceSubtemplate({
                type: row[atColumn.E].toLowerCase(),
                isSata: row[atColumn.F].split(",").length>1
            })
        } // Ends template context
        // console.log({templateContext});

        that.__correctChoice = row[atColumn.F];
        that.__isSata = row[atColumn.F].split(",").length>1;

        // For Handlebars injection
        // Much of the UI logic depends on the Question type
        let type = row[atColumn.E].toLowerCase();

        // Handlebars
        var templateEl = document.getElementById("template-question");
        var targetEl = document.querySelector(".question");
        // -- //
        var templateQuestionBox = templateEl.innerHTML;
        templateQuestionBox =  window.formatters.injectChoicesSubtemplate({type, mainTemplate:templateQuestionBox});
        var fillQuestionBox = Handlebars.compile(templateQuestionBox);
        var htmlQuestionBox = fillQuestionBox(templateContext);
        targetEl.innerHTML = htmlQuestionBox;
        
        window.formatters.repaintChoicesAfterRender({type})

        // Hydrate with multiple choice handling, ranking handling, etc
        window.formatters.hydrateChoices({type})
        
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
    }, // Ends showQuestion
} // ui

ui.init();