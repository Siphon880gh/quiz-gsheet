---
name: extract-stocks-trainer-quizzes
description: >-
  Extracts teachable facts from context-stocks-trainer into quiz-gsheet local CSV
  quizzes under Business - Investment Markets (one quiz per market type plus literacy packs).
  Finds candlestick and other educational graphics from trainer assets and known
  learning libraries (e.g. Wikimedia Commons) for Picture questions when needed.
  Use when converting Stock Trainer curriculum into quiz questions, draining
  AGENTS_LOOP-Stocks-Trainer-Quizzes.md, or adding Equities/Futures/Options/Crypto/Forex quizzes.
disable-model-invocation: true
---

# Extract Stocks Trainer → Quiz GSheet

## Goal

Turn **all** durable facts in `context-stocks-trainer/` into graded quiz rows for this app.

**Category folder:** `gsheets/Business - Investment Markets/`  
(Name chosen over “Money Raising”: content is market literacy & trading instruments, not capital fundraising. Includes moved `Trading Basics.php` from the former Business - Trader category.)

## Non-negotiables

- Source of truth: trainer libs under `context-stocks-trainer/src/lib/` (+ literacy / quiz / pack notes). Do **not** invent LIVE market claims, brokerage advice, or Greeks/pricing engines.
- Prefer **local CSV** (`spreadsheetLocal`) + `quiz-engine.php` (same pattern as `gsheets/Medical - _ACLS/`).
- Facts only from the trainer. Paraphrase for quiz stems; keep meanings faithful.
- Mix question types: Multiple Choice, Fill in the blank, True False, SATA, Flash card, Ranked, Mix and match, **Picture** (see `README-Question-Types.md` / `gsheets/Test/sample-quiz.csv`).
- When a fact is recognition-heavy (candles, chart schemes, indicator shapes), prefer a **Picture** row using an educational graphic (see Educational graphics).
- SAMPLE/educational framing when the trainer labels content SAMPLE/STYLIZED.
- One quiz file pair per queue topic: `Name.php` + `name.csv` (slug csv).

## Source map (read these)

| Topic | Primary sources |
|-------|-----------------|
| Equities | `literacyTerms.ts`, `EQUITY_LITERACY_QUESTIONS` in `quizData.ts`, `EQUITY_SAMPLE_PACKS` notes |
| Futures | `literacyTerms` futures entry, `FUTURE_SAMPLE_PACKS`, Market Types loop docs |
| Options context | `literacyTerms` options-context, `OPTION_CONTEXT_SAMPLE_PACKS` notes |
| Crypto | `literacyTerms` crypto, crypto packs in `markets.ts` |
| Forex | `literacyTerms` forex, `FOREX_SAMPLE_PACKS` |
| Market bridge | Equities-vs-other wording in `literacyTerms`, `AGENTS_LOOP-Market-Types.md` |
| Candlesticks | `patterns.ts`, pattern prompts in `quizData.ts`, **+ educational pattern graphics** |
| Indicators | `overlays.ts`, `INDICATOR_QUIZ_QUESTIONS`, **+ chart-scheme graphics when useful** |
| Financials | `financialSnapshots.ts`, `FINANCIAL_LITERACY_QUESTIONS`, `FINANCIAL_DRILLS_QUESTIONS` |
| News / decisions | `NEWS_LITERACY_QUESTIONS`, `thinkingModeTips.ts`, case process copy |

## Educational graphics

Use graphics when the learner should **identify** a pattern/shape, not only recall a definition. Full lookup tables: [graphics-sources.md](graphics-sources.md).

### Priority order

1. **Trainer-bundled art** — `context-stocks-trainer/public/patterns/*.svg`  
   Copy into `public/assets/patterns/markets/` (keep filenames). In CSV Picture cells use app-relative URLs:  
   `../../public/assets/patterns/markets/<file>.svg`
2. **Wikimedia Commons — Candlestick charts** — https://commons.wikimedia.org/wiki/Category:Candlestick_charts  
   Prefer SVG files titled `Candlestick pattern …` (often CC0). Resolve stable upload URLs via:  
   `https://commons.wikimedia.org/wiki/Special:FilePath/<Exact_File_Name.svg>`  
   then paste the final `https://upload.wikimedia.org/wikipedia/commons/…` URL into the Question cell.
3. **Other open learning diagram libraries** (same rules: free reuse, educational, no LIVE broker branding):  
   - Wikimedia chart-scheme diagrams (OHLC/candle anatomy)  
   - Public-domain / CC0 technical-analysis teaching SVGs already on Commons  
   - Optionally vendor-neutral open teaching sets if license is clearly OK (document the source URL in Hint)

### Picture row rules

- Question Type: `Picture`
- Question column: **only** the image URL (no prose in that cell — instruction carries the ask)
- Instruction: e.g. `Identify the candlestick pattern shown.`
- Choices: pattern names / distractors aligned with `patterns.ts`
- Hint: optional short teaching cue + source tag (`Trainer SVG` or `Wikimedia CC0`)
- Prefer SVG; PNG/JPG OK if SVG missing
- Do **not** hotlink paid chart platforms, broker marketing, or unlabeled screenshots
- If FilePath 404s, search Commons category; skip the Picture row rather than inventing art

### When to add Picture rows

| Topic | Prefer Picture when… |
|-------|----------------------|
| Candlestick Patterns | Pattern has a clear diagram (hammer, engulfing, morning star, H&S, wedge, …) |
| Technical Indicators | Anatomy/scheme diagram helps (candle anatomy → indicator overlays stay text unless a clear free diagram exists) |
| Market types | Rarely — only if a free labeled instrument diagram clarifies a fact |

Aim for **≥4 Picture rows** in Candlestick Patterns when graphics resolve; other quizzes stay text-first unless a graphic clearly improves recognition.

## CSV schema

Header row required:

```csv
,Title,Question,Instruction,Question Type,Correct Choice,Choice 1,Choice 2,Choice 3,Choice 4,Choice 5,Choice 6,Choice 7,Hint
```

Rules:

- Col A: blank or sort index; use `-1` to hide.
- Col B: short subcategory (`Concepts`, `Application`, `Picture`, etc.).
- Col E: `Multiple Choice` | `Fill in the blank` | `True False` | `Flash card` | `Ranked` | `Mix and match` | `Picture`.
- Col F: `1`-based Choice index, comma-separated for SATA, `N/A` for Ranked / Mix and match.
- Quote fields that contain commas; flash/mix cells use `\n====\n` or `\n===\n` inside one CSV field.
- Put a one-line teaching cue in `Hint` when useful (collapsed in UI).
- Target **≥12 rows** per market-type quiz; **≥10** for supporting packs. Prefer coverage over padding.

## PHP quiz stub

Copy structure from `gsheets/Test/Sample Quiz.php`:

```php
$inputs = [
    "spreadsheetLocal"=>"<file>.csv",
    "pageTitle"=>"Quiz: …",
    "pageDescription"=>"… facts from Stock Trainer (SAMPLE educational).",
    "timeLeft"=>0,
    "cssOverride"=>".question { border: 1px solid black; background-color: white !important; }"
];
// … session + titles …
require_once "../../controllers/quiz-engine.php";
```

## Per-topic workflow

1. Read state: `.agents/stocks-trainer-quiz-state.json`
2. Take the next `pending` id in `queue_order`
3. Mine facts from the source map (and any linked docs)
4. For recognition topics: resolve graphics via Educational graphics + [graphics-sources.md](graphics-sources.md); copy trainer SVGs if missing under `public/assets/patterns/markets/`
5. Write/append CSV + ensure PHP wrapper exists
6. Dedupe against existing rows in that CSV (same stem ≈ skip)
7. Spot-check: Correct Choice indices match Choice columns; Picture cells are bare URLs; SATA uses commas; Ranked order correct
8. Update state: topic → `done`, bump counts, set `next_action`
9. Icon: ensure `icons.config.js` has `Business - Investment Markets` if missing

## Done criteria

All `queue_order` ids are `done`, every listed PHP+CSV pair exists with ≥ minimum rows, icon entry present. Then set `status: "Done"` and stop the loop (do not re-arm).
