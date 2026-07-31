# Question Types

Main question types supported by quiz-gsheet. Set the **Question Type** column (column E) so the app knows how to render each row. Rows in the same quiz can use different formats.

Examples for each type: `gsheets/Test/sample-quiz.csv` (loaded by `gsheets/Test/Sample Quiz.php`).

## Main types

1. **Multiple choice** — Click one answer. Any number of choice columns; blank or generic types like `Text` / `Normal` also render as plain-text multiple choice.
2. **Fill in the blank** — Same interaction as multiple choice, with blanks in the question text (for example `The ___ is the powerhouse of the cell`). Put the correct wording in one choice column and distractors in the others. A separate `Fill in the blank` Question Type value is optional.
3. **True/False** — Multiple choice with only two choices (`True` / `False`). Correct Choice is `1` or `2`. Leave other choice cells blank.
4. **SATA (Select all that apply)** — Enabled when Correct Choice has **comma-separated** indices (for example `1,2,4`). You do **not** need `SATA` in the Question Type column; use `Multiple Choice`, `Picture`, `Video`, or another media type as needed.
5. **Flash cards** — Question Type: `Flash cards`. Front and back in the question cell, separated by four equal signs (`====`).
6. **Ranking / ordering** — Question Type: `Ranked`. Drag choices into the correct sequence. Put answers in correct order in the choice columns; Correct Choice can be `N/A` / `Na`.
7. **Mix and match** — Question Type: `Mix and match`. Each matching pair uses a flash-card-style cell (`Side A` / `===` / `Side B`). Extra unpaired choices can act as distractors.
8. **Media / special prompts** — `Picture`, `Video`, `Absolute pitch`, `Relative pitch` change how the prompt renders; choices can still be multiple choice or SATA.

## Nuances

- **SATA + picture or video** — Use `Picture` or `Video` in Question Type for the media, and comma-separated numbers in Correct Choice for SATA. The confirm button appears because of the correct-answer format, not a `SATA` type name.
- **Plain text questions** — If Question Type is blank or unrecognized, the question renders as plain text with the usual clickable choice list.
- **Polymorphic rows** — You are not forced to use the same number of choice columns or the same type on every row.
