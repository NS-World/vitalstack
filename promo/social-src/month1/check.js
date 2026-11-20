// Render every slide: scale each slide's content up to fill the free space (up to 1.5×),
// then report anything that still runs into the crew strip or footer.
// usage: node check.js path/to/month1.html OUT_PNG_DIR
const { chromium } = require('playwright');
(async () => {
  const [file, out] = process.argv.slice(2);
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1080, height: 1350 } });
  await p.goto('file://' + file);
  await p.waitForTimeout(600);
  const problems = await p.$$eval('section.s', secs => secs.map(s => {
    const top = () => s.getBoundingClientRect().top;
    const c = s.querySelector('.content');
    const strip = s.querySelector('.strip');
    let limit = 1234 - 34;
    if (strip) {
      let st = 1350;
      strip.querySelectorAll('.bub, .ch img').forEach(e => { st = Math.min(st, e.getBoundingClientRect().top - top()); });
      limit = st - 30;
    }
    const height = () => {
      let bottom = 0;
      c.querySelectorAll('*').forEach(e => { const r = e.getBoundingClientRect(); if (r.height) bottom = Math.max(bottom, r.bottom - top()); });
      return bottom - 80;
    };
    const pres = [...c.querySelectorAll('pre')];
    pres.forEach(e => e.dataset.fs = parseFloat(getComputedStyle(e).fontSize));
    const fitPres = () => pres.forEach(e => {
      let fs = +e.dataset.fs; e.style.fontSize = fs + 'px';
      while (e.scrollWidth > e.clientWidth + 1 && fs > 18) { fs -= 1; e.style.fontSize = fs + 'px'; }
    });
    const tooWide = () => [...c.querySelectorAll('pre, table, .li, .opts')].some(e => e.scrollWidth > e.clientWidth + 2);
    let z = 1.5;
    for (; z >= 0.8; z -= 0.03) {
      c.style.zoom = z; c.style.width = (920 / z) + 'px'; c.style.right = 'auto';
      fitPres();
      if (height() + 80 <= limit && !tooWide()) break;
    }
    const wide = tooWide();
    return { name: s.dataset.name, zoom: +z.toFixed(2), bottom: Math.round(height() + 80), limit: Math.round(limit), wide };
  }).filter(x => x.bottom > x.limit || x.wide));
  for (const el of await p.$$('section.s')) {
    await el.screenshot({ path: `${out}/${await el.getAttribute('data-name')}.png` });
  }
  await b.close();
  console.log(problems.length ? JSON.stringify(problems, null, 1) : 'no layout problems');
})();
