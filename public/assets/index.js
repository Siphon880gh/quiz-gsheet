function getCategoryFromUrl() {
    return new URLSearchParams(window.location.search).get("category") || "";
}

function getActiveCategory(categoriesConfig) {
    const category = getCategoryFromUrl();
    if (!category) return "";
    if (!Object.prototype.hasOwnProperty.call(categoriesConfig, category)) return "";
    return category;
}

function setCategoryInUrl(category) {
    const url = new URL(window.location.href);
    if (category) {
        url.searchParams.set("category", category);
    } else {
        url.searchParams.delete("category");
    }
    history.pushState(null, "", url);
}

function folderNameFromDir(dir) {
    let folderName = dir.split("/")[0];
    // If was password protected, remove password from the folder name
    if (folderName.length && folderName[0] === "-") {
        folderName = folderName.split(" ").slice(1).join(" ");
    }
    return folderName;
}

function matchesCategory(folderName, category, categoriesConfig) {
    if (!category) return true;
    const pattern = categoriesConfig[category];
    if (!pattern) return false;
    return new RegExp(pattern, "i").test(folderName);
}

function initCategoryFilter(categoriesConfig) {
    const select = document.querySelector(".category-filter");
    if (!select) return;

    if (!select.dataset.ready) {
        select.innerHTML = "";
        const allOpt = document.createElement("option");
        allOpt.value = "";
        allOpt.textContent = "All topics";
        select.append(allOpt);

        Object.keys(categoriesConfig).forEach((key) => {
            const opt = document.createElement("option");
            opt.value = key;
            opt.textContent = key.charAt(0).toUpperCase() + key.slice(1);
            select.append(opt);
        });

        select.addEventListener("change", () => {
            setCategoryInUrl(select.value);
            initIndexUI();
        });

        window.addEventListener("popstate", () => {
            initIndexUI();
        });

        select.dataset.ready = "1";
    }

    select.value = getActiveCategory(categoriesConfig);
}

function initIndexUI() {
    const dirsEl = document.querySelector(".dirs");
    dirsEl.innerHTML = ""; // So can be reinit

    Promise.all([
        fetch("icons.config.js")
            .then((response) => (response.ok ? response.json() : { icons: [] }))
            .catch(() => ({ icons: [] })),
        fetch("categories.config.js")
            .then((response) => (response.ok ? response.json() : { categories: {} }))
            .catch(() => ({ categories: {} })),
    ]).then(([iconsConfig, categoriesConfig]) => {
        const categories = categoriesConfig.categories || {};
        initCategoryFilter(categories);
        renderListing(iconsConfig.icons || [], categories);
    });

    function renderListing(customIcons, categories) {
        const category = getActiveCategory(categories);
        const dirs = [...window.dirs].sort().filter((dir) => {
            const parts = dir.split("/");
            if (parts.length < 2) {
                return !category;
            }
            return matchesCategory(folderNameFromDir(dir), category, categories);
        });

        dirs.forEach((dir) => {
            const isSegmentedPath = dir.split("/").length;
            if (isSegmentedPath) {
                const folderName = folderNameFromDir(dir);
                const fileName = dir.split("/")[1];
                const isFirstListing = !Boolean(
                    document.querySelector(`[data-folder="${folderName}"]`)
                );
                if (isFirstListing) {
                    dirsEl.append(
                        (() => {
                            const liEl = document.createElement("li");
                            liEl.textContent = folderName;
                            liEl.classList.add("folder");

                            const matchedCustomIcon = customIcons.filter(
                                (customIcon) => customIcon.displayName === folderName
                            );
                            if (matchedCustomIcon.length) {
                                liEl.classList.add("custom-icon");
                                liEl.innerHTML =
                                    matchedCustomIcon[0].replaceIcon +
                                    "&nbsp;" +
                                    liEl.innerHTML;
                            }
                            liEl.setAttribute("data-folder", folderName);
                            return liEl;
                        })()
                    );
                }
                dirsEl.append(
                    (() => {
                        const liEl = document.createElement("li");
                        liEl.classList.add("file");

                        const aEl = document.createElement("a");
                        aEl.href = "gsheets/" + dir + ".php";
                        aEl.textContent = fileName;

                        liEl.append(aEl);
                        return liEl;
                    })()
                );
            } // isSegmentedPath
            else {
                // is not segmented with slashes, so is root file
                dirsEl.append(
                    (() => {
                        const liEl = document.createElement("li");
                        liEl.classList.add("file");

                        const aEl = document.createElement("a");
                        aEl.href = "gsheets/" + dir + ".php";
                        aEl.textContent = fileName;

                        liEl.append(aEl);
                        return liEl;
                    })()
                );
            }
        });
    } // renderListing
} // initIndexUI

function addQuizzesFromPassword() {
    const password = prompt("Enter password(s) to unlock more quizzes");
    if (password) {
        fetch(`./controllers/show-protected.php?password=${password}`)
            .then((response) => response.json())
            .then((newDirs) => {
                if (newDirs?.length) {
                    window.dirs = window.dirs.concat(newDirs);
                    initIndexUI();
                }
            });
    }
} // addQuizzesFromPassword

initIndexUI();
