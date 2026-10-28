import json,re,html
A=json.load(open('arts.json'))
tags={'sozdanie-saytov':'#созданиесайтов #сайтдлябизнеса','prodvizhenie-saytov':'#seo #продвижениесайтов #яндекс','it-autsorsing':'#itаутсорсинг #системныйадминистратор','servery':'#серверы #резервноекопирование','seti':'#локальнаясеть #wifi'}
out=[]
for i,a in enumerate(A):
    h2=[html.unescape(re.sub('<[^>]+>','',x)).strip() for x in re.findall(r'<h2>(.*?)</h2>',a['body'])]
    h2=[re.sub(r'^(Шаг|Этап) \d+\.\s*','',x) for x in h2]
    h2=[x for x in h2 if x and x not in ('Частые вопросы',)][:6]
    lines=[a['title'],'',a['lead'],'','В статье:']+['▫️ '+x for x in h2]+['','📖 Полная статья — на сайте intex.click, раздел «Статьи». Ссылка в профиле.','','ИНТЕХ — сайты, SEO и IT-обслуживание для бизнеса в Калининграде.','',tags.get(a['service'],'')+' #калининград #итдлябизнеса']
    c='\n'.join(lines)
    assert len(c)<2100,(a['slug'],len(c))
    out.append({'i':i,'slug':a['slug'],'img':f'card-{i:02d}.jpg','caption':c})
json.dump(out,open('posts.json','w'),ensure_ascii=False,indent=1)
print(out[2]['caption']); print(max(len(o['caption']) for o in out))
