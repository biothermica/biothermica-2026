#!/usr/bin/env python3

import argparse
import re
import sys
from pathlib import Path
from urllib.parse import urljoin, urlparse
from urllib.request import Request, urlopen
from urllib.error import HTTPError, URLError


# Matches:
# onclick="window.location.href='/about/'"
# onclick='window.location.href="/about/"'
# onclick="window.location.href='https://example.com/'"
#
# Also allows whitespace around =
ONCLICK_PATTERN = re.compile(
    r"""onclick\s*=\s*["']\s*
        window\.location\.href\s*=\s*
        ["']([^"']+)["']
        \s*["']""",
    re.IGNORECASE | re.VERBOSE,
)


def check_url(url, timeout=15):
    """
    Return (success, status, message).
    """

    try:
        request = Request(
            url,
            headers={
                "User-Agent": "GitHub-Actions-Onclick-Link-Checker/1.0"
            },
        )

        try:
            with urlopen(request, timeout=timeout) as response:
                status = response.status

                if 200 <= status < 400:
                    return True, status, "OK"

                return False, status, f"HTTP {status}"

        except HTTPError as error:
            # Some servers reject HEAD-like requests, but urllib is
            # using GET here, so an HTTP error means the URL is bad
            # for our purposes.
            return False, error.code, f"HTTP {error.code}"

    except URLError as error:
        return False, None, str(error.reason)

    except Exception as error:
        return False, None, str(error)


def normalize_url(link, page_url):
    """
    Convert relative URLs to absolute URLs.
    """

    link = link.strip()

    # Ignore empty links.
    if not link:
        return None

    # Ignore javascript:, mailto:, tel:, etc.
    parsed = urlparse(link)

    if parsed.scheme and parsed.scheme not in ("http", "https"):
        return None

    return urljoin(page_url, link)


def main():
    parser = argparse.ArgumentParser(
        description="Check window.location.href links in generated HTML."
    )

    parser.add_argument(
        "directory",
        nargs="?",
        default="docs/_site",
        help="Directory containing generated HTML files "
             "(default: docs/_site)",
    )

    parser.add_argument(
        "--base-url",
        default="http://localhost:4312/",
        help="Base URL used to resolve relative links "
             "(default: http://localhost:4312/)",
    )

    parser.add_argument(
        "--timeout",
        type=int,
        default=15,
        help="HTTP timeout in seconds (default: 15)",
    )

    args = parser.parse_args()

    site_dir = Path(args.directory)

    if not site_dir.exists():
        print(f"ERROR: Directory does not exist: {site_dir}")
        sys.exit(1)

    base_url = args.base_url.rstrip("/") + "/"

    html_files = list(site_dir.rglob("*.html"))

    print(f"Scanning: {site_dir}")
    print(f"HTML files found: {len(html_files)}")
    print(f"Base URL: {base_url}")
    print()

    found = 0
    failed = 0
    checked = set()

    for html_file in html_files:
        try:
            content = html_file.read_text(
                encoding="utf-8",
                errors="ignore",
            )
        except Exception as error:
            print(f"ERROR reading {html_file}: {error}")
            failed += 1
            continue

        matches = ONCLICK_PATTERN.findall(content)

        if not matches:
            continue

        page_relative = html_file.relative_to(site_dir)

        # Convert:
        #
        # index.html          -> /
        # about/index.html    -> /about/
        # about.html          -> /about.html
        #
        if page_relative.name == "index.html":
            if page_relative.parent == Path("."):
                page_path = "/"
            else:
                page_path = "/" + str(page_relative.parent).replace("\\", "/") + "/"
        else:
            page_path = "/" + str(page_relative).replace("\\", "/")

        page_url = urljoin(base_url, page_path)

        for link in matches:
            url = normalize_url(link, page_url)

            if url is None:
                continue

            # Avoid checking the exact same URL repeatedly.
            key = (url, str(html_file))

            if key in checked:
                continue

            checked.add(key)
            found += 1

            print(f"Checking: {url}")
            print(f"  Found in: {html_file}")

            success, status, message = check_url(
                url,
                timeout=args.timeout,
            )

            if success:
                print(f"  ✓ {message} ({status})")
            else:
                failed += 1

                if status:
                    print(f"  ✗ BROKEN: HTTP {status}")
                else:
                    print(f"  ✗ BROKEN: {message}")

            print()

    print("=" * 70)
    print(f"onclick links found : {found}")
    print(f"broken links        : {failed}")
    print("=" * 70)

    if failed:
        print("\nBroken onclick links were found.")
        sys.exit(1)

    print("\nAll onclick links are accessible.")
    sys.exit(0)


if __name__ == "__main__":
    main()
