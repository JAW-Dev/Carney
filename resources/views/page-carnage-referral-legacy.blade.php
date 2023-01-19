@extends('layouts.carnage-referral')

@section('content')
  @while(have_posts()) @php(the_post())
    <?php
      global $wpdb, $user_referrals, $user_referred_by;

      $is_admin = current_user_can('carnage_admin') || current_user_can('administrator');

      $user_referrals = !$is_admin ? [] : $wpdb->get_results("SELECT user_id, meta_value as referrals FROM {$wpdb->prefix}usermeta WHERE meta_key = 'referrals'", OBJECT_K);
      if (!empty($user_referrals)):
        foreach ($user_referrals as $id => $user):
          $user_referrals[$id] = !empty($user->referrals) ? unserialize($user->referrals) : [];
        endforeach;
      endif;

      $user_referred_by = !$is_admin ? [] : $wpdb->get_results("SELECT user_id, meta_value as referred_by FROM {$wpdb->prefix}usermeta WHERE meta_key = 'referred_by'", OBJECT_K);
      if (!empty($user_referred_by)):
        $user_referred_by = array_filter(array_map(function($user) {
          return !empty($user->referred_by) ? (int) $user->referred_by : false;
        }, $user_referred_by));
      endif;

      function username_from_email($email) {
        return preg_replace('/([^@+]*).*$/', '$1', $email);
      }

      function filter_referrals($users, $fields = ['ID','username']) {
        global $user_referrals, $user_referred_by;
        $fields = is_array($fields) && !empty($fields) ? $fields : ['ID','username'];

        if (!empty($users) && is_array($users)):
          $users = array_values(array_filter($users, function($user) {
            return is_object($user);
          }));

          foreach($users as $index => $user):
            $user = $user->to_array();

            if (in_array('referral_id', $fields)):
              $ref_pat = '/mc-\d+-([a-zA-Z0-9]*)$/';
              $user['referral_id'] = preg_match($ref_pat, $user['user_login']) ?
                             preg_replace($ref_pat, '$1', $user['user_login']) :
                             get_field('referral_id', "user_{$user['ID']}");
            endif;

            if (in_array('referred_by', $fields)):
              $user['referred_by'] = !empty($user_referred_by[$user['ID']]) ? $user_referred_by[$user['ID']] : null;
            endif;

            if (in_array('referral_count', $fields)):
              $user['referral_count'] = !empty($user_referrals[$user['ID']]) ? count($user_referrals[$user['ID']]) : 0;
            endif;

            if (in_array('username', $fields)):
              $user['username'] = username_from_email($user['user_email']);
            endif;

            if (in_array('hash', $fields)):
              $user['hash'] = md5(strtolower($user['user_email']));
            endif;

            if (in_array('user_registered', $fields)):
              $user['user_registered'] = !empty($user['user_registered']) ? date('c', strtotime($user['user_registered'])) : null;
            endif;

            $users[$index] = array_filter($user, function($value, $key) use($fields) {
              return in_array($key, $fields);
            }, 1);
          endforeach;
          return $users;
        endif;

        return [];
      }

      $ref = !empty($_GET['r']) && '*|REF_ID|*' !== $_GET['r'] ? $_GET['r'] : null;
      $referred_by = !empty($_GET['u']) && '*|REF_ID|*' !== $_GET['u'] ? $_GET['u'] : null;
      $user_search = !empty($referred_by) ? get_users(['meta_key' => 'referral_id', 'meta_value' => $referred_by]) : null;
      $user_raw = !empty($user_search) ? array_reduce($user_search, function($result, $user) use ($referred_by) {
        return (FALSE !== strpos($user->user_login, $referred_by) ? $user : $result);
      }) : null;
      $user = null;

      if (!empty($user_raw)):
        $referral_fields = $is_admin ? ['ID', 'username', 'referral_id', 'referral_count'] : [];
        $user_meta = !empty($user_raw) ? get_user_meta($user_raw->ID) : null;
        $user_meta['username'] = !empty($user_raw) ? username_from_email($user_raw->user_email) : '';
        $user_meta['referral_id'] = !empty($user_raw) ? get_field('referral_id', 'user_' . $user_raw->ID) : '';
        $user_meta['referred_by'] = !empty($user_raw) ? filter_referrals([get_field('referred_by', 'user_' . $user_raw->ID)], $referral_fields) : [];
        $user_meta['referrals'] = !empty($user_raw) ? filter_referrals(get_field('referrals', 'user_' . $user_raw->ID), $referral_fields) : [];
        foreach($user_meta as $key => $value):
          $user_meta[$key] = is_array($value) && !empty($value) && $key !== 'referrals' ? $value[0] : $value;
        endforeach;
        $user = array_filter(array_merge($user_raw->to_array(), $user_meta), function ($value, $key) {
          return in_array($key, [
            'ID',
            'username',
            'user_login',
            'user_email',
            'display_name',
            'first_name',
            'last_name',
            'referral_id',
            'referred_by',
            'referrals'
          ]);
        }, 1 /* pass both value and key to callback */ );
      endif;

      if ($is_admin):
        $admin = [];
        $admin_user_fields = ['ID', 'username', 'hash', 'user_email', 'user_login', 'user_registered', 'referral_id', 'referred_by', 'referral_count'];

        // get 100 latest referrals
        $admin['latest_referrals'] = filter_referrals(get_users([
          'role' => 'carnage_user',
          'orderby' => 'registered',
          'order' => 'DESC',
          'number' => 100,
          'meta_query' => [
            [
              'key' => 'referred_by',
              'compare' => 'exists'
            ],
            [
              'key' => 'referred_by',
              'value' => '',
              'compare' => '!='
            ]
          ]
        ]), $admin_user_fields);

        // get all referrers
        $admin['top_referrers'] = array_filter(filter_referrals(get_users([
          'role' => 'carnage_user',
          'order' => 'DESC',
          'meta_query' => [[
            'key' => 'referrals',
            'compare' => 'exists'
          ]]
        ]), $admin_user_fields), function($user) {
          return !empty($user['referral_count']);
        });

        // sort referrers by referral count
        usort($admin['top_referrers'], function ($a, $b) {
          if ($a['referral_count'] == $b['referral_count']) {
            return 0;
          }
          return $a['referral_count'] > $b['referral_count'] ? -1 : 1;
        });

        // add rank to referrers
        $rank_skip = 0;
        foreach($admin['top_referrers'] as $index => $referrer):
          $count = $referrer['referral_count'];
          $rank = $index + 1;

          if ($index > 0):
            if ($count == $admin['top_referrers'][$index - 1]['referral_count']):
              $rank_skip += 1;
              $rank -= $rank_skip;
            else:
              $rank_skip = 0;
            endif;
          endif;

          $admin['top_referrers'][$index]['referral_rank'] = $rank;
        endforeach;

        // include referred_by reference
        $admin['referred_by'] = $user_referred_by;
      endif;
    ?>

    <script type="text/javascript">
      window.admin = <?= !empty($admin) ? json_encode($admin) : 'null'; ?>;
      window.ref = <?= !empty($ref) ? "'" . $ref . "'" : 'null' ?>;
      window.user = <?= !empty($user) && !is_wp_error($user) ? json_encode($user) : 'null' ?>;
      window.rewards = <?= json_encode(get_field('referral_rewards', 'options')) ?>;
    </script>

    <main class="main">
      <div id="app-content"></div>

      <?php if (!empty($ref) && (empty($user_raw) || is_wp_error($user))): ?>
        <?php $issues = get_posts(['post_type' => 'carnage_issue', 'posts_per_page' => 3]); ?>
        <?php if (!empty($issues)): global $post; ?>
          <section class="wp-block-carney-section bg-gray text-dark py-md">
            <div class="wp-block-carney-posts pb-5">
              <div class="container">
                <h2 class="h6 mb-4 text-center">What can you expect?</h2>
                <p class="lead text-center">Check out some recent issues. Go ahead, we'll wait.</p>
                <ul class="wp-block-carney-posts__list mt-3">
                  <?php foreach ($issues as $post): setup_postdata($post); ?>
                    <li className="my-sm">
                      @include('partials.content-'.get_post_type())
                    </li>
                  <?php endforeach; wp_reset_postdata(); ?>
                </ul>
              </div>
            </div>
          </section>
        <?php endif; ?>
      <?php endif; ?>
    </main>

  @endwhile
@endsection
