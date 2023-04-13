# Create Quizzes from Google Sheets

![Last Commit](https://img.shields.io/github/last-commit/Siphon880gh/quiz-gsheet/main)
<a target="_blank" href="https://github.com/Siphon880gh" rel="nofollow"><img src="https://img.shields.io/badge/GitHub--blue?style=social&logo=GitHub" alt="Github" data-canonical-src="https://img.shields.io/badge/GitHub--blue?style=social&logo=GitHub" style="max-width:10ch;"></a>
<a target="_blank" href="https://www.linkedin.com/in/weng-fung/" rel="nofollow"><img src="https://camo.githubusercontent.com/0f56393c2fe76a2cd803ead7e5508f916eb5f1e62358226112e98f7e933301d7/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f4c696e6b6564496e2d626c75653f7374796c653d666c6174266c6f676f3d6c696e6b6564696e266c6162656c436f6c6f723d626c7565" alt="Linked-In" data-canonical-src="https://img.shields.io/badge/LinkedIn-blue?style=flat&amp;logo=linkedin&amp;labelColor=blue" style="max-width:10ch;"></a>
<a target="_blank" href="https://www.youtube.com/user/Siphon880yt/" rel="nofollow"><img src="https://camo.githubusercontent.com/0bf5ba8ac9f286f95b2a2e86aee46371e0ac03d38b64ee2b78b9b1490df38458/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f596f75747562652d7265643f7374796c653d666c6174266c6f676f3d796f7574756265266c6162656c436f6c6f723d726564" alt="Youtube" data-canonical-src="https://img.shields.io/badge/Youtube-red?style=flat&amp;logo=youtube&amp;labelColor=red" style="max-width:10ch;"></a>

## :page_facing_up: Description:
By Weng Fei Fung. Easily create various types of quizzes from Google Sheet. A Google spreadsheet URL and tab name with the right columns inputted and you have yourself a quiz. There are multiple choices, fill in the blanks, Select all that apply (SATA), True/False, and flash cards. You can have audio and video elements. You can have more than the four standard multiple choices (as many choices as you add columns in Google sheet). Because you can also have just two choices by just having two choice  columns in your Google Sheet, you effectively can have True/False questions as well. Optionally you can add a countdown timer. Minimal coding knowledge needed. And mostly all in Google Sheets.

## :open_file_folder: Table of Contents:
---
- [Description](#page_facing_up-description) <!-- - [Screenshots](#camera-screenshots) -->
- [Live Demo](#computer-live-demo)
- [Installation and Quiz Administration](#minidisc-installation-and-quiz-administration)
- [Usage](#runner-usage)
- [Architecture](#triangular_ruler-architecture)
- [Future Version](#crystal_ball-future-version)
---

<!-- ## :camera: Screenshots:
![image](docs/screenshot.png) -->

## :computer: Live Demo:
<a href="https://wengindustry.com/tools/quiz-gsheet" target="_blank">Check it out</a>

<!-- 
:camera: Preview:
---
![image](docs/screenshot.png) -->

## :minidisc: Installation and Quiz Administration:

Install PHP Google API Client by running `composer install google/apiclient` 

At gsheets/, create a folder that represents a group of quizzes. Even if you have one quiz, you must create a folder. The front page will show quizzes grouped by categories, and the categories are your folders, followed by quizzes that belong to those folders aka categories.

Create a PHP file inside your folder, and that will represent your connection to a specific Google Sheet tab that has your quiz details. Remember the file ends with file extension .php. Then input your Google sheet id and tab name into the PHP file like this:

```
<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$inputs = [
    /* Connections */
    "spreadsheetUrl"=>"https://docs.google.com/spreadsheets/d/1SHqEB2MVho0jP81cT9bDEo5VUZOzkfwNC1BZ3qB8VQE/",
    "spreadsheetId"=>"1SHqEB2MVho0jP81cT9bDEo5VUZOzkfwNC1BZ3qB8VQE",
    "tabName"=>"AbsolutePitch",

    /* Display */
    "pageTitle"=>"Quiz: Identify Absolute Pitches",
    "pageDescription"=>"Play a quick quiz game to test your ears' absolute pitch skills.",

    /* Optionals OR set as defaults 0 and "" respectively */
    "timeLeft"=>0,
    "cssOverride"=>".question {
        border: 1px solid black;
        background-color: white !important;
    }"
];

/* DEVELOPER READABILITY
This is for readability
______________________________________________________________________ */
$_SESSION["spreadsheet-link"] = $inputs["spreadsheetUrl"];
$connectToSpreadSheetUrlId = $inputs["spreadsheetId"];
$connectToTab = $inputs["tabName"];

$pageTitle = $inputs["pageTitle"];
$pageDesc = $inputs["pageDescription"];

$timeLeft = $inputs["timeLeft"];
$overrideCSS = $inputs["cssOverride"];

/* ENGINE
   Do not touch
______________________________________________________________________ */

// Check is initialized and not visited directly. If visited directly with no session, then initialize
// Error? gsheets accept only flat directory listing. It would have all folders then inside folder would have the quiz php files and credential creds.json files.
require_once "../../controllers/check-initialized.php";

// Check credential file correct. HINT: Named the same as PHP script and ends with ".creds.json"
$credsGsheetJSONFile = rawurldecode(basename(__FILE__, '.php') . ".creds.json");
file_exists($credsGsheetJSONFile) or die("Error: Failed to load credentials $credsGsheetJSONFile. Contact administrator");

// Load in Composer libraries
require_once $_SESSION["root_dir"] . '/vendor/autoload.php';

// Connect API with credentials
require_once "../../controllers/connect-gsheet.php";

// Render quiz page
require_once "../../controllers/render-quiz.php";
?>
```

Notice you are only changing the code under INPUTS. You must have the spreadsheet URL, spreadsheet ID, and the tab name you are loading the quiz from. The spreadsheet ID is from the spreadsheet link, eg. "https://docs.google.com/spreadsheets/d/__GOOGLE_SHEET_ID__/". I chose to make the ID manual rather than parse from the spreadsheet URL in case Google changes their URL scheme.

Optionally, you can enable a countdown timer if it's a timed quiz. When the time runs out, the quiz ends even if not all the questions are answered. You can also optionally add styling specific to your quiz (feel free to inspect the HTML of the quiz to figure out what classes and ID's you can use).

You cannot have folders inside folders. Here is a file tree example of folders aka categories and their respective quizzes:

```
____gsheets
| |____Medicine
| | |____Cardiovascular Pharmacology Quiz.php
| | |____Cardiovascular Pharmacology Quiz.creds.json
| | |____Infections.php
| | |____Infections.creds.json
| | |____Minimally Invasive Skills SATA.creds.json
| | |____Minimally Invasive Skills SATA.php
| | |____Respiratory Pathophysiology.php
| | |____Respiratory Pathophysiology.creds.json
| | |____Stroke NIHSS Signs Video Quiz.php
| | |____Stroke NIHSS Signs Video QUiz.creds.json
| |____Web Development
| | |____Javascript Flash Cards.php
| | |____Javascript Flash Cards.creds.json
| | |____MySQL True False Questions.php
| | |____MySQL True False Questions.creds.json

```

Along with the php files, you have the credential files which are named the same except they end with `.creds.json`; and that's the private key you download upon creating your service account with Google Sheet API enabled at your Developer Cloud Console. You would have to rename their .json key file into a file that matches the quiz php's filename, then end the file extension with `.creds.json` instead of `.php`.  There is a likelihood that your creds.json files end up having the same contents, however for ease-of-use and scalability, I decided to stick with this protocol.

The index.php will automatically list the quizzes under their respective folder names, and your quiz will open when clicked if the credential files are named correctly.

If you want a quiz group or folder to be hidden until you enter a password, your quiz group can be named with a minus, then password, then space, followed by your category name. The index.php front page won't list your folder until you enter the correct password. Here is an example tree folder. You can enter multiple passwords on the front page to show both these categories:

```
____gsheets
| |____-HolisticSecret Naturopath
| | |____Alternative health exercises for back pain.php
| | |____Alternative health exercises for back pain.creds.json
| | |____Herbs for Improved Energy Quiz.php
| | |____Herbs for Improved Energy Quiz.creds.json
| |____-WengSecret Personal Quizzes
| | |____Private Stock Trading Strategies.php
| | |____Private Stock Trading Strategies.creds.json
| | |____Code Numbers for Interdepartmental Communication.php
| | |____Code Numbers for Interdepartmental Communication.creds.json
| |____Web Development
| | |____Javascript Flash Cards.php
| | |____Javascript Flash Cards.creds.json
| | |____MySQL True False Questions.php
| | |____MySQL True False Questions.creds.json
```

In the above example, the Naturopath and Personal Quizzes don't show up until you unlock them with the passwords HolisticSecret and WengSecret. Other users wouldn't know there's anything to unlock because the categories don't appear. But the important key here is you have to precede the folder name with a minus and password.

Over at the Google sheet, I shared the sheet with the email address that is in the private json key (under client_email). You can also get the client email of the service account at Google Cloud Console.

The sheet must have the first row reserved for column headings. Then the columns should follow this format of columns in this order, and you can name the columns in whichever way makes sense:
- Optional Numbering for Google sheet sorting. Even if it's optional and you leave it blank at the rows, this column must exist because the code expects a certain number of columns from the left
- Title of the question that will appear on the quiz
- Text of the question, or image url, or sound clip url, depending on the type of question. In other words, the question value
- Instruction text that appears under your question on the quiz. Eg. "Select correct choice".
- Question Type column. This is the type of the question. It can be either Picture, Flash Card Absolute Pitch, Normal, etc. The quiz will look at this column cell and render the question in a specific format (for instance, whether it's a picture type question). If you have it as normal or any type the quiz doesn't recognize, it will render the question as is.
- Correct Choice. This is the index number 1,2,3,4,etc that corresponds to the columns to the right and that is the actually correct answer. The columns to the right will be choice columns. You can have multiple correct choices with comma separated values in the choice of Select all that apply (SATA):
Eg. 2
Eg. 2,3,4
Note for SATA, you do not write "SATA" for the Question Type column because you can have picture types or video types that are SATA. For example, you see a picture of a patients signs/symptoms and you select all the possible diagnoses.
- All other columns to the right are choices. You can have four columns if you want a standard multiple choice question with a,b,c,d choices. If you have two columns, you can name them True and False to make it a True/False question. Or with a two column you can name them "Got it correct" and "Got it wrong" to keep track of your flash cards but you should also have the Type column as "Flash Card" (End of quiz will review your wrong questions)


### Administering Multiple Choice Questions

Just have the choices entered. You can have more than 4 multiple choices or less. The app will figure out how many choices to render based on how many columns you fill in.

Question Type column can be blank or something generic like "Text". However, you may have multiple choice questions that are sound based or picture based which can be multiple choice but must have a specific question type like "Absolute Pitch".

### Administering Fill in the Blank Questions

Treat as a multiple choice question and your question text can be: "The ___ is the powerhouse of the cell". One of your choices will have the correct text.

### Administering True/False Question
Simply have two columns of answers only.


Correct Choice column is either 1 or 2
Choice 1: TRUE
Choice 2: FALSE

Leave other choice cells blank

Note that you're not forced to have the rest of the other questions (Google Sheet rows) to have only two choice columns filled, so you are not stuck with having to do True/False questions in a quiz. In fact, you can have as many choice columns as you want filled in any question/row.

### Administering Ranking Question

You ask the user to rank the choices in order.

Put the answers in their correct order from columns F to etc.

Correct choice column will be ignored. You can write "N/A"

Question Type: `Ranked`

### Administering Mix and Match Question
Question Text:
```
Side A
===
Side B
```
Question Type: `Mix and match`

Note that you're not forced to have the rest of the other questions (Google Sheet rows) to be Flash cards

### Administering Flash Card Question
Question Text:
```
Side A
====
Side B
```
Question Type: `Flash cards`

Make sure it's __4 equal signs__.

Note that you're not forced to have the rest of the other questions (Google Sheet rows) to be Flash cards

## :runner: Usage:

Opening the front page will show you all categorized quizzes you can take. If there are secret quizzes, then click "Passwords" to show them.

When taking a quiz, just follow the instructions on screen. If it's a timed quiz, you have a certain amount of time to finish the quiz. If you don't make it in time, the quiz will be considered done and the final score shows. Otherwise, the quiz finishes when you answered the final question.

You can select choices by clicking them or pressing your keyboard 1,2,3,4..9 depending on the number of multiple choices. A question with greater than 9 multiple choices will only support the keys 1,2,3,4...9 and you would have to click manually for the other choices. Usually questions that are Select all that apply (SATA) have that many choices.

## :triangular_ruler: Architecture:
I used composer to install the PHP Google API client. There was no documentation on Google's site on how to authenticate using the PHP Google API client, but I figured it out like this: The API client selected for Google Sheet API (versus other Google API's) that then connected gsheet/folder/quiz_name.php using credential file at quiz_name.creds.json at the same folder. The quiz name has the spreadsheet id and tab name necessarily to connect, and the Google Sheet has been shared to the email associated to the service account. The credential file was from my service account when I created a private json key and it downloaded upon finish creating it. The spreadsheet URL is just for the front facing when you click "Google Sheet" at the top bar, for your convenience.

Elsewhere, index.php lists all the quiz name.php files under their quiz group folder. The ones hidden are folder names preceded with a minus and password then space (eg...). PHP glob was recursively done on the gsheet folder to list the quiz groups and their respective quizzes but leaving out the folder names preceded with minus -. A "passwords" button was created for the user to enter password(s), then the code will glob recursively and append onto the index.php categories/quiz list based on the matched pattern of the user input against the directory.

When the user clicks a quiz at index.php, then all the spreadsheet tabs cell values will be dumped into a json form for javascript to consume. Over at javascript, it expects columns to have certain meanings associated with them based on the position of the column. In particularly, a column could for question type could be named Picture then the question text will be rendered differently, in particular with an img tag.  The column Correct choice will let the app listen for click even and compare if the index of the clicked answer matches the correct choice index from the spreadsheet (count starts at 1). In addition, that correct choice column can have comma separated indexes instead of a single index, then the question becomes select all that apply and a button renders that let you finish selecting multiple SATA choices. It can also with some common sense tweaking you can make it into a True/False question or Flash Card question. But if you have a Ranked question (order the steps in the correct sequence) or a Mix and match (match pairs of choices), then the correct choice column won't matter (because you just input the choice columns in order at the Google Sheet, or because each choice column is in a flash card format).

The question type can influence how the question is rendered and how the choices are rendered. The app looks at the QUestion Type column in Google Sheet. Then the app uses the logic at public/assets/formatters/format-question-text.js and public/assets/formatters/format-choices.js. 

Most of the logic is happening at quiz.js which refers to format-question-text.js and format-choices.js. There is an templateContext that will add smaller handlebar templates into the handlebars question box template (particularly theey are the question text section, question choices section*, and an optional choice confirm button). The templateContext is also for filling in variable values into the Handlebars template. The question text templater saves the correct HTML into the templateContext's questionText key.

When it comes to formatting choices, there's a modeling step before the templating step which is an injection mechanism. The modeling step will convert a simple choices array into an array of objects that fit the Handlebars rendering logic at the choices subtemplate (from simple multiple choices DOM to complicated mix and matching DOM). Then the templating step would have saved the correct HTML into the templateContext's choices key, however because this choices subtemplate will need to be compiled with the choices model, either we could register partials or inject into the template before compiling. I chose to inject the subtemplate into a copy of the Handlebars question box template because registering partials will disrupt the design pattern I already have. Finally, there is one more subtemplate and that's the button to confirm your choices; when it comes to multiple choices you don't need a button; but you do need a confirmation button for selecting choices in SATA, Ranking, Mix and Match, etc. That button is custom to the type of choices because there's text that reminds the user how they finished interacting with the choices. The template switching logic for the choice confirm button is also in format-choices.js because it directly relates to handling the choices the user interacted with.

After the Handlebars template is rendered as HTML, then the formatter might redraw the DOM to make it fit the contents, etc if necessary. Then it will hydrate the choices UI to be interactable using format-choices' hydrateChoices method. Depending on the question type, you may have multiple choices that click, or you may have a ranking question where you drag and drop the choices into the correct sequence.

Was initially vanilla javascript. However, in order to speed up development time when it became more UI intensive with various drag and drop logic, I switched over to jQuery with jQuery UI. May convert back to full vanilla javascript in the future and then minify and bundle assets to gain a slight performance boost.

In terms of the Mix and Match choices, one column are the draggable choices. The other column are the droppable areas. Each droppable area has a smaller area that's actually the droparea. The correct matches are already paired row at each row in .mix-match-container, however after rendering, we repaint by shuffling the draggables between the rows. The classes are thus .draggable, .droppable, .droparea, and .mix-match-container.

## :crystal_ball: Future version
- Review wrong answers when finished
- Top scores