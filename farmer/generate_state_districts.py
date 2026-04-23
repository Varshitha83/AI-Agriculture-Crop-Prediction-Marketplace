import pandas as pd
import json
import os

# Read the crop prediction CSV
df = pd.read_csv(os.path.join(os.path.dirname(__file__), 'ML', 'crop_prediction', 'preprocessed2.csv'))

# Create state-district mapping
state_district = {}
for state in sorted(df['State_Name'].unique()):
    districts = sorted(df[df['State_Name'] == state]['District_Name'].unique())
    state_district[state.strip()] = [d.strip() for d in districts]

# Save to JSON in assets folder
output_path = os.path.join(os.path.dirname(__file__), 'state_districts.json')
with open(output_path, 'w') as f:
    json.dump(state_district, f, indent=2)

print(f"Created {output_path}")
print(f"Total states: {len(state_district)}")
