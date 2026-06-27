import urllib.request
import time
import sys

url = 'https://recorda-434241025-qzja.vercel.app/'
headers = {'User-Agent': 'Mozilla/5.0'}

print("Waiting for active Vercel deployment...")
sys.stdout.flush()

for i in range(20):
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req) as r:
            content = r.read().decode('utf-8')
            if '<?php' not in content and '<html' in content:
                print("Success! Vercel is now executing PHP and rendering the landing page!")
                sys.stdout.flush()
                break
            else:
                print(f"[{i+1}/20] Still loading or serving raw PHP. Waiting...")
                sys.stdout.flush()
    except Exception as e:
        print(f"[{i+1}/20] Error checking page: {e}")
        sys.stdout.flush()
    time.sleep(15)

print("Finished polling.")
