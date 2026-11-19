// AI Hub: gating, filters, dialog, copy, download, header link, sitemap. node t_aihub.js
const { chromium } = require('playwright'); const { execFileSync } = require('child_process');
const L = process.env.WP_URL || 'http://localhost:8095', SITE = process.argv[2]; // path to a local WordPress with the plugin active
let pass = 0, fail = 0; const ok = (c, m) => { c ? pass++ : fail++; console.log((c ? 'PASS ' : 'FAIL ') + m); };
const php = (code) => execFileSync('php', ['-r', 'require "wp-load.php"; ' + code], { cwd: SITE }).toString().trim();
(async () => {
  const b = await chromium.launch(); const errs = [];
  const g = await b.newContext({ viewport: { width: 1366, height: 900 } }); const p = await g.newPage(); p.on('pageerror', e => errs.push(e.message));
  // Guest: premium content never in the HTML.
  const proPrompt = php('foreach (vsai_builtin("prompts") as $i) { if ($i["premium"]) { echo substr($i["prompt"],0,40); break; } }');
  const proGuide = php('foreach (vsai_builtin("library") as $i) { if ($i["premium"]) { echo "from pypdf import PdfReader"; break; } }');
  const proCode = php('foreach (vsai_builtin("motion") as $i) { if ($i["premium"]) { echo substr($i["prompt"],0,40); break; } }');
  const proCmd = php('echo "Checkpoints / restore";');
  let html = await (await p.goto(L + '/ai/prompts/')).text();
  ok(!html.includes(proPrompt.replace(/&/g, '&amp;').slice(0, 30)) && !html.includes(proPrompt.slice(0, 30)), 'guest: premium prompt text not in page');
  ok((html.match(/vsai-lock/g) || []).length >= 10, 'guest: premium prompts show lock cards');
  ok(html.includes('Copy'), 'guest: free prompts have copy buttons');
  html = await (await p.goto(L + '/ai/library/')).text();
  ok(!html.includes(proGuide), 'guest: premium guide body not in page');
  html = await (await p.goto(L + '/ai/motion/')).text();
  ok(!html.includes(proCode.slice(0, 30)) && html.includes('assets/posters/text-scramble.webp'), 'guest: premium motion code hidden, poster shown');
  html = await (await p.goto(L + '/ai/commands/')).text();
  ok(!html.includes(proCmd), 'guest: premium command rows hidden');
  const v = await p.goto(L + '/ai/motion/view/glass-pricing/'); ok(v.url().endsWith('/ai/motion/'), 'guest: premium full-screen view redirects');
  const fv = await p.goto(L + '/ai/motion/view/aurora-hero/'); ok(/sandbox allow-scripts/.test(fv.headers()['content-security-policy'] || ''), 'free view: sandbox CSP header');
  // Filters.
  await p.goto(L + '/ai/prompts/', { waitUntil: 'load' });
  const total = await p.locator('.vsai-prompt').count();
  await p.click('[data-price="free"]'); const free = await p.locator('.vsai-prompt:visible').count();
  await p.click('[data-price="pro"]'); const pro = await p.locator('.vsai-prompt:visible').count();
  ok(free + pro === total && free > 0 && pro > 0, `filters: free ${free} + premium ${pro} = ${total}`);
  await p.click('[data-price=""]'); await p.click('.vsai-chip[data-cat="Coding"]');
  ok(await p.locator('.vsai-prompt:visible').count() === await p.locator('.vsai-prompt[data-cat="Coding"]').count(), 'filters: category chip');
  await p.click('.vsai-chip[data-cat=""]'); await p.fill('[data-q]', 'resume'); await p.waitForTimeout(300);
  ok(await p.locator('.vsai-prompt:visible').count() >= 1 && await p.locator('.vsai-prompt:visible').count() < total, 'filters: search');
  await p.fill('[data-q]', 'zzzqqq'); await p.waitForTimeout(300); ok(await p.locator('[data-empty]').isVisible(), 'filters: empty message');
  // Copy (clipboard permission).
  await g.grantPermissions(['clipboard-read', 'clipboard-write'], { origin: L });
  await p.fill('[data-q]', ''); await p.waitForTimeout(250);
  await p.locator('.vsai-prompt:visible [data-copy]').first().click(); await p.waitForTimeout(200);
  const clip = await p.evaluate(() => navigator.clipboard.readText()); ok(clip.length > 40, 'copy: prompt copied to clipboard');
  // Library dialog.
  await p.goto(L + '/ai/library/', { waitUntil: 'load' });
  await p.locator('[data-open="#lib-ai-in-15-minutes"]').click();
  ok(await p.locator('.vsai-dialog[open] h2').textContent().then(t => t.includes('AI in 15 Minutes')), 'library: guide opens in dialog');
  await p.keyboard.press('Escape'); ok(!(await p.locator('.vsai-dialog[open]').count()), 'dialog: Esc closes');
  await p.locator('[data-open="#lib-rag-from-scratch"]').click();
  ok(await p.locator('.vsai-dialog[open] .vsai-lock').count() === 1, 'library: premium guide shows lock in dialog');
  await p.keyboard.press('Escape');
  // Motion dialog for free item: preview, tabs, download.
  await p.goto(L + '/ai/motion/', { waitUntil: 'load' }); await p.waitForTimeout(800);
  ok(await p.locator('.vsai-frame iframe[srcdoc]').count() >= 3, 'motion: visible previews loaded lazily');
  await p.locator('[data-open="#mo-aurora-hero"]').click();
  ok(await p.locator('.vsai-dialog[open] .vsai-mo-stage iframe[srcdoc]').count() === 1, 'motion: dialog live preview');
  await p.click('.vsai-dialog [data-tab="prompt"]'); ok(await p.locator('.vsai-dialog [data-pane="prompt"]').isVisible(), 'motion: AI prompt tab');
  const [dl] = await Promise.all([p.waitForEvent('download'), p.click('.vsai-dialog [data-download]')]);
  ok(dl.suggestedFilename() === 'aurora-hero.html', 'motion: download .html');
  await p.keyboard.press('Escape');
  // Main site header link + sitemap.
  await p.goto(L + '/', { waitUntil: 'load' });
  ok(await p.locator('a[href$="/ai/"]').count() >= 1, 'main site: AI Hub link present');
  const sm = await (await p.goto(L + '/wp-sitemap-aihub-1.xml')).text(); ok(/\/ai\/motion\//.test(sm) && /\/ai\/library\//.test(sm), 'sitemap: AI Hub pages listed');
  const head = await (await p.goto(L + '/ai/prompts/')).text();
  ok((head.match(/rel="canonical"/g) || []).length === 1 && /<meta name="description" content="Ready-to-use prompts/.test(head), 'SEO: one canonical + own description');
  // Member: everything open.
  php('$u=get_user_by("email","aihubmember@example.test"); if(!$u){ $id=wp_insert_user(["user_login"=>"aihubmember","user_email"=>"aihubmember@example.test","user_pass"=>"Zx9!aihub-member-77","role"=>"subscriber"]); } else { $id=$u->ID; } update_user_meta($id,"vs_premium",["data-scientist"=>time()+86400]); echo $id;');
  ok(php('$u=get_user_by("email","aihubmember@example.test"); echo vsai_is_member($u->ID)?"y":"n";') === 'y', 'member: any paid track = AI Hub member');
  ok(php('$u=get_user_by("email","aihubmember@example.test"); update_user_meta($u->ID,"vs_premium",["data-scientist"=>time()-10]); $r=vsai_is_member($u->ID)?"y":"n"; update_user_meta($u->ID,"vs_premium",["data-scientist"=>time()+86400]); echo $r;') === 'n', 'member: expired plan is not a member');
  const mHtml = php('$u=get_user_by("email","aihubmember@example.test"); wp_set_current_user($u->ID); set_query_var("vsai","prompts"); ob_start(); vsai_render_prompts(); echo ob_get_clean();');
  ok(mHtml.includes('Act as a sceptical startup mentor') || mHtml.includes('system design interviewer'), 'member: premium prompts rendered');
  ok(!/vsai-lock/.test(mHtml), 'member: no lock cards');
  php('$u=get_user_by("email","aihubmember@example.test"); require_once ABSPATH."wp-admin/includes/user.php"; wp_delete_user($u->ID);');
  ok(errs.length === 0, 'no JS errors ' + errs.join(' | '));
  await b.close(); console.log(`${pass}/${pass + fail}`); process.exit(fail ? 1 : 0);
})().catch(e => { console.log('CRASH', e.message); process.exit(2); });
