#!/usr/bin/env python3
"""Normalize NASS Census of Agriculture HTML into Drupal-friendly JSON.

This script is intentionally heuristic. The three page families are similar, but not
identical, so the script extracts headings, descriptive text, grouped resource items,
and action links into one common JSON shape.

Usage:
  python extract_agcensus.py --input page.html --url https://... --variant publication --year 2022 --output 2022.json
"""

from __future__ import annotations

import argparse
import json
import re
from dataclasses import dataclass, field
from pathlib import Path
from typing import Iterable, List, Optional

from bs4 import BeautifulSoup, NavigableString, Tag

DATE_RE = re.compile(
    r"(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\s+\d{1,2},\s+\d{4}",
    re.IGNORECASE,
)


@dataclass
class ActionLink:
    label: str
    url: str
    format: str = ""


@dataclass
class ResourceItem:
    title: str
    description: str = ""
    release_date: str = ""
    group_label: str = ""
    links: List[ActionLink] = field(default_factory=list)


@dataclass
class Section:
    heading: str
    intro: str = ""
    items: List[ResourceItem] = field(default_factory=list)


def clean_text(value: str) -> str:
    return re.sub(r"\s+", " ", value).strip()


def infer_format(label: str, url: str) -> str:
    combined = f"{label} {url}".lower()
    for ext in ["pdf", "txt", "csv", "xls", "xlsx", "zip"]:
        if ext in combined:
            return ext.upper()
    return ""


def is_heading(tag: Tag) -> bool:
    return tag.name in {"h1", "h2", "h3", "h4"}


def is_noise(text: str) -> bool:
    text = clean_text(text)
    if not text:
      return True
    return text.lower() in {
        "menu",
        "skip to main content",
        "search",
    }


def pick_main_root(soup: BeautifulSoup) -> Tag:
    selectors = [
        "main",
        "#main-content",
        "#content",
        ".content",
        ".main-content",
        "body",
    ]
    for selector in selectors:
        node = soup.select_one(selector)
        if node:
            return node
    return soup.body or soup


def paragraph_text(tag: Tag) -> str:
    return clean_text(" ".join(tag.stripped_strings))


def collect_action_links(container: Tag) -> List[ActionLink]:
    links: List[ActionLink] = []
    for a in container.find_all("a", href=True, recursive=False):
        label = clean_text(a.get_text(" ", strip=True)) or a.get("title", "Link")
        url = a["href"].strip()
        links.append(ActionLink(label=label, url=url, format=infer_format(label, url)))
    return links


def parse_list_block(tag: Tag) -> List[ResourceItem]:
    items: List[ResourceItem] = []
    for li in tag.find_all("li", recursive=False):
        direct_links = li.find_all("a", href=True)
        text = clean_text(li.get_text(" ", strip=True))
        if not text:
            continue
        title = text
        description = ""
        links = [ActionLink(clean_text(a.get_text(" ", strip=True)), a["href"].strip(), infer_format(a.get_text(" ", strip=True), a["href"])) for a in direct_links]
        if direct_links:
            first_label = clean_text(direct_links[0].get_text(" ", strip=True))
            if text.startswith(first_label):
                title = first_label
                description = clean_text(text[len(first_label):])
        items.append(ResourceItem(title=title, description=description, links=links))
    return items


def parse_publication_rows(elements: Iterable[Tag]) -> List[ResourceItem]:
    items: List[ResourceItem] = []
    current: Optional[ResourceItem] = None

    for el in elements:
        if is_heading(el):
            continue
        text = paragraph_text(el)
        if not text:
            continue

        links = [ActionLink(clean_text(a.get_text(" ", strip=True)), a["href"].strip(), infer_format(a.get_text(" ", strip=True), a["href"])) for a in el.find_all("a", href=True)]
        maybe_date = DATE_RE.search(text)

        if el.name in {"p", "div"} and links and not maybe_date:
            current = ResourceItem(title=text, links=links)
            items.append(current)
            continue

        if current and not current.description and el.name in {"p", "div"} and not links and not maybe_date:
            current.description = text
            continue

        if current and maybe_date:
            current.release_date = maybe_date.group(0)
            if links:
                current.links.extend(links)
            continue

        if links:
            items.append(ResourceItem(title=text, links=links, release_date=maybe_date.group(0) if maybe_date else ""))

    return items


def parse_sections(root: Tag) -> List[Section]:
    sections: List[Section] = []
    current: Optional[Section] = None

    for el in root.find_all(["h1", "h2", "h3", "h4", "p", "ul", "ol", "div"], recursive=True):
        if el.find_parent(["header", "footer", "nav"]):
            continue

        if is_heading(el):
            heading = paragraph_text(el)
            if is_noise(heading):
                continue
            current = Section(heading=heading)
            sections.append(current)
            continue

        if current is None:
            continue

        if el.name == "p":
            text = paragraph_text(el)
            if text and not current.items:
                if current.intro:
                    current.intro += "\n\n" + text
                else:
                    current.intro = text
            continue

        if el.name in {"ul", "ol"}:
            current.items.extend(parse_list_block(el))
            continue

        if el.name == "div":
            # Handle denser publication/archive blocks.
            items = parse_publication_rows(list(el.find_all(["p", "div"], recursive=False)))
            if items:
                current.items.extend(items)

    # Drop empty shell sections.
    return [section for section in sections if section.intro or section.items]


def normalize(html: str, url: str, variant: str, year: int) -> dict:
    soup = BeautifulSoup(html, "html.parser")
    root = pick_main_root(soup)
    title = soup.title.get_text(" ", strip=True) if soup.title else f"Ag Census {year}"
    sections = parse_sections(root)

    summary = ""
    for section in sections[:3]:
        if section.intro:
            summary = section.intro
            break

    return {
        "page": {
            "title": title,
            "year": year,
            "variant": variant,
            "source_url": url,
            "summary": summary,
            "legacy_html": html[:200000],
        },
        "sections": [
            {
                "heading": section.heading,
                "intro": section.intro,
                "items": [
                    {
                        "title": item.title,
                        "description": item.description,
                        "release_date": item.release_date,
                        "group_label": item.group_label,
                        "links": [
                            {"label": link.label, "url": link.url, "format": link.format}
                            for link in item.links
                        ],
                    }
                    for item in section.items
                ],
            }
            for section in sections
        ],
    }


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--input", required=True, help="Local HTML file to normalize")
    parser.add_argument("--url", required=True, help="Original source URL")
    parser.add_argument("--variant", required=True, choices=["current", "publication", "archive"])
    parser.add_argument("--year", required=True, type=int)
    parser.add_argument("--output", required=True)
    args = parser.parse_args()

    html = Path(args.input).read_text(encoding="utf-8", errors="ignore")
    data = normalize(html, args.url, args.variant, args.year)
    Path(args.output).write_text(json.dumps(data, indent=2, ensure_ascii=False), encoding="utf-8")
    print(f"Wrote {args.output}")


if __name__ == "__main__":
    main()
