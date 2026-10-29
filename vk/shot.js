const { chromium } = require('playwright');
(async () => { const b = await chromium.launch({ executablePath: '/opt/pw-browsers/chromium' });
for (const [pre,n,w,h] of [['svc',5,1000,1333],['menu',5,752,512]]) for (let i=0;i<n;i++){
const p = await b.newPage({viewport:{width:w,height:h}}); await p.goto('file://'+process.cwd()+'/vkshop/'+pre+'-'+i+'.html'); await p.waitForTimeout(500); await p.screenshot({path:'vkshop/out/'+pre+'-'+i+'.jpg',type:'jpeg',quality:92}); await p.close();}
await b.close(); })();
