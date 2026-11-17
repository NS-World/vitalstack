const { chromium } = require('playwright');
(async () => {
  const [file, out] = process.argv.slice(2);
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1080, height: 1350 } });
  await p.goto('file://' + file);
  await p.waitForTimeout(500);
  for (const el of await p.$$('section.s')) {
    const n = await el.getAttribute('data-name');
    await el.screenshot({ path: `${out}/${n}.png` });
  }
  await b.close();
})();
