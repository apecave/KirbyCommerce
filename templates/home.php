<?php snippet('header') ?>

  <main class="main" role="main">
    <header class="wrap">
      <h1><?= $page->title()->html() ?> Plugin Template</h1>
      <div class="intro text">
        <?= $page->intro()->kt() ?>
      </div>
      <hr />
    </header>

    <div class="text wrap">
      <?= $page->text()->kt() ?>
    </div>

    <section class="projects-section">

      <div class="wrap wide">
        <h2>Latest Projects</h2>
        <?php snippet('showcase', ['limit' => 3]) ?>
        <p class="projects-section-more"><a href="<?= page('projects')->url() ?>" class="btn">show all projects &hellip;</a></p>
      </div>

    </section>

  </main>

<?php snippet('footer') ?>