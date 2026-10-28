const { chromium } = require('playwright');
(async () => { const b = await chromium.launch({ executablePath: '/opt/pw-browsers/chromium' }); const p = await b.newPage({viewport:{width:1080,height:1350}});
const list = process.argv.slice(2);
for (const f of list) { await p.goto('file://' + process.cwd() + '/' + f); await p.waitForSelector('body[data-ready]'); await p.waitForTimeout(200); await p.screenshot({path: f.replace('.html','.jpg'), type:'jpeg', quality:92}); }
await b.close(); })();
