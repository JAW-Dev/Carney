<?php

/**
 * Column Section block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'section-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'wp-block-carney-section';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$container_width = get_field('container_width') ?: 'contained';
$container_css_class = trim('container' . ('full' === $container_width ? '-fluid ' : ' ') . get_field('container_css_class'));
$row_css_class = trim('row ' . get_field('row_css_class'));
$section_header = trim(get_field('section_header'));
$section_footer = trim(get_field('section_footer'));
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <div class="<?= $container_css_class ?>">
    <?php if (!empty($section_header)): ?>
      <header>
        <?= $section_header ?>
      </header>
    <?php endif; ?>

    <?php if (have_rows('columns')): ?>
      <div class="<?= $row_css_class ?>">
        <?php while (have_rows('columns')): the_row('columns'); ?>
          <?php
            $column_content = get_sub_field('column_content');
            $column_span = [];
            $column_span[] = ['sm', get_sub_field('column_span_sm')];
            $column_span[] = ['md', get_sub_field('column_span_md')];
            $column_span[] = ['lg', get_sub_field('column_span_lg')];
            $column_span[] = ['xl', get_sub_field('column_span_xl')];
            $column_span_class = '';
            foreach ($column_span as $index => $span):
              if ($span[1] >= 0):
                $is_different = ($index > 0 && $span !== $column_span[$index - 1][1]);
                if (empty($column_span_class) && $is_different):
                  $column_span_class .= " col-{$span[0]}-{$span[1]}";
                endif;
              endif;
            endforeach;
            $column_span_class = $column_span_class ?: 'col';
            $column_css_class = trim($column_span_class . " " . get_sub_field('column_css_class'));
          ?>
          <div class="<?= $column_css_class ?>">
            <?= $column_content ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($section_footer)): ?>
      <footer>
        <?= $section_footer ?>
      </footer>
    <?php endif; ?>
  </div>
</div>
