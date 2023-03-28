window.dirs.forEach(dir=>{
    const dirsEl = document.querySelector(".dirs");
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