import urllib.request
import time
import sys

url = 'https://recorda-434241025-qzja.vercel.app/'
headers = {'User-Agent': 'Mozilla/5.0'}

print("Starting long poll for Vercel deployment...")
sys.stdout.flush()

for i in range(30):
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req) as r:
            content = r.read().decode('utf-8')
            if '<?php' not in content and '<html' in content:
                print("SUCCESS: Website is now live on Vercel!")
                sys.stdout.flush()
                break
            else:
                print(f"[{i+1}/30] Served content is still raw PHP. Polling again in 20s...")
                sys.stdout.flush()
    except Exception as e:
        print(f"[{i+1}/30] Check failed: {e}")
        sys.stdout.flush()
    time.sleep(20)

print("Finished long poll.")
