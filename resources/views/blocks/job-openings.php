<?php

/**
 * Job Openings block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$blockId = $block['id'];
if( !empty($block['anchor']) ) {
    $blockId = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'job-openings';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Fetch job openings
$date = new \DateTime();
$today = $date->format('Ymd');
$job_openings = array_map(function($job_opening) {
  $fields = get_fields($job_opening->ID);
  $fields['industries'] = !empty($fields['industries']) ? explode("\n", $fields['industries']) : [];
  $fields['locations'] = !empty($fields['locations']) ? explode("\n", $fields['locations']) : [];
  $fields['company'] = !empty($fields['company']) ? $fields['company']->name : null;

  foreach (['job_types', 'experience_levels'] as $key):
    if (!empty($value = $fields[$key])):
      $fields[$key] = array_map(function($term) {
        return $term->name;
      }, $value);
    endif;
  endforeach;

  if (!empty($fields['remote_friendly'])):
    $fields['locations'][] = 'Remote';
  endif;

  return array_merge(
    [
      'id' => $job_opening->ID,
      'posted' => get_the_date('M j, Y', $job_opening),
      'description' => apply_filters('the_content', $job_opening->post_excerpt)
    ],
    $fields
  );
}, get_posts([
  'post_type' => 'job_opening',
  'posts_per_page' => 100,
  'meta_query' => [
    'relation' => 'AND',
    [
      'key' => 'expires',
      'compare' => '>',
      'value' => $today
    ],
  ]
]));

// Load values and assign defaults.
?>
<div id="<?= $blockId ?>" class="<?= $className ?>">
  <?php if (!empty($job_openings)): ?>
    <div class="p-2 border" style="border-radius: 0.5rem;">
      <table class="table text-dark small mb-0">
        <thead>
          <tr>
            <th className="job-title border-top-0">Job Title</th>
            <th className="job-company border-top-0">Company</th>
            <th className="job-location border-top-0 d-none d-sm-table-cell">Location</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($job_openings as $job_opening): ?>
            <?php
              extract(shortcode_atts([
                'id' => null,
                'posted' => null,
                'description' => '',
                'job_title' => '',
                'company' => null,
                'industries' => '',
                'locations' => '',
                'remote_friendly' => false,
                'link' => '',
                'job_types' => [],
                'experience_levels' => [],
                'contact_name' => '',
                'contact_email' => '',
              ], $job_opening));
            ?>
            <tr id="job-<?= $id ?>" class="job">
              <td class="job-title"><a href="#job-<?= $id ?>" data-job="<?= $id ?>" class="no-scroll font-weight-bold text-pink-dark job__view-link"><?= $job_title ?></a></td>
              <td class="job-company"><?= $company ?: '' ?></td>
              <td class="job-location d-none d-sm-table-cell"><?= empty($locations) ? '&ndash;' : implode(' | ', $locations) ?></td>
            </tr>
            <tr class="job-details">
              <td colspan="3">
                <div class="row">
                  <div class="job-location col-12 d-sm-none">
                    <p>
                      <strong>Location</strong><br>
                      <?= empty($locations) ? '&ndash;' : implode(' | ', $locations) ?>
                    </p>
                  </div>
                  <div class="job-experience col-sm-4">
                    <p>
                      <strong>Level</strong><br>
                      <?= empty($experience_levels) ? '&ndash;' : implode(' | ', $experience_levels) ?>
                    </p>
                  </div>
                  <div class="job-type col-sm-4">
                    <p>
                      <strong>Type</strong><br>
                      <?= empty($job_types) ? '&ndash;' : implode(' | ', $job_types) ?>
                    </p>
                  </div>
                  <div class="job-industry col-sm-4">
                    <p>
                      <strong>Industry</strong><br>
                      <?= empty($industries) ? '&ndash;' : implode(' | ', $industries) ?>
                    </p>
                  </div>
                  <div class="job-description col-md-9">
                    <?php if (!empty($description)): ?>
                      <?= $description ?>
                    <?php endif; ?>
                    <p class="small"><em>Posted <?= $posted ?></em></p>
                  </div>
                  <div class="job-apply col-md-3 d-flex flex-column justify-content-end align-items-md-end">
                    <p>
                      <a class="btn btn-sm btn-dark px-3 job__apply-link" data-job="<?= $id ?>" href="<?=
                        !empty($link) ? add_query_arg([
                          'utm_source' => 'daily-carnage',
                          'utm_medium' => 'web'
                        ], $link) : add_query_arg([
                          'subject' => "$job_title job",
                          'body' => "%0A%0A---%0ASent from a job listing at " . get_permalink()
                        ], "mailto:&quot;$contact_name&quot;&lt;$contact_email&gt;")
                      ?>">Apply Now</a>
                    </p>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <script type="text/javascript">
      window.jobOpeningsBlocks = window.jobOpeningsBlocks || {};
      window.jobOpeningsBlocks['<?= $blockId ?>'] = {
        jobOpenings: <?= json_encode($job_openings) ?>
      };
    </script>
  <?php else: ?>
    <p class="lead my-5 text-center">Sorry, there are no available job openings.</p>
  <?php endif; ?>
</div>
