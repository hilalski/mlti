import openpyxl
import json
import os
import tempfile

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
    'vendor_service.xlsx',
    'qr_pusat.xlsx'
]

for fname in files:
    excel_path = os.path.join(excel_dir, fname)
    if not os.path.exists(excel_path):
        print(f"Skipped {fname}: file not found")
        continue

    # data_only reads the latest calculated value of formula cells and read_only
    # keeps conversion reliable even when the workbook grows larger.
    wb = openpyxl.load_workbook(excel_path, data_only=True, read_only=True)
    ws = wb.active
    rows = ws.iter_rows(values_only=True)
    headers_row = next(rows, None)
    if not headers_row:
        continue

    headers = [str(h).strip() if h is not None else '' for h in headers_row]
    data = []
    for r in rows:
        if all(cell is None for cell in r):
            continue
        row_dict = {}
        for h, val in zip(headers, r):
            if not h:
                continue
            # Clean string values
            if isinstance(val, str):
                val = val.strip()
            row_dict[h] = val
        if row_dict:
            data.append(row_dict)
        
    out_name = fname.replace('.xlsx', '.json')
    output_path = os.path.join(json_dir, out_name)
    # Write to a temporary file first so an interrupted conversion never leaves
    # the seeder with a partial JSON file.
    fd, temp_path = tempfile.mkstemp(dir=json_dir, suffix='.json')
    with os.fdopen(fd, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=4, ensure_ascii=False)
    os.replace(temp_path, output_path)
    wb.close()
    print(f"Converted {fname} to {out_name} (rows: {len(data)})")
