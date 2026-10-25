#!/usr/bin/env node
// Render an HTML file to an A4 PDF with Playwright's Chromium.
// Usage: node tools/render_pdf.js in.html out.pdf
// Set PLAYWRIGHT_CHROMIUM to a Chromium binary if Playwright can't find one.
const path = require('path');
let chromium;
try { ({ chromium } = require('playwright')); } catch (e) { ({ chromium } = require('/opt/node22/lib/node_modules/playwright')); }

(async () => {
  const [input, output] = process.argv.slice(2);
  if (!input || !output) { console.error('usage: node tools/render_pdf.js in.html out.pdf'); process.exit(1); }
  const opts = process.env.PLAYWRIGHT_CHROMIUM ? { executablePath: process.env.PLAYWRIGHT_CHROMIUM } : {};
  const browser = await chromium.launch(opts);
  const page = await browser.newPage();
  await page.goto('file://' + path.resolve(input), { waitUntil: 'load' });
  await page.evaluate(() => document.fonts.ready);
  await page.pdf({ path: output, format: 'A4', printBackground: true, preferCSSPageSize: true });
  await browser.close();
  console.log('wrote', output);
})();
