# CDN stress test

Sequential HEAD checks against a list of URLs. Prints per-request status/latency, then a summary (200 / 429 / 5xx / other, avg, P95).

## Usage

```bash
cd scripts/cdn-stress
cp urls.txt.example urls.txt
# edit urls.txt — one URL per line
php stress.php
```

Requires PHP with the `curl` extension.

`urls.txt` is gitignored locally; keep real load lists out of git and use `urls.txt.example` as the template.
