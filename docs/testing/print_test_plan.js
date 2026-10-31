const { chromium } = require('playwright');
const path = require('path');
(async () => {
  const b = await chromium.launch();
  const p = await b.newPage();
  await p.goto('file://' + path.join(__dirname, 'testplan.html'));
  await p.pdf({
    path: process.argv[2], format: 'A4', landscape: true, printBackground: true, preferCSSPageSize: true,
    displayHeaderFooter: true, headerTemplate: '<span></span>',
    footerTemplate: '<div style="font-size:7pt;color:#888;width:100%;padding:0 12mm;display:flex;justify-content:space-between;font-family:sans-serif"><span>VitalStack 4.8 Test Plan · build 4.8.2</span><span>Page <span class="pageNumber"></span> / <span class="totalPages"></span></span></div>',
  });
  await b.close();
})();
