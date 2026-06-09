#!/usr/bin/env python3
"""
Slicing Pipeline for Paris 1953 Map

This script opens the high-resolution JP2 (JPEG 2000) historical scan of the 
1953 Paris map and slices it into 110 tiles (10 columns x 11 rows) across 
5 zoom scales, plus a single low-resolution overview image.

Dependencies:
  - Python 3.10+
  - Pillow (PIL): python -m pip install Pillow
  - Glymur: python -m pip install glymur
  - OpenJPEG C library: Must be installed/configured on the system.
    On Windows, place openjp2.dll in scripts/openjpeg/ and configure glymurrc.
"""

import os
import sys
import time
from PIL import Image

# Point XDG_CONFIG_HOME to the scripts/ folder so glymur loads our local glymurrc config
SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
os.environ["XDG_CONFIG_HOME"] = SCRIPT_DIR

try:
    import glymur
    print("Glymur imported successfully!")
    print("OpenJPEG version:", glymur.version.openjpeg_version)
except Exception as e:
    print(f"Failed to import glymur: {e}")
    print("Please ensure openjpeg library is available and glymurrc is configured.")
    sys.exit(1)

# Resolve path to the source JP2 map file (placed in the root of the project)
ROOT_DIR = os.path.abspath(os.path.join(SCRIPT_DIR, '..'))
jp2_path = os.path.join(ROOT_DIR, 'plan de paris 1953.jp2')

if not os.path.exists(jp2_path):
    print(f"Error: {jp2_path} not found.")
    print("Please make sure 'plan de paris 1953.jp2' is placed in the project root directory.")
    sys.exit(1)

# Resolve target folders for the output tiles inside plans/1953/
plans_base = os.path.join(ROOT_DIR, 'plans', '1953')
folders = [
    plans_base,
    os.path.join(plans_base, 'tranches0'), # scale 6.4 (native 4800x2880)
    os.path.join(plans_base, 'tranches1'), # scale 1.0 (750x450)
    os.path.join(plans_base, 'tranches2'), # scale 2.5 (1875x1125)
    os.path.join(plans_base, 'tranches3'), # scale 0.33 (250x150)
    os.path.join(plans_base, 'tranches5')  # scale 0.2 (150x90)
]
for f in folders:
    os.makedirs(f, exist_ok=True)

print("Opening JP2 image with Glymur...")
start_time = time.time()
jp2 = glymur.Jp2k(jp2_path)
h, w, c = jp2.shape
print(f"Opened image of size {w}x{h} in {time.time() - start_time:.4f}s")

# Slicing Parameters
# The map is center-cropped to crop out white scanner borders.
# Offset: 2541 vertical offset, target height: 31680, target width: 48000.
target_h = 31680
top_offset_jp2 = 2541

# 110 tiles * 5 levels + 1 overview = 551 total files
total_files = 551
completed_files = 0

def print_progress(message):
    percent = (completed_files / total_files) * 100
    print(f"[Progress] {percent:.1f}% ({completed_files}/{total_files}): {message}", flush=True)

print("Starting tile slicing (processing cell by cell to minimize memory footprint)...")

# 11 Rows (r) and 10 Columns (c)
for r in range(11):
    for c in range(1, 11):
        cellIndex = r * 10 + c
        paddedIndex = f"0{cellIndex}" if cellIndex < 10 else str(cellIndex)
        
        # Coordinates in the cropped 48000x31680 space
        left = (c - 1) * 4800
        top = r * 2880
        
        # Absolute coordinates in the source JP2 file
        jp2_left = left
        jp2_right = left + 4800
        jp2_top = top_offset_jp2 + top
        jp2_bottom = top_offset_jp2 + top + 2880
        
        # -------------------------------------------------------------
        # Level 0 (scale 6.4, full resolution, tile size: 4800x2880)
        # -------------------------------------------------------------
        t0 = time.time()
        tile0_data = jp2[jp2_top:jp2_bottom, jp2_left:jp2_right]
        tile0_img = Image.fromarray(tile0_data)
        tile0_path = os.path.join(plans_base, 'tranches0', f'planParis_{paddedIndex}.jpg')
        tile0_img.save(tile0_path, 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches0/planParis_{paddedIndex}.jpg in {time.time() - t0:.2f}s")
        
        # -------------------------------------------------------------
        # Level 2 (scale 2.5, stride 2, resize to 1875x1125)
        # -------------------------------------------------------------
        t2 = time.time()
        tile2_data = jp2[jp2_top:jp2_bottom:2, jp2_left:jp2_right:2]
        tile2_img = Image.fromarray(tile2_data)
        tile2_resized = tile2_img.resize((1875, 1125), Image.Resampling.LANCZOS)
        tile2_path = os.path.join(plans_base, 'tranches2', f'planParis_{paddedIndex}.jpg')
        tile2_resized.save(tile2_path, 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches2/planParis_{paddedIndex}.jpg in {time.time() - t2:.2f}s")
        
        # -------------------------------------------------------------
        # Level 1 (scale 1.0, stride 4, resize to 750x450)
        # -------------------------------------------------------------
        t1 = time.time()
        tile1_data = jp2[jp2_top:jp2_bottom:4, jp2_left:jp2_right:4]
        tile1_img = Image.fromarray(tile1_data)
        tile1_resized = tile1_img.resize((750, 450), Image.Resampling.LANCZOS)
        tile1_path = os.path.join(plans_base, 'tranches1', f'planParis_{paddedIndex}.jpg')
        tile1_resized.save(tile1_path, 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches1/planParis_{paddedIndex}.jpg in {time.time() - t1:.2f}s")
        
        # -------------------------------------------------------------
        # Level 3 (scale 0.33, stride 8, resize to 250x150)
        # -------------------------------------------------------------
        t3 = time.time()
        tile3_data = jp2[jp2_top:jp2_bottom:8, jp2_left:jp2_right:8]
        tile3_img = Image.fromarray(tile3_data)
        tile3_resized = tile3_img.resize((250, 150), Image.Resampling.LANCZOS)
        tile3_path = os.path.join(plans_base, 'tranches3', f'planParis_{paddedIndex}.jpg')
        tile3_resized.save(tile3_path, 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches3/planParis_{paddedIndex}.jpg in {time.time() - t3:.2f}s")
        
        # -------------------------------------------------------------
        # Level 5 (scale 0.2, stride 16, resize to 150x90)
        # -------------------------------------------------------------
        t5 = time.time()
        tile5_data = jp2[jp2_top:jp2_bottom:16, jp2_left:jp2_right:16]
        tile5_img = Image.fromarray(tile5_data)
        tile5_resized = tile5_img.resize((150, 90), Image.Resampling.LANCZOS)
        tile5_path = os.path.join(plans_base, 'tranches5', f'planParis_{paddedIndex}.jpg')
        tile5_resized.save(tile5_path, 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches5/planParis_{paddedIndex}.jpg in {time.time() - t5:.2f}s")

# -------------------------------------------------------------
# Save overview (parisComplet675x450.jpg, stride 16, crop middle and resize)
# -------------------------------------------------------------
print("Saving overview parisComplet675x450.jpg...")
t_over = time.time()
overview_top = top_offset_jp2
overview_bottom = top_offset_jp2 + target_h
overview_data = jp2[overview_top:overview_bottom:16, 0:48000:16]
overview_img = Image.fromarray(overview_data)
overview_resized = overview_img.resize((675, 450), Image.Resampling.LANCZOS)
overview_path = os.path.join(plans_base, 'parisComplet675x450.jpg')
overview_resized.save(overview_path, 'JPEG', quality=85)
completed_files += 1
print_progress(f"Saved overview plans/1953/parisComplet675x450.jpg in {time.time() - t_over:.2f}s")

print(f"\nSlicing completed successfully in {time.time() - start_time:.2f}s!")
