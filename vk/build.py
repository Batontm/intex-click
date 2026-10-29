import re,os,json
T='/home/claude/intech-site/wp-content/themes/intech/assets/img/'
src=open('vk-cover-v2.html').read()
head=src[:src.index('</style>')]  # fonts + art css + root
logo=re.search(r'<svg class="logo__svg".*?</svg>',src,re.S).group(0)
def art(n,pref):
    s=open(T+'sol-'+n+'.svg').read()
    # make ids unique per use
    ids=set(re.findall(r'id="([^"]+)"',s))
    for i in ids: s=s.replace('id="%s"'%i,'id="%s%s"'%(pref,i)).replace('url(#%s)'%i,'url(#%s%s)'%(pref,i))
    return s
base='''*{box-sizing:border-box;margin:0}body{background:#04060C;font-family:Manrope,sans-serif;color:#fff;overflow:hidden;position:relative}
.g{position:absolute;border-radius:50%%;filter:blur(40px)}
.grad{background:linear-gradient(90deg,#c9f6ff,#22D3EE 45%%,#3B82F6);-webkit-background-clip:text;color:transparent}
.logo svg{width:auto;display:block}.logo{color:#fff}
.pic svg{width:100%%;height:auto;display:block}
'''
svc=[('sozdanie-saytov','web','Создание сайтов','лендинги, корпоративные сайты, магазины'),
('prodvizhenie-saytov','seo','SEO и Яндекс Директ','продвижение в Яндексе и Google'),
('it-autsorsing','soft','IT‑обслуживание','сисадмин на аутсорсе для офиса'),
('servery','infra','Серверы и бэкапы','1С, терминальный сервер, резервные копии'),
('seti','net','Сети и Wi‑Fi','локальная сеть и Wi‑Fi под ключ')]
os.makedirs('vkshop/out',exist_ok=True)
for k,(slug,a,t,sub) in enumerate(svc):
    h=head+base+'''body{width:1000px;height:1000px}
.pic{position:absolute;left:40px;right:40px;top:120px;border-radius:28px;overflow:hidden;border:1.5px solid rgba(148,178,255,.18);box-shadow:0 30px 80px rgba(0,0,0,.6)}
.logo{position:absolute;left:64px;top:44px}.logo svg{height:44px}
.tag{position:absolute;right:64px;top:48px;font-size:22px;font-weight:600;padding:8px 18px;border-radius:999px;border:1.5px solid rgba(34,211,238,.45);color:#9BEAF7}
h1{position:absolute;left:64px;right:64px;top:720px;font-family:Unbounded;font-weight:600;font-size:60px;line-height:1.08;letter-spacing:-.02em}
p{position:absolute;left:64px;right:64px;top:850px;font-size:30px;color:#9AA8BF}
</style></head><body>
<div class=g style="width:900px;height:900px;left:-300px;top:-400px;background:radial-gradient(circle,rgba(34,211,238,.28),transparent 65%)"></div>
<div class=g style="width:900px;height:900px;right:-350px;bottom:-400px;background:radial-gradient(circle,rgba(59,130,246,.3),transparent 65%)"></div>
<div class=logo>'''+logo+'''</div><div class=tag>Калининград</div>
<div class=pic>'''+art(a,'p%d'%k)+'''</div>
<h1>'''+t+'''</h1><p>'''+sub+'''</p></body></html>'''
    open('vkshop/svc-%d.html'%k,'w').write(h)
menu=[('Услуги','что мы делаем','web','https://intex.click/#services'),
('Статьи','полезное о сайтах и IT','seo','https://intex.click/blog/'),
('О компании','кто мы и как работаем','infra','https://intex.click/o-kompanii/'),
('Контакты','телефон, почта, мессенджеры','net','https://intex.click/kontakty/'),
('Оставить заявку','ответим в рабочее время','soft','https://intex.click/#contacts')]
for k,(t,sub,a,u) in enumerate(menu):
    h=head+base+'''body{width:752px;height:512px}
.pic{position:absolute;right:-120px;top:-10px;width:620px;opacity:.55;-webkit-mask-image:radial-gradient(ellipse 50%% 50%% at 50%% 50%%,#000 40%%,transparent 100%%)}
.shade{position:absolute;inset:0;background:linear-gradient(90deg,#04060C 30%,rgba(4,6,12,.2))}
.logo{position:absolute;left:48px;top:44px}.logo svg{height:34px}
h1{position:absolute;left:48px;right:48px;bottom:118px;font-family:Unbounded;font-weight:600;font-size:56px;line-height:1.05;letter-spacing:-.02em}
p{position:absolute;left:48px;bottom:60px;font-size:26px;color:#9AA8BF}
.bar{position:absolute;left:48px;bottom:36px;width:80px;height:5px;border-radius:3px;background:linear-gradient(90deg,#22D3EE,#3B82F6)}
</style></head><body>
<div class=g style="width:700px;height:700px;left:-250px;top:-350px;background:radial-gradient(circle,rgba(34,211,238,.3),transparent 65%)"></div>
<div class=pic>'''+art(a,'m%d'%k)+'''</div><div class=shade></div>
<div class=logo>'''+logo+'''</div>
<h1>'''+t+'''</h1><p>'''+sub+'''</p><div class=bar></div></body></html>'''
    h=h.replace('%%','%')
    open('vkshop/menu-%d.html'%k,'w').write(h)
json.dump({'svc':svc,'menu':menu},open('vkshop/meta.json','w'),ensure_ascii=False)
