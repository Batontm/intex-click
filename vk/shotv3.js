const { chromium } = require('playwright');
(async () => { const b = await chromium.launch({ executablePath: '/opt/pw-browsers/chromium' });
const p = await b.newPage({viewport:{width:1920,height:768}, deviceScaleFactor:1}); await p.goto('file://'+process.cwd()+'/vk-cover-v3.html'); await p.waitForTimeout(800); await p.screenshot({path:'vk-cover-v3.png'});
await b.close(); })();
