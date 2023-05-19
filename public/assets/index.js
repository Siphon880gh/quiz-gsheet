function initIndexUI() {
    const dirsEl = document.querySelector(".dirs");
    dirsEl.innerHTML = ""; // So can be reinit
    
    window.dirs = window.dirs.reverse();
    window.dirs.forEach(dir=>{
        const isSegmentedPath = dir.split("/").length;
        if(isSegmentedPath) {
            const folderName = dir.split("/")[0];
            const fileName = dir.split("/")[1];
            const isFirstListing = !Boolean(document.querySelector(`[data-folder="${folderName}"]`));
            if(isFirstListing) {
                dirsEl.append((()=>{
                    const liEl = document.createElement("li");
                    liEl.textContent = folderName;
                    liEl.classList.add("folder")
                    liEl.setAttribute("data-folder", folderName);
                    return liEl;
                })())
            }
            dirsEl.append((()=>{
                const liEl = document.createElement("li");
                liEl.classList.add("file")
                
                const aEl = document.createElement("a");
                aEl.href = "gsheets/" + dir + ".php";
                aEl.textContent = fileName;
                
                liEl.append(aEl);
                return liEl;
            })());
        } // isSegmentedPath
        else { // is not segmented with slashes, so is root file
            
            dirsEl.append((()=>{
                const liEl = document.createElement("li");
                liEl.classList.add("file")
                
                const aEl = document.createElement("a");
                aEl.href = "gsheets/" + dir + ".php";
                aEl.textContent = fileName;
                
                liEl.append(aEl);
                return liEl;
            })());
        }
    });
} // initIndexUI

function addQuizzesFromPassword() {
    const password = prompt("Enter password(s) to unlock more quizzes"); 
    if(password) {
        fetch(`./controllers/show-protected.php?password=${password}`).then(response=>response.json()).then(newDirs=>{
            if(newDirs?.length) {
                window.dirs = window.dirs.concat(newDirs);
                initIndexUI();
            }
        });
    }
} // addQuizzesFromPassword

initIndexUI();