<?php
/**
 * Template Name: VitalStack Tutorials Hub
 * Description: W3Schools-style tutorial landing page for VitalStack.
 */

get_header();

$tutorial_topics = [
  [
    'title' => 'HTML',
    'desc'  => 'Learn the structure of web pages from scratch.',
    'url'   => home_url('/tutorials/html-tutorial-for-beginners/'),
    'color' => 'green',
  ],
  [
    'title' => 'Programming',
    'desc'  => 'Understand coding basics in simple language.',
    'url'   => home_url('/tutorials/what-is-programming-beginners-guide/'),
    'color' => 'navy',
  ],
  [
    'title' => 'React',
    'desc'  => 'Build modern interactive websites with projects.',
    'url'   => home_url('/tutorials/react-tutorial-for-beginners-with-projects/'),
    'color' => 'blue',
  ],
  [
    'title' => 'Java',
    'desc'  => 'Start backend and app development step by step.',
    'url'   => home_url('/tutorials/java-tutorial-for-beginners-2026-complete-guide/'),
    'color' => 'gold',
  ],
];
?>

<main id="main" class="vs-school-page">

  <section class="vs-school-hero">
    <div class="container">
      <span class="vs-school-kicker">VitalStack Tutorials</span>
      <h1>Learn Technology Step by Step</h1>
      <p>Simple, practical tutorials for beginners who want to understand coding, AI, and modern tech without confusion.</p>

      <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="vs-school-search">
        <input type="search" name="s" placeholder="Search tutorials..." required>
        <input type="hidden" name="post_type" value="tutorials">
        <button type="submit">Search</button>
      </form>
    </div>
  </section>

  <section class="vs-school-tabs">
    <div class="container">
      <a href="#tutorials">Tutorials</a>
      <a href="#paths">Learning Paths</a>
      <a href="#latest">Latest Guides</a>
      <a href="#start">Where to Start</a>
    </div>
  </section>

  <section id="tutorials" class="vs-school-topics">
    <div class="container">
      <div class="vs-school-section-head">
        <h2>Choose a Tutorial</h2>
        <p>Pick one topic and start learning in a clean, guided way.</p>
      </div>

      <div class="vs-school-topic-grid">
        <?php foreach ($tutorial_topics as $topic) : ?>
          <a href="<?php echo esc_url($topic['url']); ?>" class="vs-school-topic-card <?php echo esc_attr($topic['color']); ?>">
            <span><?php echo esc_html($topic['title']); ?></span>
            <p><?php echo esc_html($topic['desc']); ?></p>
            <strong>Start learning</strong>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="start" class="vs-school-feature">
    <div class="container vs-school-feature-grid">
      <div>
        <span class="vs-school-kicker">Beginner Friendly</span>
        <h2>Not Sure Where To Begin?</h2>
        <p>
          If you are new, start with Programming Basics, then move to HTML, JavaScript, React,
          and practical AI tools. This keeps learning simple and enjoyable.
        </p>
        <a href="<?php echo esc_url(home_url('/tutorials/what-is-programming-beginners-guide/')); ?>" class="vs-school-btn">
          Start With Basics
        </a>
      </div>

      <div class="vs-code-preview" aria-label="Code example preview">
        <div class="vs-code-header">
          <span></span><span></span><span></span>
        </div>
        <pre><code>&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;My First Page&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
    &lt;h1&gt;Hello VitalStack&lt;/h1&gt;
  &lt;/body&gt;
&lt;/html&gt;</code></pre>
      </div>
    </div>
  </section>

  <section id="paths" class="vs-school-paths">
    <div class="container">
      <div class="vs-school-section-head">
        <h2>Learning Paths</h2>
        <p>Follow a path instead of jumping randomly between topics.</p>
      </div>

      <div class="vs-path-grid">
        <div class="vs-path-card">
          <h3>Web Development</h3>
          <ol>
            <li>Programming Basics</li>
            <li>HTML</li>
            <li>CSS</li>
            <li>JavaScript</li>
            <li>React</li>
          </ol>
        </div>

        <div class="vs-path-card">
          <h3>AI & Productivity</h3>
          <ol>
            <li>AI Basics</li>
            <li>Prompting</li>
            <li>AI Tools</li>
            <li>Workflows</li>
            <li>Automation</li>
          </ol>
        </div>

        <div class="vs-path-card">
          <h3>Backend Basics</h3>
          <ol>
            <li>Programming Logic</li>
            <li>Java</li>
            <li>Databases</li>
            <li>APIs</li>
            <li>Projects</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section id="latest" class="vs-school-latest">
    <div class="container">
      <div class="vs-school-section-head">
        <h2>Latest Tutorials</h2>
        <p>Fresh guides from VitalStack.</p>
      </div>

      <?php
      $latest_tutorials = new WP_Query([
        'post_type'      => 'tutorials',
        'posts_per_page' => 6,
      ]);
      ?>

      <?php if ($latest_tutorials->have_posts()) : ?>
        <div class="vs-school-latest-grid">
          <?php while ($latest_tutorials->have_posts()) : $latest_tutorials->the_post(); ?>
            <article class="vs-school-article-card">
              <a href="<?php the_permalink(); ?>" class="vs-school-thumb">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('medium_large'); ?>
                <?php else : ?>
                  <span><?php the_title(); ?></span>
                <?php endif; ?>
              </a>

              <div class="vs-school-card-body">
                <span class="vs-school-badge">Tutorial</span>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                <a href="<?php the_permalink(); ?>" class="vs-school-read">Read tutorial</a>
              </div>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

</main>

<style>
.vs-school-page {
  background: #f1f5f9;
}

.vs-school-hero {
  padding: 86px 0 72px;
  text-align: center;
  background: #122045;
  color: #fff;
}

.vs-school-kicker {
  display: inline-block;
  margin-bottom: 14px;
  color: #25b067;
  font-size: 12px;
  font-weight: 800;
  letter-spacing: .12em;
  text-transform: uppercase;
}

.vs-school-hero h1 {
  margin: 0;
  color: #fff;
  font-family: 'Poppins', sans-serif !important;
  font-size: clamp(42px, 6vw, 72px);
  line-height: 1.04;
}

.vs-school-hero p {
  max-width: 720px;
  margin: 18px auto 30px;
  color: rgba(255,255,255,.76);
  font-size: 18px;
}

.vs-school-search {
  display: flex;
  max-width: 620px;
  margin: 0 auto;
  padding: 7px;
  background: #fff;
  border-radius: 999px;
}

.vs-school-search input {
  flex: 1;
  border: 0;
  outline: 0;
  padding: 15px 20px;
  font-size: 15px;
}

.vs-school-search button,
.vs-school-btn {
  border: 0;
  border-radius: 999px;
  padding: 14px 24px;
  background: #04aa6d;
  color: #fff;
  font-weight: 800;
  cursor: pointer;
}

.vs-school-tabs {
  position: sticky;
  top: 68px;
  z-index: 80;
  background: #fff;
  border-bottom: 1px solid #dbe4ea;
}

.vs-school-tabs .container {
  display: flex;
  gap: 8px;
  overflow-x: auto;
  padding-top: 12px;
  padding-bottom: 12px;
}

.vs-school-tabs a {
  padding: 10px 16px;
  border-radius: 999px;
  background: #f1f5f9;
  color: #122045;
  font-weight: 700;
  white-space: nowrap;
}

.vs-school-tabs a:hover {
  background: #04aa6d;
  color: #fff;
}

.vs-school-topics,
.vs-school-paths,
.vs-school-latest {
  padding: 64px 0;
}

.vs-school-section-head {
  margin-bottom: 28px;
}

.vs-school-section-head h2 {
  margin: 0 0 8px;
  color: #122045;
  font-family: 'Poppins', sans-serif !important;
  font-size: clamp(30px, 4vw, 44px);
}

.vs-school-section-head p {
  color: #64748b;
}

.vs-school-topic-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 18px;
}

.vs-school-topic-card {
  min-height: 220px;
  padding: 24px;
  border-radius: 16px;
  color: #122045;
  background: #fff;
  box-shadow: 0 10px 28px rgba(18,32,69,.08);
  transition: transform .22s, box-shadow .22s;
}

.vs-school-topic-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 18px 44px rgba(18,32,69,.14);
}

.vs-school-topic-card span {
  display: block;
  margin-bottom: 12px;
  font-size: 30px;
  font-weight: 900;
}

.vs-school-topic-card p {
  color: #526070;
  line-height: 1.6;
}

.vs-school-topic-card strong {
  display: inline-block;
  margin-top: 18px;
}

.vs-school-topic-card.green { background: #d9eee1; }
.vs-school-topic-card.navy { background: #e8ecf5; }
.vs-school-topic-card.blue { background: #d9f0ff; }
.vs-school-topic-card.gold { background: #fff3c4; }

.vs-school-feature {
  padding: 70px 0;
  background: #d9eee1;
}

.vs-school-feature-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 44px;
  align-items: center;
}

.vs-school-feature h2 {
  color: #122045;
  font-family: 'Poppins', sans-serif !important;
  font-size: clamp(34px, 5vw, 56px);
}

.vs-school-feature p {
  max-width: 560px;
  color: #365243;
  font-size: 17px;
  line-height: 1.75;
  margin-bottom: 24px;
}

.vs-code-preview {
  overflow: hidden;
  border-radius: 14px;
  background: #0f172a;
  box-shadow: 0 20px 48px rgba(18,32,69,.22);
}

.vs-code-header {
  display: flex;
  gap: 8px;
  padding: 14px;
  background: #1e293b;
}

.vs-code-header span {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #94a3b8;
}

.vs-code-preview pre {
  margin: 0;
  padding: 24px;
  color: #e2e8f0;
  white-space: pre-wrap;
}

.vs-path-grid,
.vs-school-latest-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 22px;
}

.vs-path-card,
.vs-school-article-card {
  background: #fff;
  border: 1px solid #dbe4ea;
  border-radius: 16px;
  box-shadow: 0 10px 28px rgba(18,32,69,.07);
  overflow: hidden;
}

.vs-path-card {
  padding: 24px;
}

.vs-path-card h3 {
  color: #122045;
  font-size: 22px;
}

.vs-path-card ol {
  margin: 16px 0 0 20px;
  color: #526070;
}

.vs-path-card li {
  margin-bottom: 8px;
}

.vs-school-thumb img,
.vs-school-thumb {
  display: block;
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
  background: #e8ecf5;
}

.vs-school-card-body {
  padding: 20px;
}

.vs-school-badge {
  display: inline-block;
  margin-bottom: 10px;
  padding: 5px 10px;
  border-radius: 999px;
  background: #e8f5ee;
  color: #087a4a;
  font-size: 10px;
  font-weight: 900;
  text-transform: uppercase;
}

.vs-school-card-body h3 {
  margin: 0 0 10px;
  color: #122045;
  font-family: 'Poppins', sans-serif !important;
  font-size: 20px;
  line-height: 1.35;
}

.vs-school-card-body p {
  color: #526070;
  font-size: 14px;
}

.vs-school-read {
  display: inline-block;
  margin-top: 12px;
  color: #04aa6d;
  font-weight: 800;
}

@media (max-width: 1024px) {
  .vs-school-topic-grid,
  .vs-path-grid,
  .vs-school-latest-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .vs-school-feature-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 680px) {
  .vs-school-hero {
    padding: 58px 0 48px;
  }

  .vs-school-search {
    flex-direction: column;
    border-radius: 16px;
  }

  .vs-school-topic-grid,
  .vs-path-grid,
  .vs-school-latest-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<?php get_footer(); ?>