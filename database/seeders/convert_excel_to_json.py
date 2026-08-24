import openpyxl
import json
import os

excel_dir = 'database/seeders/excel'
json_dir = 'database/seeders/json'
os.makedirs(json_dir, exist_ok=True)

files = [
    'team.xlsx',
    'room.xlsx',
    'user.xlsx',
    'device.xlsx',
    'type.xlsx',
    'condition.xlsx',
    'source.xlsx',
    'status_bmn.xlsx',
    'vendor_service.xlsx'
]

for fname in files:
    wb = openpyxl.load_workbook(os.path.join(excel_dir, fname))
    ws = wb.active
    rows = list(ws.iter_rows(values_only=True))
    if not rows:
        continue
    
    headers = [str(h).strip() for h in rows[0]]
    data = []
    for r in rows[1:]:
        if all(cell is None for cell in r):
            continue
        row_dict = {}
        for h, val in zip(headers, r):
            # Clean string values
            if isinstance(val, str):
                val = val.strip()
            row_dict[h] = val
        data.append(row_dict)
        
    out_name = fname.replace('.xlsx', '.json')
    with open(os.path.join(json_dir, out_name), 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=4, ensure_ascii=False)
    print(f"Converted {fname} to {out_name} (rows: {len(data)})")
