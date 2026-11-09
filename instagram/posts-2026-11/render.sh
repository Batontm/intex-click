#!/bin/sh
# Рендер карточек в JPG 1080×1350 через Google Chrome без окна (macOS).
cd "$(dirname "$0")" || exit 1
CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
for f in post-*.html; do
  png="${f%.html}.png"
  "$CHROME" --headless=new --disable-gpu --hide-scrollbars --force-device-scale-factor=1 \
    --window-size=1080,1350 --virtual-time-budget=8000 --screenshot="$PWD/$png" "file://$PWD/$f" >/dev/null 2>&1
  sips -s format jpeg -s formatOptions 92 "$png" --out "${f%.html}.jpg" >/dev/null && rm -f "$png"
  echo "${f%.html}.jpg"
done
