# Educational graphics sources (Business - Investment Markets)

Libraries and URLs for Picture-type quiz rows. Prefer free educational diagrams; never LIVE broker screenshots.

## 1. Trainer-bundled (first choice)

Source: `context-stocks-trainer/public/patterns/`  
Served copy: `public/assets/patterns/markets/`

| Pattern | Local quiz URL |
|---------|----------------|
| Bullish Engulfing | `../../public/assets/patterns/markets/bullish-engulfing.svg` |
| Morning Star | `../../public/assets/patterns/markets/morning-star.svg` |
| Head and Shoulders | `../../public/assets/patterns/markets/head-shoulders.svg` |
| Falling Wedge | `../../public/assets/patterns/markets/falling-wedge.svg` |

Copy command when missing:

```bash
mkdir -p public/assets/patterns/markets
cp context-stocks-trainer/public/patterns/*.svg public/assets/patterns/markets/
```

## 2. Wikimedia Commons — Candlestick charts

Browse: https://commons.wikimedia.org/wiki/Category:Candlestick_charts  

Resolve upload URL:

```bash
curl -sI -L -o /dev/null -w '%{url_effective}\n' \
  'https://commons.wikimedia.org/wiki/Special:FilePath/Candlestick_pattern_hammer.svg'
```

### Verified upload URLs (CC0 / Commons educational SVGs)

| Teaching use | File | Upload URL |
|--------------|------|------------|
| Hammer | `Candlestick_pattern_hammer.svg` | https://upload.wikimedia.org/wikipedia/commons/e/ec/Candlestick_pattern_hammer.svg |
| Bullish Engulfing | `Candlestick_pattern_bullish_engulfing.svg` | https://upload.wikimedia.org/wikipedia/commons/2/23/Candlestick_pattern_bullish_engulfing.svg |
| Bearish Engulfing | `Candlestick_pattern_bearish_engulfing.svg` | https://upload.wikimedia.org/wikipedia/commons/0/0c/Candlestick_pattern_bearish_engulfing.svg |
| Morning Star (Commons spelling “Moring”) | `Candlestick_pattern_bullish_Moring_Star.svg` | https://upload.wikimedia.org/wikipedia/commons/9/9d/Candlestick_pattern_bullish_Moring_Star.svg |
| Morning Doji Star | `Candlestick_pattern_bullish_Morning_Doji_Star.svg` | https://upload.wikimedia.org/wikipedia/commons/0/06/Candlestick_pattern_bullish_Morning_Doji_Star.svg |

Search Commons for more trainer-aligned names: Doji, Shooting Star, Inverted Hammer, Evening Star, Three Line Strike, Rising Three Methods, etc. Re-resolve FilePath before writing the CSV (hashes are stable once resolved).

## 3. Other educational diagram classes

| Need | Where to look |
|------|----------------|
| Candle / OHLC anatomy | Commons files matching `Candlestick chart scheme` |
| Indicator teaching diagrams | Commons technical-analysis diagrams with clear CC0/CC-BY; skip branded broker pages |
| Market microstructure comics | Only if license is explicit and content matches trainer facts |

## License / safety checklist

- [ ] License is CC0, public domain, or clearly reusable CC-BY (note attribution in Hint if BY)
- [ ] Diagram is schematic/educational — not a LIVE quote screenshot
- [ ] Filename/pattern matches the fact being tested
- [ ] URL returns HTTP 200 when resolved
- [ ] Question cell contains **only** the image URL for `Picture` type


### Additional verified upload URLs (enrichment pass)

| Teaching use | File | Upload URL |
|--------------|------|------------|
| Doji Star | `Candlestick_pattern_Doji_Star.svg` | https://upload.wikimedia.org/wikipedia/commons/7/78/Candlestick_pattern_Doji_Star.svg |
| Bullish Doji Star | `Candlestick_pattern_bullish_Doji_Star.svg` | https://upload.wikimedia.org/wikipedia/commons/6/6f/Candlestick_pattern_bullish_Doji_Star.svg |
| Morning Doji Star | `Candlestick_pattern_bullish_Morning_Doji_Star.svg` | https://upload.wikimedia.org/wikipedia/commons/0/06/Candlestick_pattern_bullish_Morning_Doji_Star.svg |
| Evening Star | `Candlestick_pattern_bearish_Evening_Star.svg` | https://upload.wikimedia.org/wikipedia/commons/5/5b/Candlestick_pattern_bearish_Evening_Star.svg |
| Shooting Star | `Candlestick_pattern_Shooting_Star.svg` | https://upload.wikimedia.org/wikipedia/commons/a/a7/Candlestick_pattern_Shooting_Star.svg |
| Candle anatomy 01 | `Candlestick_chart_scheme_01-en.svg` | https://upload.wikimedia.org/wikipedia/commons/9/92/Candlestick_chart_scheme_01-en.svg |
| Candle anatomy 02 | `Candlestick_chart_scheme_02-en.svg` | https://upload.wikimedia.org/wikipedia/commons/8/85/Candlestick_chart_scheme_02-en.svg |
