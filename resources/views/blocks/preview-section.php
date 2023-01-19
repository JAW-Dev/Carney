<?php

/**
 * Preview Section block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'post-preview-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'wp-block-carney-post-preview py-lg-lg';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$label = get_field('label');
$title = get_field('title');
$description = get_field('description');
$link = get_field('link');
$image = get_field('image');
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <div class="container order-last order-lg-first">
    <div class="row">
      <div class="col-lg-6 wp-block-carney-post-preview__content-col">
        <header>
          <?php if ($label): ?>
            <div class="h6 dash mb-5"><small><?= $label ?></small></div>
          <?php endif; ?>
          <?php if ($title): ?>
            <h2 class="h4 entry-title"><?= $title ?></h2>
          <?php endif; ?>
        </header>
        <?php if ($description): ?>
          <div class="entry-summary lead mb-5">
            <?= $description ?>
          </div>
        <?php endif; ?>
        <?php if ($link && $link['url']): ?>
          <a class="btn btn-primary btn--gradient" href="<?= $link['url'] ?>" target="<?= $link['target'] ?>">
            <?= $link['title'] ?: 'Read More' ?>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="container-fluid wp-block-carney-post-preview__image-container">
    <div class="row">
      <div class="col-lg-6 offset-lg-6 wp-block-carney-post-preview__image-col">
        <?php if ($image): ?>
          <?= wp_get_attachment_image($image['id'], 'large_wide') ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
