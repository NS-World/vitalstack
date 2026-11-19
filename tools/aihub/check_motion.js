const { chromium } = require('playwright');
const fs = require('fs'), path = require('path');
const dir = process.argv[2], posterDir = process.argv[3], only = process.argv[4];
(async () => {
  const browser = await chromium.launch();
  let files = fs.readdirSync(dir).filter(f => f.endsWith('.html')).sort();
  if (only) files = files.filter(f => f.includes(only));
  const bad = []; let i = 0;
  async function worker() {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const mob = await browser.newContext({ viewport: { width: 375, height: 740 }, isMobile: true, hasTouch: true });
    while (i < files.length) {
      const f = files[i++]; const html = fs.readFileSync(path.join(dir, f), 'utf8'); const errs = [];
      for (const [c, label] of [[ctx, 'desk'], [mob, 'mob']]) {
        const p = await c.newPage();
        p.on('pageerror', e => errs.push(label + ' pageerror: ' + e.message));
        p.on('console', m => { if (m.type() === 'error') errs.push(label + ' console: ' + m.text()); });
        p.on('request', r => { if (!r.url().startsWith('data:') && !r.url().startsWith('about:')) errs.push(label + ' network: ' + r.url()); });
        await p.setContent(html, { waitUntil: 'load' });
        await p.waitForTimeout(label === 'desk' ? 2600 : 900);
        const m = await p.evaluate(() => ({ sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth, txt: document.body.innerText.length }));
        if (m.sw > m.cw + 1) errs.push(label + ' overflow ' + m.sw + '>' + m.cw);
        if (m.txt < 20) errs.push(label + ' little text');
        if (label === 'desk') {
          await p.mouse.move(500, 400); await p.mouse.move(700, 300); await p.waitForTimeout(300);
          const buf = await p.screenshot({ type: 'png' });
          if (buf.length < 9000) errs.push('desk screenshot tiny ' + buf.length);
          if (posterDir) { const pb = await p.screenshot({ type: 'jpeg', quality: 80, clip: { x: 0, y: 0, width: 1280, height: 800 } }); fs.writeFileSync(path.join(posterDir, f.replace('.html', '.jpg')), pb); }
        }
        await p.close();
      }
      if (errs.length) bad.push(f + ': ' + [...new Set(errs)].slice(0, 4).join(' | '));
    }
  }
  await Promise.all([worker(), worker(), worker(), worker(), worker(), worker()]);
  await browser.close();
  console.log('checked', files.length, 'bad', bad.length); bad.forEach(b => console.log(b));
})();
