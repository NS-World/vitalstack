// AI Hub 1.1–2.0 end-to-end test: copy limits, Pro gating, pricing, purchase grant, previews.
// Usage: node tests/aihub.e2e.js /path/to/local/wordpress   (WP_URL defaults to http://localhost:8095)
const { chromium } = require('playwright'); const { execFileSync } = require('child_process');
const L = process.env.WP_URL || 'http://localhost:8095', SITE = process.argv[2];
let pass = 0, fail = 0; const ok = (c, m) => { c ? pass++ : fail++; console.log((c ? 'PASS ' : 'FAIL ') + m); };
const php = (code) => execFileSync('php', ['-r', 'require "wp-load.php"; ' + code], { cwd: SITE }).toString().trim();
const resetGuest = () => php('global $wpdb; $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE \'%vsai_g_%\' OR option_name LIKE \'%vsai_ip_%\' OR option_name LIKE \'%vsai_edge_%\'");');
const copyClick = async (page, loc) => { await Promise.all([page.waitForResponse(r => r.url().includes('/vsai/v1/copy'), { timeout: 20000 }).catch(() => {}), loc.click()]); await page.waitForTimeout(400); };
const EMAIL = 'aihubfree@example.test', PW = 'Qx7!aihub-free-2026';

(async () => {
  const b = await chromium.launch(); const errs = [];
  resetGuest();
  php(`$u=get_user_by("email","${EMAIL}"); if($u){ require_once ABSPATH."wp-admin/includes/user.php"; wp_delete_user($u->ID); } wp_insert_user(["user_login"=>"aihubfree","user_email"=>"${EMAIL}","user_pass"=>"${PW}","role"=>"subscriber"]);`);
  const g = await b.newContext({ viewport: { width: 1366, height: 900 } });
  await g.grantPermissions(['clipboard-read', 'clipboard-write'], { origin: L });
  const p = await g.newPage(); p.on('pageerror', e => errs.push(e.message));

  // No code or prompt text in the gallery HTML (free or Pro).
  const freeCode = php('foreach (vsai_builtin("motion") as $i) { if ($i["id"]==="infinite-gallery") echo substr($i["code"], 300, 40); }');
  const proSpec = php('foreach (vsai_builtin("motion") as $i) { if ($i["id"]==="card-wheel-story") echo substr($i["spec"], 200, 40); }');
  let html = await (await p.goto(L + '/ai/motion/')).text();
  ok(!html.includes(freeCode.slice(0, 30)) && !html.includes(proSpec.slice(0, 30)), 'gallery HTML holds no code or prompt text');
  ok(!/>Premium</.test(html) && (html.match(/vsai-crown/g) || []).length >= 9, 'Pro items show only the crown icon (no "Premium" word)');
  ok(html.includes('assets/posters/card-wheel-story.webp'), 'Pro design shows its poster to visitors');

  // Previews load lazily from the sandboxed view URL.
  await p.goto(L + '/ai/motion/', { waitUntil: 'load' }); await p.locator('.vsai-motion').scrollIntoViewIfNeeded(); await p.waitForTimeout(1200);
  ok(await p.locator('.vsai-frame iframe[data-on="1"]').count() >= 2, 'visible tiles load live previews');
  const v = await p.request.get(L + '/ai/motion/view/infinite-gallery/?demo=1');
  ok(/sandbox allow-scripts/.test(v.headers()['content-security-policy'] || ''), 'preview view is CSP-sandboxed');

  // Visitor: 2 free copies, then the gate.
  const copyBtn = p.locator('.vsai-ico-btn[data-copy-mo="pixel-morph-hero"]');
  await copyClick(p, copyBtn);
  let clip = await p.evaluate(() => navigator.clipboard.readText());
  ok(clip.startsWith('# Pixel Morph Hero'), 'visitor copy 1: full build prompt copied');
  await copyClick(p, p.locator('.vsai-ico-btn[data-copy-mo="infinite-gallery"]'));
  clip = await p.evaluate(() => navigator.clipboard.readText());
  ok(clip.startsWith('# Infinite Gallery'), 'visitor copy 2: copied');
  await copyClick(p, p.locator('.vsai-ico-btn[data-copy-mo="mouse-trail-cta"]'));
  ok(await p.locator('.vsai-dialog[open] .vsai-gate').count() === 1 && /free copy limit/i.test(await p.locator('[data-gate-msg]').textContent()), 'visitor copy 3: "Copy without limits" gate');
  ok(await p.locator('.vsai-dialog[open] a:has-text("Create free account")').count() === 1, 'gate offers free account');
  await p.keyboard.press('Escape');
  const r402 = await p.request.post(L + '/wp-json/vsai/v1/copy', { data: { id: 'sphere-gallery', part: 'code' } });
  ok(r402.status() === 402, 'Pro code refused for visitors (402)');
  const bad = await p.request.post(L + '/wp-json/vsai/v1/copy', { data: { id: '../etc', part: 'code' } });
  ok(bad.status() === 400, 'invalid id rejected (400)');

  // Pro detail panel shows the lock, not buttons.
  await p.locator('.vsai-ico-btn.is-pro').first().click();
  ok(await p.locator('.vsai-dialog[open] .vsai-lock').count() === 1 && await p.locator('.vsai-dialog[open] [data-copy-mo]').count() === 0, 'Pro detail: lock card, no copy buttons');
  await p.keyboard.press('Escape');

  // Free account: copies with a daily counter, limit 10.
  resetGuest();
  const a = await b.newContext({ viewport: { width: 1366, height: 900 } }); await a.grantPermissions(['clipboard-read', 'clipboard-write'], { origin: L });
  const q = await a.newPage(); q.on('pageerror', e => errs.push(e.message));
  php('global $wpdb; $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE \'%vs_otp_gap_%\' OR option_name LIKE \'%vs_rl_%\'");');
  php(`$u=get_user_by("email","${EMAIL}"); update_user_meta($u->ID,"vs_trusted",[]);`);
  // Sign in through WordPress auth cookies (the account flow itself is covered by auth-flow.e2e.js).
  const ck = JSON.parse(php(`$u=get_user_by("email","${EMAIL}"); $exp=time()+3600; $tok=WP_Session_Tokens::get_instance($u->ID)->create($exp); echo json_encode(["n"=>LOGGED_IN_COOKIE,"v"=>wp_generate_auth_cookie($u->ID,$exp,"logged_in",$tok)]);`));
  await a.addCookies([{ name: ck.n, value: ck.v, url: L }]);
  await q.goto(L + '/ai/motion/', { waitUntil: 'load' });
  ok(await q.evaluate(() => document.body.classList.contains('logged-in')), 'free account signed in');
  await q.locator('[data-open="#mo-pixel-morph-hero"]').first().click();
  ok(/10 free copies left today/.test(await q.locator('.vsai-dialog[open] [data-left]').textContent()), 'free account: "10 free copies left today"');
  await copyClick(q, q.locator('.vsai-dialog[open] [data-copy-mo][data-part="code"]:not([data-download])'));
  clip = await q.evaluate(() => navigator.clipboard.readText());
  ok(clip.startsWith('<!doctype html>') && /9 free copies left today/.test(await q.locator('.vsai-dialog[open] [data-left]').textContent()), 'free account: code copied, counter 9');
  const [dl] = await Promise.all([q.waitForEvent('download'), q.locator('.vsai-dialog[open] [data-download]').click()]);
  ok(dl.suggestedFilename() === 'pixel-morph-hero.html', 'free account: download .html');
  await q.keyboard.press('Escape');
  php(`$u=get_user_by("email","${EMAIL}"); update_user_meta($u->ID,"vsai_copies_".gmdate("Ymd"),10);`);
  await copyClick(q, q.locator('.vsai-ico-btn[data-copy-mo="infinite-gallery"]'));
  ok(/today’s free copies/.test(await q.locator('[data-gate-msg]').textContent().catch(() => '')) && await q.locator('.vsai-dialog[open] a:has-text("Create free account")').count() === 0, 'free account: daily limit gate (no sign-up button)');
  await q.keyboard.press('Escape');

  // Access rules: a course track does NOT unlock AI Pro; an AI plan does.
  ok(php(`$u=get_user_by("email","${EMAIL}"); update_user_meta($u->ID,"vs_premium",["data-scientist"=>time()+86400]); echo vsai_is_member($u->ID)?"y":"n";`) === 'n', 'course track buyer is not AI Pro');
  const plans = php('$p=vitalstack_pay_plans(); echo isset($p["ai-month"],$p["ai-year"],$p["ai-lifetime"]) && $p["ai-year"]["tracks"]===["ai-hub"] ? "ok" : "no";');
  ok(plans === 'ok', 'AI Pro plans registered with the checkout');
  const granted = php(`$u=get_user_by("email","${EMAIL}"); $o=wp_insert_post(["post_type"=>"vs_order","post_status"=>"publish","post_author"=>$u->ID,"post_title"=>"t"]); update_post_meta($o,"_vs_plan","ai-year"); update_post_meta($o,"_vs_status","created"); vitalstack_pay_complete($o,"pay_TEST123"); $a=get_user_meta($u->ID,"vs_premium",true); echo (isset($a["ai-hub"]) && $a["ai-hub"]>time()+360*86400 ? "y":"n").(vsai_is_member($u->ID)?"y":"n"); wp_delete_post($o,true); delete_option("vs_pay_done_".$o);`);
  ok(granted === 'yy', 'paying for ai-year grants 365 days of AI Pro');
  await q.goto(L + '/ai/motion/', { waitUntil: 'load' });
  await q.locator('[data-open="#mo-card-wheel-story"]').first().click();
  ok(await q.locator('.vsai-dialog[open] .vsai-mo2-stage iframe').count() === 1 && /Unlimited/.test(await q.locator('.vsai-dialog[open] [data-left]').textContent()), 'AI Pro: Pro design live preview + unlimited');
  await copyClick(q, q.locator('.vsai-dialog[open] [data-copy-mo][data-part="prompt"]'));
  clip = await q.evaluate(() => navigator.clipboard.readText());
  ok(clip.startsWith('# Card Wheel Story'), 'AI Pro: Pro build prompt copied');
  const rr = await q.request.get(L + '/premium/ai-hub/', { maxRedirects: 0 });
  ok(rr.status() === 302 && /vsai_paid=1/.test(rr.headers()['location'] || ''), 'after payment /premium/ai-hub/ → AI Hub thank-you');

  // Pricing page.
  await p.goto(L + '/ai/pricing/', { waitUntil: 'load' });
  ok(await p.locator('.vsai-plan').count() === 4 && await p.locator('.vsai-plan.is-best h2').textContent() === 'AI Pro Yearly', 'pricing: Free + 3 plans, Yearly highlighted');
  const amt = p.locator('.vsai-plan.is-best .plan-amount');
  if (await p.locator('.vsai-cur [data-cur="usd"]').count()) {
    await p.click('.vsai-cur [data-cur="usd"]'); const usd = await amt.textContent();
    await p.click('.vsai-cur [data-cur="inr"]'); const inr = await amt.textContent();
    ok(/\$\s?59/.test(usd) && /₹\s?1,999/.test(inr), `pricing: currency switch ${usd} / ${inr}`);
  } else ok(true, 'pricing: currency fixed by region');
  ok(/Save \d+% vs monthly/.test(await p.locator('.vsai-plan.is-best .vsai-plan-per').textContent()), 'pricing: honest saving vs monthly');
  const sm = await (await p.goto(L + '/wp-sitemap-aihub-1.xml')).text(); ok(/\/ai\/pricing\//.test(sm), 'sitemap includes /ai/pricing/');

  // ── 1.2: big catalogue, paged lists, generated designs ──
  const cnt = JSON.parse(php('while (!vsai_store_step()) {} echo json_encode([vsai_prompt_total(), count(vsai_items("motion")), vsai_store_state()["done"]]);'));
  ok(cnt[0] === 100000 && cnt[1] === 10000 && cnt[2] === '2.0.0', `catalogue: ${cnt[0]} prompts in the database, ${cnt[1]} motion designs`);
  await p.goto(L + '/ai/prompts/', { waitUntil: 'load' });
  ok(await p.locator('.vsai-prompt').count() === 30 && /of 100,000/.test(await p.locator('.vsai-count').textContent()), 'prompts: 30 per page with total count');
  ok(await p.locator('.vsai-pager a[href*="pg=2"]').count() >= 1, 'prompts: pager links to page 2');
  await p.click('.vsai-chips a:has-text("Coding")'); await p.waitForLoadState('load');
  const cats = await p.locator('.vsai-prompt').evaluateAll(els => [...new Set(els.map(e => e.getAttribute('data-cat')))]);
  ok(/cat=coding/.test(p.url()) && cats.length === 1 && cats[0] === 'Coding' && /Coding AI prompts/.test(await p.title()), 'category chip opens its own indexable page');
  await p.fill('#vsai-q', 'kubernetes'); await p.press('#vsai-q', 'Enter'); await p.waitForLoadState('load');
  const robots = await p.locator('meta[name="robots"]').getAttribute('content');
  ok(/q=kubernetes/.test(p.url()) && /noindex/.test(robots) && await p.locator('.vsai-prompt').count() >= 1, 'search runs on the server and is noindex');
  const proP = JSON.parse(php('foreach (vsai_builtin("prompts") as $i) { if ($i["premium"] && strlen($i["prompt"]) > 120) { echo json_encode([$i["title"], substr($i["prompt"], 40, 50)]); break; } }'));
  html = await (await p.goto(L + '/ai/prompts/?q=' + encodeURIComponent(proP[0]))).text();
  ok(html.includes('vsai-plock') && !html.includes(proP[1].replace(/&/g, '&amp;')), 'Pro prompt text never in a visitor page');
  const rd = await p.request.get(L + '/ai/motion/?pg=999', { maxRedirects: 0 });
  ok(rd.status() === 301 && /pg=417/.test(rd.headers()['location'] || ''), 'page past the end redirects to the last page');
  const gen = JSON.parse(php('$f=null;$p=null; foreach (vsai_items("motion") as $i) { if (($i["src"]??"")!=="gen2") continue; if (!$i["premium"] && !$f) $f=$i; if ($i["premium"] && !$p) $p=$i; } echo json_encode([$f["id"],$f["title"],$p["id"],$p["title"],$p["shard"],$p["poster"]]);'));
  resetGuest();
  await p.goto(L + '/ai/motion/?q=' + encodeURIComponent(gen[1]), { waitUntil: 'load' });
  await p.locator(`[data-open="#mo-${gen[0]}"]`).first().click();
  await copyClick(p, p.locator('.vsai-dialog[open] [data-copy-mo][data-part="prompt"]'));
  clip = await p.evaluate(() => navigator.clipboard.readText());
  ok(clip.startsWith('# ' + gen[1]) && /## Motion & interaction/.test(clip), 'generated free design: detailed build prompt copied');
  await copyClick(p, p.locator('.vsai-dialog[open] [data-copy-mo][data-part="code"]').first());
  clip = await p.evaluate(() => navigator.clipboard.readText());
  ok(clip.startsWith('<!doctype html>') && clip.length > 2000, 'generated free design: full HTML copied');
  html = await (await p.goto(L + '/ai/motion/?q=' + encodeURIComponent(gen[3]))).text();
  ok(html.includes('assets/posters/g/' + gen[5] + '.webp') && !html.includes('/ai/motion/view/' + gen[2]), 'generated Pro design: poster only for visitors');
  const gv = await p.request.get(L + '/ai/motion/view/' + gen[2] + '/', { maxRedirects: 0 });
  ok(gv.status() === 302, 'generated Pro design: full-screen view locked for visitors');
  const raw = await p.request.get(L + '/wp-content/plugins/vitalstack-ai-hub/data/motion/' + gen[4] + '.php');
  const rawP = await p.request.get(L + '/wp-content/plugins/vitalstack-ai-hub/data/prompts/part-000.php');
  ok((await raw.body()).length < 50 && (await rawP.body()).length < 50, 'design and prompt data files give nothing when opened directly');
  const mv = await q.request.get(L + '/ai/motion/view/' + gen[2] + '/');
  ok(mv.status() === 200 && (await mv.text()).startsWith('<!doctype html>'), 'AI Pro member: generated Pro design opens');
  // ── 2.0: prompt finder, fill-in, suggestions, pricing, cross-sell ──
  await p.goto(L + '/ai/prompts/', { waitUntil: 'load' });
  ok(await p.locator('.vsai-catcard').count() >= 25, 'prompt home shows the category grid');
  const sg = await (await p.request.get(L + '/wp-json/vsai/v1/suggest?q=instagram%20bakery')).json();
  ok(Array.isArray(sg) && sg.length > 0 && sg.every(x => x.title && !('prompt' in x)), 'suggest API returns titles only');
  await Promise.all([p.waitForResponse(r => r.url().includes('/vsai/v1/suggest'), { timeout: 20000 }).catch(() => {}), p.fill('#vsai-q', 'python unit')]); await p.waitForTimeout(300);
  ok(await p.locator('#vsai-sugg li').count() > 0, 'search box shows suggestions while typing');
  await p.goto(L + '/ai/prompts/?cat=social-media&sub=instagram&price=free', { waitUntil: 'load' });
  await p.locator('[data-fill]').first().click(); await p.waitForTimeout(400);
  await p.locator('.vsai-dialog[open] [data-fill-fields] input').first().fill('Crumb & Co.'); await p.waitForTimeout(200);
  const prev = await p.locator('.vsai-dialog[open] [data-fill-preview]').textContent();
  await p.locator('.vsai-dialog[open] [data-fill-copy]').click(); await p.waitForTimeout(500);
  clip = await p.evaluate(() => navigator.clipboard.readText());
  ok(prev.includes('Crumb & Co.') && clip === prev, 'fill in & copy builds the finished prompt');
  await p.keyboard.press('Escape');
  const tk = await p.request.get(L + '/ai/prompts/?cat=coding&tool=nope', { maxRedirects: 0 });
  ok(tk.status() === 301, 'unknown filter values redirect to a clean URL');
  html = await (await p.goto(L + '/ai/pricing/')).text();
  ok(!/plan-amount[^>]*>₹0</.test(html) && /₹399|\$9/.test(html), 'pricing shows real prices, never ₹0 for paid plans');
  const deep = await p.goto(L + '/ai/prompts/?cat=coding&pg=2'); const rb = await p.locator('meta[name="robots"]').getAttribute('content');
  ok(/noindex/.test(rb), 'deep list pages are noindex (only category landing pages index)');
  await p.goto(L + '/ai/motion/', { waitUntil: 'load' });
  ok(await p.locator('.vsai-show-card').count() >= 6 && await p.locator('.vsai-proband').count() === 1, 'motion page: 3D showcase and one AI Pro band');
  await p.locator('.vsai-tile:not(:has(.is-pro)) .vsai-frame').first().click(); await p.waitForTimeout(1500);
  const box = await p.locator('.vsai-dialog[open] .vsai-mo2-stage iframe').boundingBox(), st2 = await p.locator('.vsai-dialog[open] .vsai-mo2-stage').boundingBox();
  ok(box && Math.abs(box.width - st2.width) < 4, 'detail preview renders at desktop size scaled to fit');
  await p.click('.vsai-dialog[open] [data-dev="phone"]'); await p.waitForTimeout(300);
  const pb = await p.locator('.vsai-dialog[open] .vsai-mo2-stage iframe').boundingBox();
  ok(pb && pb.width < st2.width * .6 && pb.height <= st2.height + 2, 'phone preview toggle');
  await p.keyboard.press('Escape');
  ok(await p.locator('.vsai-courses li').count() >= 1 || !php('echo function_exists("vitalstack_pay_plans") ? 1 : 0;').includes('1'), 'career-track cross-sell shows course prices');
  const sm2 = await (await p.goto(L + '/wp-sitemap-aihub-1.xml')).text(); ok(/\/ai\/prompts\/\?cat=coding/.test(sm2) && /\/ai\/motion\/\?cat=/.test(sm2), 'sitemap lists category pages');

  // Phone layout.
  const m = await b.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true }); const mp = await m.newPage();
  for (const path of ['/ai/motion/', '/ai/pricing/', '/ai/prompts/', '/ai/prompts/?cat=image-generation&kind=image', '/ai/motion/?cat=scroll-story']) { await mp.goto(L + path, { waitUntil: 'load' }); ok(await mp.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), 'phone: no sideways scroll on ' + path); }

  php(`$u=get_user_by("email","${EMAIL}"); require_once ABSPATH."wp-admin/includes/user.php"; wp_delete_user($u->ID);`); resetGuest();
  ok(errs.length === 0, 'no JS errors ' + errs.join(' | '));
  await b.close(); console.log(`${pass}/${pass + fail}`); process.exit(fail ? 1 : 0);
})().catch(e => { console.log('CRASH', e.message); process.exit(2); });
