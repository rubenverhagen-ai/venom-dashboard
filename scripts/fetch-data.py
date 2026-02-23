#!/usr/bin/env python3
"""
VENOM — Live Data Fetcher
Runs via GitHub Actions every 15 min.
Writes live-data.json to repo root.
"""
import json, datetime, os, hmac, hashlib, time, urllib.request, urllib.error
import yfinance as yf

# ── Portfolio positions (buy prices) ──────────────────────────────
POSITIONS = [
    {"symbol": "NVDA",  "name": "Nvidia",               "shares": 4, "entry_usd": 191.125, "entry_eur": 162.01},
    {"symbol": "GOOGL", "name": "Alphabet",              "shares": 1, "entry_usd": 314.19,  "entry_eur": 266.33},
    {"symbol": "TSM",   "name": "TSMC",                  "shares": 1, "entry_usd": 368.62,  "entry_eur": 312.47},
    {"symbol": "AMD",   "name": "Advanced Micro Devices","shares": 2, "entry_usd": 196.18,  "entry_eur": 166.25},
    {"symbol": "MSFT",  "name": "Microsoft",             "shares": 1, "entry_usd": 397.23,  "entry_eur": 336.72},
]
TOTAL_INVESTED_EUR = 1896.06
CASH_EUR           = 95.61

# ── Bitvavo API ───────────────────────────────────────────────────
BITVAVO_KEY    = os.environ.get("BITVAVO_API_KEY", "")
BITVAVO_SECRET = os.environ.get("BITVAVO_API_SECRET", "")

def bitvavo_request(endpoint):
    """Authenticated Bitvavo REST call. Returns parsed JSON or None on error."""
    if not BITVAVO_KEY or not BITVAVO_SECRET:
        return None
    ts  = str(int(time.time() * 1000))
    msg = ts + "GET" + "/v2" + endpoint
    sig = hmac.new(BITVAVO_SECRET.encode(), msg.encode(), hashlib.sha256).hexdigest()
    headers = {
        "Bitvavo-Access-Key":       BITVAVO_KEY,
        "Bitvavo-Access-Signature": sig,
        "Bitvavo-Access-Timestamp": ts,
        "Bitvavo-Access-Window":    "10000",
    }
    req = urllib.request.Request(
        "https://api.bitvavo.com/v2" + endpoint,
        headers=headers
    )
    try:
        with urllib.request.urlopen(req, timeout=10) as r:
            return json.loads(r.read())
    except Exception as e:
        print(f"  Bitvavo error ({endpoint}): {e}")
        return None

def fetch_bitvavo():
    """Fetch Bitvavo balances + EUR prices. Returns dict ready for live-data.json."""
    balances_raw = bitvavo_request("/balance")
    if balances_raw is None:
        return {"available": False, "reason": "API not reachable"}
    if isinstance(balances_raw, dict) and "error" in balances_raw:
        return {"available": False, "reason": balances_raw.get("error", "API error")}

    holdings = []
    total_eur = 0.0

    for b in balances_raw:
        sym   = b.get("symbol", "")
        avail = float(b.get("available", 0))
        inord = float(b.get("inOrder", 0))
        total = avail + inord
        if total < 0.000001:
            continue

        if sym == "EUR":
            holdings.append({
                "symbol": "EUR", "name": "Euro",
                "amount": round(total, 2),
                "price_eur": 1.0,
                "value_eur": round(total, 2),
            })
            total_eur += total
            continue

        # Fetch current EUR price
        market  = f"{sym}-EUR"
        ticker  = bitvavo_request(f"/ticker/price?market={market}")
        if ticker and "price" in ticker:
            price_eur = float(ticker["price"])
        else:
            # Try 24h ticker
            ticker24 = bitvavo_request(f"/ticker/24h?market={market}")
            price_eur = float(ticker24.get("last", 0)) if ticker24 else 0.0

        value_eur = total * price_eur
        total_eur += value_eur

        holdings.append({
            "symbol":    sym,
            "name":      sym,
            "amount":    round(total, 8),
            "price_eur": round(price_eur, 2),
            "value_eur": round(value_eur, 2),
        })

    # Sort by value descending
    holdings.sort(key=lambda x: x["value_eur"], reverse=True)

    return {
        "available":  True,
        "total_eur":  round(total_eur, 2),
        "holdings":   holdings,
    }


# ── Fetch EUR/USD exchange rate ───────────────────────────────────
fx_ticker  = yf.Ticker("EURUSD=X")
fx_hist    = fx_ticker.history(period="1d")
fx_eurusd  = float(fx_hist["Close"].iloc[-1]) if not fx_hist.empty else 1.08

# ── Fetch stock prices ────────────────────────────────────────────
symbols = [p["symbol"] for p in POSITIONS]
tickers = yf.Tickers(" ".join(symbols))

results = []
total_value_eur = 0.0

for p in POSITIONS:
    sym  = p["symbol"]
    info = tickers.tickers[sym].history(period="2d")
    if len(info) >= 2:
        prev_close = float(info["Close"].iloc[-2])
        cur_price  = float(info["Close"].iloc[-1])
        change_pct = (cur_price - prev_close) / prev_close * 100
    elif len(info) == 1:
        cur_price  = float(info["Close"].iloc[-1])
        change_pct = 0.0
    else:
        cur_price  = p["entry_usd"]
        change_pct = 0.0

    cur_price_eur = cur_price / fx_eurusd
    invested_eur  = p["entry_eur"] * p["shares"]
    value_eur     = cur_price_eur * p["shares"]
    pnl_eur       = value_eur - invested_eur
    pnl_pct       = pnl_eur / invested_eur * 100
    total_value_eur += value_eur

    results.append({
        "symbol":      sym,
        "name":        p["name"],
        "shares":      p["shares"],
        "entry_usd":   round(p["entry_usd"], 2),
        "entry_eur":   round(p["entry_eur"], 2),
        "current_usd": round(cur_price, 2),
        "current_eur": round(cur_price_eur, 2),
        "invested_eur":round(invested_eur, 2),
        "value_eur":   round(value_eur, 2),
        "pnl_eur":     round(pnl_eur, 2),
        "pnl_pct":     round(pnl_pct, 2),
        "change_today_pct": round(change_pct, 2),
    })

total_pnl_eur = total_value_eur - TOTAL_INVESTED_EUR
total_pnl_pct = total_pnl_eur / TOTAL_INVESTED_EUR * 100

# ── Fetch Bitvavo crypto ──────────────────────────────────────────
print("Fetching Bitvavo...")
crypto_data = fetch_bitvavo()
if crypto_data.get("available"):
    print(f"  Bitvavo ✅ — total €{crypto_data['total_eur']:.2f}, {len(crypto_data['holdings'])} holdings")
else:
    print(f"  Bitvavo ⚠️  — {crypto_data.get('reason','unavailable')}")

# ── Write JSON ────────────────────────────────────────────────────
data = {
    "updated": datetime.datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ"),
    "portfolio": {
        "total_invested_eur": round(TOTAL_INVESTED_EUR, 2),
        "total_value_eur":    round(total_value_eur, 2),
        "cash_eur":           round(CASH_EUR, 2),
        "total_with_cash":    round(total_value_eur + CASH_EUR, 2),
        "pnl_eur":            round(total_pnl_eur, 2),
        "pnl_pct":            round(total_pnl_pct, 2),
        "fx_eurusd":          round(fx_eurusd, 4),
        "positions":          results,
    },
    "crypto": crypto_data,
}

with open("live-data.json", "w") as f:
    json.dump(data, f, indent=2)

print(f"✅ live-data.json updated — portfolio €{total_value_eur:.2f} ({total_pnl_pct:+.2f}%)")
