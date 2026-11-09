"""Собирает карточки 1080×1350 (HTML) и подписи для трёх новых постов.

Запуск из этой папки:  python3 build.py
Затем рендер:          ./render.sh
Стиль карточки — как в ../build.py (те же цвета, сетка, шрифты Unbounded/Manrope).
"""
import html
import json
import re
from pathlib import Path

from posts import POSTS

HERE = Path(__file__).parent
THEME = HERE.parent.parent / 'wp-content/themes/intech'
IG_LIMIT = 2200

# Логотип и CSS иллюстраций берём из темы, чтобы не расходиться с сайтом.
logo_php = (THEME / 'inc/logo.php').read_text()
MARK = re.search(r"\$mark = '([^']+)'", logo_php).group(1)
WORD = re.search(r"\$word = '([^']+)'", logo_php).group(1)
LOGO = ('<svg viewBox="4 3 996 250" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="lg" x1="0" y1="1" x2="1" y2="0">'
        '<stop offset="0" stop-color="#1FD1E8"/><stop offset="1" stop-color="#3B63F3"/></linearGradient></defs>'
        f'<path fill="url(#lg)" d="{MARK}"/><path fill="#fff" d="{WORD}"/></svg>')
style = (THEME / 'style.css').read_text()
ART_CSS = '\n'.join(l for l in style.splitlines() if l.startswith('.art ') or l.startswith('svg.art'))

FONTS = '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Unbounded:wght@500;600;700&display=block">'


def card(p):
    svg = (HERE / p['art']).read_text()
    title = html.escape(p['title']).replace('-', '&#8209;')
    sub = html.escape(p['sub'])
    n = len(p['title'])
    fs = 96 if n <= 22 else 84 if n <= 34 else 72 if n <= 48 else 64
    return f'''<!doctype html><html><head><meta charset=utf-8>{FONTS}<style>
:root{{--accent:#22D3EE;--accent-2:#3B82F6;--warm:#FF7A3D}}
{ART_CSS}
*{{box-sizing:border-box;margin:0}}
body{{width:1080px;height:1350px;background:#04060C;font-family:Manrope,sans-serif;color:#fff;overflow:hidden;position:relative}}
.g1{{position:absolute;width:900px;height:900px;left:-300px;top:-300px;border-radius:50%;background:radial-gradient(circle,rgba(34,211,238,.28),transparent 65%);filter:blur(40px)}}
.g2{{position:absolute;width:800px;height:800px;right:-300px;bottom:-250px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,.30),transparent 65%);filter:blur(40px)}}
.g3{{position:absolute;width:500px;height:500px;left:-150px;bottom:-200px;border-radius:50%;background:radial-gradient(circle,rgba(255,122,61,.16),transparent 65%);filter:blur(50px)}}
.grid{{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(circle at 50% 40%,#000,transparent 75%)}}
.wrap{{position:absolute;inset:72px;display:flex;flex-direction:column}}
.top{{display:flex;align-items:center;justify-content:space-between}}
.logo svg{{height:58px;width:auto;display:block}}
.chip{{font-size:26px;font-weight:600;color:#22D3EE;border:2px solid rgba(34,211,238,.45);border-radius:999px;padding:12px 28px;background:rgba(34,211,238,.08)}}
.pic{{margin:30px -72px 0;flex:1 1 auto;min-height:320px;max-height:600px;display:flex;justify-content:center;-webkit-mask-image:radial-gradient(ellipse 62% 60% at 50% 50%,#000 55%,transparent 100%);mask-image:radial-gradient(ellipse 62% 60% at 50% 50%,#000 55%,transparent 100%)}}
.pic svg{{height:100%;width:auto}}
.title{{margin-top:24px;max-width:936px;hyphens:none;font-family:Unbounded;font-weight:600;font-size:{fs}px;line-height:1.08;letter-spacing:-.02em}}
.sub{{margin-top:26px;font-size:40px;line-height:1.3;font-weight:600;background:linear-gradient(90deg,#c9f6ff,#22D3EE 45%,#3B82F6);-webkit-background-clip:text;color:transparent}}
.foot{{margin-top:56px;padding-top:32px;border-top:2px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;align-items:center;font-size:30px;color:#9AA4B8}}
.foot b{{color:#fff;font-weight:600}}
.arrow{{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#22D3EE,#3B82F6);display:flex;align-items:center;justify-content:center;color:#020617;font-size:36px;font-weight:700}}
</style></head><body><div class=g1></div><div class=g2></div><div class=g3></div><div class=grid></div>
<div class=wrap><div class=top><div class=logo>{LOGO}</div><div class=chip>{html.escape(p['chip'])}</div></div>
<div class=pic>{svg}</div>
<div class=title>{title}</div><div class=sub>{sub}</div>
<div class=foot><span>Разбор — в описании к посту · <b>intex.click</b></span><span class=arrow>↓</span></div></div>
<script>document.fonts.ready.then(()=>{{const t=document.querySelector('.title');const W=936;let f=parseFloat(getComputedStyle(t).fontSize);const words=t.textContent.split(/\\s+/);const m=document.createElement('span');m.style.cssText='position:absolute;visibility:hidden;white-space:nowrap;font-family:Unbounded;font-weight:600;letter-spacing:-.02em';document.body.appendChild(m);const fits=()=>{{m.style.fontSize=f+'px';return words.every(w=>{{m.textContent=w;return m.getBoundingClientRect().width<=W}})}};while(!fits()&&f>40)f-=2;t.style.fontSize=f+'px';document.body.dataset.ready=1}})</script></body></html>'''


out = []
md = ['# Instagram @intex.click — три новых поста (ноябрь 2026)', '',
      'Картинки 1080×1350 (4:5), полный текст — в подписи. Публиковать по одному в день.', '']
for i, p in enumerate(POSTS, 1):
    name = f'post-{i}-{p["slug"]}'
    (HERE / f'{name}.html').write_text(card(p))
    cap = p['caption'].strip()
    assert len(cap) <= IG_LIMIT, (p['slug'], len(cap))
    out.append({'n': i, 'slug': p['slug'], 'img': f'{name}.jpg', 'chars': len(cap), 'caption': cap})
    md += [f'## {i}. {p["title"]}', '', f'Картинка: `{name}.jpg` · подпись {len(cap)} символов из {IG_LIMIT}', '', '```', cap, '```', '']
    print(f'{name}: подпись {len(cap)} символов')
(HERE / 'captions.json').write_text(json.dumps(out, ensure_ascii=False, indent=1))
(HERE / 'README.md').write_text('\n'.join(md))
