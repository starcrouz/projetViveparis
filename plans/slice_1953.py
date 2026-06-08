import os
import sys
import time
from PIL import Image

# Point XDG_CONFIG_HOME to our local plans folder so glymur loads our local glymurrc
os.environ["XDG_CONFIG_HOME"] = os.path.abspath("plans")

try:
    import glymur
    print("Glymur imported successfully!")
    print("OpenJPEG version:", glymur.version.openjpeg_version)
except Exception as e:
    print(f"Failed to import glymur: {e}")
    print("Please ensure openjpeg DLL is available and XDG_CONFIG_HOME is set.")
    sys.exit(1)

jp2_path = 'plan de paris 1953.jp2'
if not os.path.exists(jp2_path):
    jp2_path = '../plan de paris 1953.jp2'
    if not os.path.exists(jp2_path):
        print("Error: plan de paris 1953.jp2 not found in workspace.")
        sys.exit(1)

# Create folders
folders = [
    'plans/1953',
    'plans/1953/tranches0',
    'plans/1953/tranches1',
    'plans/1953/tranches2',
    'plans/1953/tranches3',
    'plans/1953/tranches5'
]
for f in folders:
    os.makedirs(f, exist_ok=True)

print("Opening JP2 image with Glymur...")
start_time = time.time()
jp2 = glymur.Jp2k(jp2_path)
h, w, c = jp2.shape
print(f"Opened image of size {w}x{h} in {time.time() - start_time:.4f}s")

# Crop boundaries (offset 2541 vertical)
target_h = 31680
top_offset_jp2 = 2541

# Total files to generate: 110 tiles * 5 levels + 1 overview = 551 files
total_files = 551
completed_files = 0

def print_progress(message):
    percent = (completed_files / total_files) * 100
    print(f"[Progress] {percent:.1f}% ({completed_files}/{total_files}): {message}", flush=True)

# Define levels and their strides/resize targets
# We process tile by tile for best memory usage and progress updates
print("Starting tile slicing...")

for r in range(11):
    for c in range(1, 11):
        cellIndex = r * 10 + c
        paddedIndex = f"0{cellIndex}" if cellIndex < 10 else str(cellIndex)
        
        # Native coordinates for this tile
        left = (c - 1) * 4800
        top = r * 2880
        
        jp2_left = left
        jp2_right = left + 4800
        jp2_top = top_offset_jp2 + top
        jp2_bottom = top_offset_jp2 + top + 2880
        
        # Level 0 (scale 6.4)
        t0 = time.time()
        tile0_data = jp2[jp2_top:jp2_bottom, jp2_left:jp2_right]
        tile0_img = Image.fromarray(tile0_data)
        tile0_img.save(f'plans/1953/tranches0/planParis_{paddedIndex}.jpg', 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches0/planParis_{paddedIndex}.jpg in {time.time() - t0:.2f}s")
        
        # Level 2 (scale 2.5, stride 2, resize to 1875x1125)
        t2 = time.time()
        tile2_data = jp2[jp2_top:jp2_bottom:2, jp2_left:jp2_right:2]
        tile2_img = Image.fromarray(tile2_data)
        tile2_resized = tile2_img.resize((1875, 1125), Image.Resampling.LANCZOS)
        tile2_resized.save(f'plans/1953/tranches2/planParis_{paddedIndex}.jpg', 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches2/planParis_{paddedIndex}.jpg in {time.time() - t2:.2f}s")
        
        # Level 1 (scale 1.0, stride 4, resize to 750x450)
        t1 = time.time()
        tile1_data = jp2[jp2_top:jp2_bottom:4, jp2_left:jp2_right:4]
        tile1_img = Image.fromarray(tile1_data)
        tile1_resized = tile1_img.resize((750, 450), Image.Resampling.LANCZOS)
        tile1_resized.save(f'plans/1953/tranches1/planParis_{paddedIndex}.jpg', 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches1/planParis_{paddedIndex}.jpg in {time.time() - t1:.2f}s")
        
        # Level 3 (scale 0.33, stride 8, resize to 250x150)
        t3 = time.time()
        tile3_data = jp2[jp2_top:jp2_bottom:8, jp2_left:jp2_right:8]
        tile3_img = Image.fromarray(tile3_data)
        tile3_resized = tile3_img.resize((250, 150), Image.Resampling.LANCZOS)
        tile3_resized.save(f'plans/1953/tranches3/planParis_{paddedIndex}.jpg', 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches3/planParis_{paddedIndex}.jpg in {time.time() - t3:.2f}s")
        
        # Level 5 (scale 0.2, stride 16, resize to 150x90)
        t5 = time.time()
        tile5_data = jp2[jp2_top:jp2_bottom:16, jp2_left:jp2_right:16]
        tile5_img = Image.fromarray(tile5_data)
        tile5_resized = tile5_img.resize((150, 90), Image.Resampling.LANCZOS)
        tile5_resized.save(f'plans/1953/tranches5/planParis_{paddedIndex}.jpg', 'JPEG', quality=85)
        completed_files += 1
        print_progress(f"Saved tranches5/planParis_{paddedIndex}.jpg in {time.time() - t5:.2f}s")

# Save overview (parisComplet675x450.jpg, stride 16, crop middle and resize)
print("Saving overview plans/1953/parisComplet675x450.jpg...")
t_over = time.time()
overview_top = top_offset_jp2
overview_bottom = top_offset_jp2 + target_h
overview_data = jp2[overview_top:overview_bottom:16, 0:48000:16]
overview_img = Image.fromarray(overview_data)
overview_resized = overview_img.resize((675, 450), Image.Resampling.LANCZOS)
overview_resized.save('plans/1953/parisComplet675x450.jpg', 'JPEG', quality=85)
completed_files += 1
print_progress(f"Saved overview plans/1953/parisComplet675x450.jpg in {time.time() - t_over:.2f}s")

print(f"\nSlicing completed successfully in {time.time() - start_time:.2f}s!")

