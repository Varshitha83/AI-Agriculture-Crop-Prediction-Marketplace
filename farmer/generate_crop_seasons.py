import pandas as pd
import json
import os

# Read the crop prediction CSV
df = pd.read_csv(os.path.join(os.path.dirname(__file__), 'ML', 'crop_prediction', 'preprocessed2.csv'))

# Create crop-season mapping (crops by season)
crop_season = {}
for season in sorted(df['Season'].unique()):
    crops = sorted(df[df['Season'] == season]['Crop'].unique())
    crop_season[season.strip()] = [c.strip() for c in crops if pd.notna(c)]

# Save to JSON
output_path = os.path.join(os.path.dirname(__file__), 'crop_seasons.json')
with open(output_path, 'w') as f:
    json.dump(crop_season, f, indent=2)

print(f"Created {output_path}")
print(f"Seasons: {list(crop_season.keys())}")
for season in list(crop_season.keys())[:2]:
    print(f"  {season}: {len(crop_season[season])} crops")
