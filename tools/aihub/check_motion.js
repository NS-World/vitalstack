const { chromium } = require('playwright');
const fs = require('fs'), path = require('path');
const dir = process.argv[2], posterDir = process.argv[3], posterMap = process.argv[4] ? JSON.parse(fs.readFileSync(process.argv[4], 'utf8')) : {}, only = process.argv[5] || '';
const want = {}; for (const [k, id] of Object.entries(posterMap)) { if (!posterDir || !fs.existsSync(path.join(posterDir, k + '.jpg'))) want[id + '.html'] = k; }
(async () => {
  const browser = await chromium.launch();
  let files = fs.readdirSync(dir).filter(f => f.endsWith('.html') && f.includes(only)).sort();
  const bad = []; let i = 0, done = 0;
  async function worker() {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const mob = await browser.newContext({ viewport: { width: 375, height: 740 }, isMobile: true, hasTouch: true });
    let pd, pm, errs = [], uses = 0;
    const fresh = async () => {
      if (pd) { await pd.close().catch(() => {}); await pm.close().catch(() => {}); }
      pd = await ctx.newPage(); pm = await mob.newPage();
      for (const [p, label] of [[pd, 'desk'], [pm, 'mob']]) {
        p.on('pageerror', e => errs.push(label + ' pageerror: ' + e.message));
        p.on('console', m => { if (m.type() === 'error') errs.push(label + ' console: ' + m.text()); });
        p.on('request', r => { const u = r.url(); if (!u.startsWith('data:') && !u.startsWith('about:')) errs.push(label + ' network: ' + u); });
      }
    };
    await fresh();
    while (i < files.length) {
      if (++uses % 60 === 0) await fresh();
      const f = files[i++]; const html = fs.readFileSync(path.join(dir, f), 'utf8'); errs = [];
      for (const [p, label] of [[pd, 'desk'], [pm, 'mob']]) {
        try {
          await p.setContent(html, { waitUntil: 'load', timeout: 30000 });
          await p.waitForTimeout(label === 'desk' ? (want[f] ? 1800 : 450) : 200);
          const m = await p.evaluate(() => ({ sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth, txt: document.body.innerText.length }));
          if (m.sw > m.cw + 1) errs.push(label + ' overflow ' + m.sw + '>' + m.cw);
          if (m.txt < 20) errs.push(label + ' little text');
          if (label === 'desk' && want[f] && posterDir) { await p.mouse.move(640, 400); await p.waitForTimeout(200); fs.writeFileSync(path.join(posterDir, want[f] + '.jpg'), await p.screenshot({ type: 'jpeg', quality: 80 })); }
        } catch (e) { errs.push(label + ' fail ' + e.message.slice(0, 80)); }
        await p.setContent('<html><body></body></html>').catch(() => {});
      }
      if (errs.length) { const l = f + ': ' + [...new Set(errs)].slice(0, 3).join(' | '); bad.push(l); console.log('BAD ' + l); }
      if (++done % 500 === 0) console.log('progress', done, 'bad', bad.length);
    }
  }
  await Promise.all(Array.from({ length: +(process.env.W || 5) }, worker));
  await browser.close();
  console.log('checked', files.length, 'bad', bad.length); bad.forEach(b => console.log(b));
})();
