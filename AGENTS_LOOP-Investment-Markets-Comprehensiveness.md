# AGENTS_LOOP — Investment Markets Comprehensiveness

Two-phase drain for `gsheets/Business - Investment Markets/`.

Companions:

- Skill: [`.cursor/skills/extract-stocks-trainer-quizzes/SKILL.md`](.cursor/skills/extract-stocks-trainer-quizzes/SKILL.md)
- State: [`.agents/investment-markets-comprehensiveness-state.json`](.agents/investment-markets-comprehensiveness-state.json)
- Source app: `context-stocks-trainer/`

---

## How to run

```text
/loop investment markets comprehensiveness using AGENTS_LOOP-Investment-Markets-Comprehensiveness.md
```

**Drain mode:** Finish the active phase (and continue into the next) until `status` is `Done`. Prefer completing both phases in-session.

---

## Phases (strict order)

### Phase A — vs Stock Trainer app

Queue: `phase_a_order`

1. Map trainer surfaces (literacy, patterns, overlays, quiz groups, packs, paths, cases, tips)
2. Diff each quiz CSV against those facts
3. Add missing app-faithful rows (SAMPLE framing)
4. Mark Phase A done

**Done (A):** Every trainer literacy term, pattern id, overlay id, thinking-mode tip, SAMPLE pack teaching note, and major path/case pack concept has at least one quiz row that tests it (or an explicit `wontfix` with reason in state).

### Phase B — vs domain fundamentals (expert TOC)

Queue: `phase_b_order`

1. Diff each market quiz against a standard fundamentals TOC (beyond the app)
2. Add missing educational rows (still no LIVE brokerage / Greeks engine / investment advice)
3. Mark Phase B done

**Done (B):** No P0/P1 TOC gaps remain for Equities, Futures, Options, Crypto, Forex, Bridge, Candles, Indicators, Statements, News.

---

## Loop prompt

```markdown
# OBJECTIVE
Raise Business - Investment Markets quiz comprehensiveness until both Phase A (app) and Phase B (domain TOC) are Done.

**Done (global):** `.agents/investment-markets-comprehensiveness-state.json` → `status: "Done"`.

**Done (per tick):** Advance one or more pending phase steps; fill gaps found; update state. When Done, stop (do not re-arm).

# CONTEXT
- Quizzes: `gsheets/Business - Investment Markets/*.csv`
- App facts: `context-stocks-trainer/src/lib/*`
- Keep SAMPLE/educational; no LIVE fills; options = no Greeks engine
- Prefer appending chapter-tagged rows; dedupe on question stem

# CADENCE
1. Orient on state `active_phase` + next pending id
2. If Done → stop
3. Audit → write gap list into state
4. Patch CSVs for those gaps
5. Validate choice indices
6. Update counts / phase status
7. Re-arm only if not Done (dynamic ~20–40s)

# STOP
status == Done → kill sleeper; do not re-arm
```
