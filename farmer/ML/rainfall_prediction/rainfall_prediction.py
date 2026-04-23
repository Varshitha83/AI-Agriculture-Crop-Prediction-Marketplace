import pandas as pd
import sys
import os
import calendar

# Load the dataset into a dataframe (path relative to this script)
base_dir = os.path.dirname(os.path.abspath(__file__))
csv_path = os.path.join(base_dir, 'rainfall_in_india_1901-2015.csv')
df = pd.read_csv(csv_path)


def month_abbr_to_index(mon):
    mapping = {'JAN':1,'FEB':2,'MAR':3,'APR':4,'MAY':5,'JUN':6,'JUL':7,'AUG':8,'SEP':9,'OCT':10,'NOV':11,'DEC':12}
    return mapping.get(mon.strip().upper(), None)


def find_subdivision_match(name):
    if not name:
        return None
    name_up = str(name).strip().upper()
    # exact match
    mask = df['SUBDIVISION'].astype(str).str.upper() == name_up
    if mask.any():
        return df[mask]
    # try contains
    mask2 = df['SUBDIVISION'].astype(str).str.upper().str.contains(name_up)
    if mask2.any():
        return df[mask2]
    return None


def predict_for_date(state, district, date_str, day_name, month_abbr, year):
    # Try district first (if dataset had district entries), then state
    # Note: current dataset is state/subdivision monthly totals; district-level daily prediction requires district/daily data.
    # We'll attempt district match, otherwise fall back to state-level averages.

    # prefer district if provided
    df_match = None
    if district:
        df_match = find_subdivision_match(district)
    if df_match is None and state:
        df_match = find_subdivision_match(state)

    if df_match is None or df_match.empty:
        return f"No historical data found for '{district or state}'. Unable to predict."

    # Ensure month is valid
    if month_abbr is None:
        return f"Invalid month value: {month_abbr}"

    if month_abbr not in df_match.columns:
        return f"Month {month_abbr} not available in dataset for the selected subdivision."

    # compute average monthly rainfall
    avg_month = df_match[month_abbr].dropna().mean()
    if pd.isna(avg_month):
        return f"No numeric rainfall data for {month_abbr} in the selected subdivision."

    # estimate daily rainfall as monthly average divided by days in month
    try:
        year_int = int(year)
    except Exception:
        year_int = 2020
    mon_idx = month_abbr_to_index(month_abbr)
    if mon_idx is None:
        return f"Invalid month abbreviation: {month_abbr}"
    days_in_month = calendar.monthrange(year_int, mon_idx)[1]
    estimated_daily = float(avg_month) / days_in_month

    # Simple heuristic for probability: if monthly avg is > threshold, say likely
    likely = float(avg_month) > 50  # heuristic

    # Heuristic for time of day: monsoon months more likely afternoons/evenings
    if mon_idx in (6,7,8,9):
        time_est = 'Afternoon - Evening (approx. 15:00-21:00)'
    else:
        time_est = 'Morning - Midday (approx. 06:00-12:00)'

    rain_text = f"Estimated rainfall on {date_str}: {estimated_daily:.2f} mm (based on historical {month_abbr} averages)."
    if likely:
        rain_text += f" Rain is likely in that period. Estimated time: {time_est}."
    else:
        rain_text += f" Rain unlikely; if it occurs, estimated time: {time_est}."

    rain_text += "\nNote: This is a state-level historical estimate (dataset contains monthly totals). For accurate district/day/time predictions, supply district-level daily historical data and a trained model."
    return rain_text


# Parse input arguments (support both legacy and extended signature)
args = sys.argv[1:]
state = args[0] if len(args) > 0 else ''
district = args[1] if len(args) > 1 else ''
date_str = args[2] if len(args) > 2 else ''
day_name = args[3] if len(args) > 3 else ''
month_abbr = args[4] if len(args) > 4 else None
year = args[5] if len(args) > 5 else ''

if month_abbr is None:
    # fallback:try to use current month
    month_abbr = ''

result = predict_for_date(state, district, date_str or f"{year}-{month_abbr}", day_name, month_abbr, year)
print(result)


