# Slicing Pipeline for Paris 1953 Map

This folder contains the utility script `slice_1953.py` used to slice the high-resolution JPEG 2000 (`.jp2`) image of the 1953 Paris map into multiple zoom-level tile grids.

## Overview

The slicing script crops out white margins and processes the map at a native resolution of **48,000 x 31,680 pixels**. It outputs a total of **551 files** under `plans/1953/`:
- **tranches0** (scale 6.4): 110 tiles of size `4800x2880` (native crop).
- **tranches2** (scale 2.5): 110 tiles of size `1875x1125` (resized).
- **tranches1** (scale 1.0): 110 tiles of size `750x450` (resized).
- **tranches3** (scale 0.33): 110 tiles of size `250x150` (resized).
- **tranches5** (scale 0.2): 110 tiles of size `150x90` (resized).
- **parisComplet675x450.jpg**: 1 overview file.

The script is highly optimized to run in **under 3 minutes** and consumes **less than 200 MB of RAM** by decoding individual tile regions directly from the JP2 file using Glymur strides (wavelet decomp levels) instead of loading the entire image into memory.

---

## Installation & Setup

### 1. Requirements
Ensure you have **Python 3.10+** installed on your system.

### 2. Virtual Environment Setup
From the project root directory, set up a virtual environment and install the required dependencies:

```bash
# Create the virtual environment
python -m venv .venv

# Activate it (Windows PowerShell)
.venv\Scripts\Activate.ps1

# Activate it (Windows Command Prompt)
.venv\Scripts\activate.bat

# Activate it (macOS/Linux)
source .venv/bin/activate

# Install required packages
pip install Pillow glymur
```

### 3. OpenJPEG Dynamic Library (Windows)
`glymur` relies on the C-based OpenJPEG library (`openjp2.dll` or similar) to read JPEG 2000 files.

To configure it:
1. Ensure the `openjp2.dll` library is installed on your computer. If you have Calibre or another app installed, it is typically located in `C:\Program Files\Calibre2\app\bin\openjp2.dll`.
2. The `scripts/glymur/glymurrc` file configures the path to `openjp2` for Glymur:
   ```ini
   [library]
   openjp2: C:\Program Files\Calibre2\app\bin\openjp2.dll
   ```
   Modify this path if your DLL is located elsewhere.

---

## Execution

1. Make sure the source file `plan de paris 1953.jp2` is placed in the project root folder.
2. From the project root, activate your virtual environment and run:

```bash
python scripts/slice_1953.py
```

The script will report progress (0% to 100%) in real-time as it processes all cells and saves the output images directly into the `plans/1953/` folder.
