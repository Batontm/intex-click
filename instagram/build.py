import json,html,re
A=json.load(open('arts.json'))
logo=open('logo.svg').read()
IMG='/home/claude/intech-site/wp-content/themes/intech/assets/img/'
art={'sozdanie-saytov':'sol-web','prodvizhenie-saytov':'sol-seo','it-autsorsing':'sol-infra','servery':'sol-infra','seti':'sol-net'}
cats={'it':'IT для бизнеса','sayty':'Сайты и продвижение'}
fonts=open('../render/fonts.css').read()+open('art.css').read()
def card(a,i):
    t=a['title']
    if ':' in t: main,sub=t.split(':',1)
    elif ' — ' in t: main,sub=t.split(' — ',1)
    else: main,sub=t,''
    svg=open(IMG+('sol-soft' if a['slug'] in ('crm-sistema-chto-eto','terminalnyy-server-chto-eto') else art.get(a['service'],'sol-infra'))+'.svg').read()
    n=len(main)
    fs=96 if n<=22 else 84 if n<=34 else 72 if n<=48 else 62
    return f'''<!doctype html><html><head><meta charset=utf-8><style>{fonts}
:root{{--accent:#22D3EE;--accent-2:#3B82F6;--warm:#FF7A3D;--accent-rgb:34,211,238;--accent2-rgb:59,130,246;--warm-rgb:255,122,61;--white:#fff;--muted:#9AA4B8;--line:rgba(255,255,255,.08);--line-strong:rgba(255,255,255,.16)}}
*{{box-sizing:border-box;margin:0}}
body{{width:1080px;height:1350px;background:#04060C;font-family:Manrope,sans-serif;color:#fff;overflow:hidden;position:relative}}
.g1{{position:absolute;width:900px;height:900px;left:-300px;top:-300px;border-radius:50%;background:radial-gradient(circle,rgba(34,211,238,.28),transparent 65%);filter:blur(40px)}}
.g2{{position:absolute;width:800px;height:800px;right:-300px;bottom:-250px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,.30),transparent 65%);filter:blur(40px)}}
.g3{{position:absolute;width:500px;height:500px;left:-150px;bottom:-200px;border-radius:50%;background:radial-gradient(circle,rgba(255,122,61,.16),transparent 65%);filter:blur(50px)}}
.grid{{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(circle at 50% 40%,#000,transparent 75%)}}
.wrap{{position:absolute;inset:72px;display:flex;flex-direction:column}}
.top{{display:flex;align-items:center;justify-content:space-between}}
.logo svg{{height:58px;width:auto}}
.chip{{font-size:26px;font-weight:600;color:#22D3EE;border:2px solid rgba(34,211,238,.45);border-radius:999px;padding:12px 28px;background:rgba(34,211,238,.08)}}
.pic{{margin:30px -72px 0;flex:1 1 auto;min-height:320px;max-height:600px;display:flex;justify-content:center;-webkit-mask-image:radial-gradient(ellipse 62% 60% at 50% 50%,#000 55%,transparent 100%);mask-image:radial-gradient(ellipse 62% 60% at 50% 50%,#000 55%,transparent 100%)}}
.pic svg{{height:100%;width:auto}}
.title{{margin-top:24px;max-width:936px;hyphens:none;font-family:Unbounded;font-weight:600;font-size:{fs}px;line-height:1.08;letter-spacing:-.02em}}
.sub{{margin-top:26px;font-size:40px;line-height:1.3;font-weight:600;background:linear-gradient(90deg,#c9f6ff,#22D3EE 45%,#3B82F6);-webkit-background-clip:text;color:transparent}}
.foot{{margin-top:56px;padding-top:32px;border-top:2px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;align-items:center;font-size:30px;color:#9AA4B8}}
.foot b{{color:#fff;font-weight:600}}
.arrow{{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#22D3EE,#3B82F6);display:flex;align-items:center;justify-content:center;color:#020617;font-size:36px;font-weight:700}}
</style></head><body><div class=g1></div><div class=g2></div><div class=g3></div><div class=grid></div>
<div class=wrap><div class=top><div class=logo>{logo}</div><div class=chip>{cats[a["cat"]]}</div></div>
<div class=pic>{svg}</div>
<div class=title>{html.escape(main.strip()).replace('-','&#8209;')}</div>{f'<div class=sub>{html.escape(sub.strip()[0].upper()+sub.strip()[1:])}</div>' if sub else ''}
<div class=foot><span>Читайте полностью на <b>intex.click</b></span><span class=arrow>→</span></div></div><script>document.fonts.ready.then(()=>{{const t=document.querySelector('.title');const W=936;let f=parseFloat(getComputedStyle(t).fontSize);const words=t.textContent.split(/\s+/);const m=document.createElement('span');m.style.cssText='position:absolute;visibility:hidden;white-space:nowrap;font-family:Unbounded;font-weight:600;letter-spacing:-.02em';document.body.appendChild(m);const fits=()=>{{m.style.fontSize=f+'px';return words.every(w=>{{m.textContent=w;return m.getBoundingClientRect().width<=W}})}};while(!fits()&&f>40)f-=2;t.style.fontSize=f+'px';document.body.dataset.ready=1}})</script></body></html>'''
for i,a in enumerate(A):
    open(f'card-{i:02d}.html','w').write(card(a,i))
