import re
import textwrap
from pathlib import Path

ROOT = Path(__file__).resolve().parent
EMAIL = "info@primegateinternational.co.tz"
HOURS = "Mon - sun / 08:00 - 20:30"
PHONES = "+255 718 707 449 | +255 755 666 745 | 0798 008 007"
SKIP_DIRS = {"plugins", "upload"}

CONTAINER_PATTERN = re.compile(r'<div class=("|")logisco-top-bar-container-inner clearfix\1>')
NAV_PATTERN = re.compile(r'<ul id=menu-main-navigation-1')

META_TEMPLATE = textwrap.dedent(
    """
                        <div class="top-bar-row top-bar-meta">
                            <div class="contact-hours"><i class="icon_clock"></i> {hours}</div>
                            <div class="contact-email"><i class="fa fa-envelope"></i> <a href="mailto:{email}">{email}</a></div>
                            <div class="contact-numbers"><i class="fa fa-phone"></i> {phones}</div>
                        </div>
    """
).strip("\n")

MENU_TEMPLATE = """                        <div class="top-bar-row top-bar-menu">
                            <div class="top-bar-brand">
                                <img src="{logo_path}" alt="Primegate Logo" class="top-bar-logo" loading="lazy">
                            </div>
                            <div class="top-bar-nav-wrap">
{nav_block}
                            </div>
                        </div>"""


def find_matching(text: str, start: int, open_token: str, close_token: str) -> int | None:
    depth = 1
    idx = start
    open_len = len(open_token)
    close_len = len(close_token)
    while idx < len(text):
        next_open = text.find(open_token, idx)
        next_close = text.find(close_token, idx)
        if next_open != -1 and next_open < next_close:
            depth += 1
            idx = next_open + open_len
            continue
        if next_close == -1:
            return None
        depth -= 1
        idx = next_close + close_len
        if depth == 0:
            return idx
    return None


def relative_logo_path(path: Path) -> str:
    rel = path.relative_to(ROOT)
    depth = len(rel.parents) - 1
    prefix = "" if depth <= 0 else "../" * depth
    return f"{prefix}upload/logo-removebg-preview.png"


def rebuild_container(text: str, file_path: Path) -> str | None:
    match = CONTAINER_PATTERN.search(text)
    if not match:
        return None
    container_start = match.start()
    inner_start = match.end()
    container_end = find_matching(text, inner_start, '<div', '</div>')
    if container_end is None:
        return None

    nav_match = NAV_PATTERN.search(text, inner_start, container_end)
    if not nav_match:
        return None
    nav_start = nav_match.start()
    nav_end = find_matching(text, nav_start + len('<ul'), '<ul', '</ul>')
    if nav_end is None:
        return None
    nav_block = text[nav_start:nav_end]
    nav_block = textwrap.indent(nav_block.strip('\n'), ' ' * 28)
    if not nav_block.endswith('\n'):
        nav_block += '\n'

    logo_path = relative_logo_path(file_path)

    new_inner = '\n'.join([
        META_TEMPLATE.format(hours=HOURS, email=EMAIL, phones=PHONES),
        MENU_TEMPLATE.format(logo_path=logo_path, nav_block=nav_block)
    ]) + '\n'

    return text[:inner_start] + '\n' + new_inner + text[container_end - len('</div>'):]


def process_file(path: Path) -> bool:
    if any(part in SKIP_DIRS for part in path.relative_to(ROOT).parts):
        return False
    try:
        text = path.read_text(encoding='utf-8')
    except UnicodeDecodeError:
        return False
    rebuilt = rebuild_container(text, path)
    if rebuilt and rebuilt != text:
        path.write_text(rebuilt, encoding='utf-8')
        return True
    return False


def main():
    changed = []
    for html_file in ROOT.rglob('*.html'):
        if process_file(html_file):
            changed.append(html_file)
    print(f"Applied header layout to {len(changed)} files")


if __name__ == '__main__':
    main()
