# AGENTS_LOOP — Stocks Trainer → Quiz Questions

Drain teachable facts from `context-stocks-trainer` into local quizzes under **`gsheets/Business - Investment Markets/`**.

Companions:

- Skill: [`.cursor/skills/extract-stocks-trainer-quizzes/SKILL.md`](.cursor/skills/extract-stocks-trainer-quizzes/SKILL.md)
- Graphics: [`.cursor/skills/extract-stocks-trainer-quizzes/graphics-sources.md`](.cursor/skills/extract-stocks-trainer-quizzes/graphics-sources.md) (trainer SVGs + Wikimedia Commons candlestick library)
- State: [`.agents/stocks-trainer-quiz-state.json`](.agents/stocks-trainer-quiz-state.json)
- CSV rules: [`README-Question-Types.md`](README-Question-Types.md) · [`gsheets/Test/sample-quiz.csv`](gsheets/Test/sample-quiz.csv)

---

## How to run

```text
/loop stocks trainer quizzes using AGENTS_LOOP-Stocks-Trainer-Quizzes.md
```

**Drain mode (default):** On each tick, finish as many unfinished queue items as context allows (prefer completing the whole queue in-session). Stop when `status` is `Done`.

**Before starting**

1. Read the skill + state.
2. Do not ask the user for naming preferences — category is **Business - Investment Markets**.
3. Hard-stop on Done or after 12 failed consecutive ticks.

---

## Queue (strict order)

From `.agents/stocks-trainer-quiz-state.json` → `queue_order`:

1. `scaffold` — folder, icon, state wiring
2. `equities` — traditional retail stocks
3. `futures`
4. `options-context`
5. `crypto`
6. `forex`
7. `market-types-bridge` — equities vs other classes
8. `candlestick-patterns`
9. `technical-indicators`
10. `financial-statements`
11. `news-decision-process`

---

## Loop prompt

```markdown
# OBJECTIVE
Extract Stock Trainer facts into quiz-gsheet quizzes until `.agents/stocks-trainer-quiz-state.json` reports Done.

**Done:** Every id in `queue_order` is `done`; each quiz PHP+CSV exists with row minimums from the skill; `icons.config.js` includes Business - Investment Markets.

**Done (per tick):** Advance one or more pending queue items to done (drain mode), or recover from a clear error with a next action. When Done, stop the loop (kill sleeper; do not re-arm).

# CONTEXT
- Skill: `.cursor/skills/extract-stocks-trainer-quizzes/SKILL.md`
- State: `.agents/stocks-trainer-quiz-state.json`
- Source app: `context-stocks-trainer/` (read libs; do not modify trainer unless fixing a factual typo blocking extraction)
- Output: `gsheets/Business - Investment Markets/`
- Do not invent LIVE feeds, broker order routing, or options Greeks engines
- For candlesticks (and other recognition topics): resolve Picture graphics via the skill’s Educational graphics section + `graphics-sources.md` (trainer SVGs first, then Wikimedia Commons Candlestick charts, then other open learning diagram libraries)

# STEP-BY-STEP CADENCE
1. **Orient** — Read state; list pending ids in `queue_order`.
2. **If Done** — Confirm files on disk; stop loop; brief completion note. Do not re-arm.
3. **Extract** — Follow the skill for each pending topic you can finish this tick. Prefer draining all remaining topics. Add Picture rows when educational graphics resolve.
4. **Verify** — CSV header present; Correct Choice indices valid; Picture cells are bare URLs; polymorphic types OK; no duplicate near-identical stems in the same file.
5. **Update state** — Mark topics done; update `files`, `question_counts`, `next_action`, `last_updated_iso`, `status`.
6. **Re-arm** — Only if status ≠ Done. Dynamic wake ~15–45s with prompt payload pointing at this file.

# STOP
- status == Done → kill loop PID; do not schedule another wake
- 12 consecutive failures → stop and report blocker
```

---

## Expected outputs

| Quiz | Files |
|------|--------|
| Equities (Stocks) | `Equities (Stocks).php` + `equities-stocks.csv` |
| Futures | `Futures.php` + `futures.csv` |
| Options Context | `Options Context.php` + `options-context.csv` |
| Crypto | `Crypto.php` + `crypto.csv` |
| Forex | `Forex.php` + `forex.csv` |
| Market Types Bridge | `Market Types Bridge.php` + `market-types-bridge.csv` |
| Candlestick Patterns | `Candlestick Patterns.php` + `candlestick-patterns.csv` |
| Technical Indicators | `Technical Indicators.php` + `technical-indicators.csv` |
| Financial Statements | `Financial Statements.php` + `financial-statements.csv` |
| News & Decisions | `News and Decisions.php` + `news-and-decisions.csv` |
| Trading Basics | `Trading Basics.php` (Google Sheet tab `Trader`; moved from Business - Trader) |
