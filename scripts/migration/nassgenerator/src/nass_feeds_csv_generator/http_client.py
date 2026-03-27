from __future__ import annotations

import logging
import time
from dataclasses import dataclass
from typing import Optional

import requests


@dataclass
class FetchResult:
    url: str
    final_url: str
    status: int
    content_type: str
    text: str


class Fetcher:
    """Small HTTP client with retry + politeness delay.

    This is intentionally minimal and independent from any other crawler codebase.
    """

    def __init__(
        self,
        base_delay: float = 0.0,
        timeout: float = 30.0,
        retries: int = 3,
        user_agent: str = "nass-feeds-csv-generator/0.1 (+migration tooling)",
    ) -> None:
        self.base_delay = base_delay
        self.timeout = timeout
        self.retries = retries
        self.session = requests.Session()
        self.session.headers.update({"User-Agent": user_agent})
        self.log = logging.getLogger("Fetcher")

    def get(self, url: str) -> FetchResult:
        last_err: Optional[Exception] = None
        for attempt in range(1, self.retries + 1):
            try:
                if self.base_delay:
                    time.sleep(self.base_delay)

                resp = self.session.get(url, timeout=self.timeout, allow_redirects=True)
                ct = resp.headers.get("Content-Type", "")
                text = resp.text if "text" in ct or "html" in ct or ct == "" else resp.text
                return FetchResult(
                    url=url,
                    final_url=str(resp.url),
                    status=int(resp.status_code),
                    content_type=ct,
                    text=text,
                )
            except Exception as e:
                last_err = e
                self.log.warning("GET failed (attempt %s/%s): %s (%s)", attempt, self.retries, url, e)
                time.sleep(min(2.0 * attempt, 6.0))
        raise RuntimeError(f"GET failed after {self.retries} attempts: {url} ({last_err})")
