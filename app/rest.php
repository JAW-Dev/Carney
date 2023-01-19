<?php

namespace App;

use Hashids\Hashids;
use \DrewM\MailChimp\MailChimp;


/**
* Record a poll response
*/

function poll_response($request) {
    $params = is_array($request) ? $request : $request->get_params();
    extract(shortcode_atts([
        'poll_id' => null,
        'response' => [],
        'email' => null,
        'uid' => null,
        'clear' => false,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'username' => null /* honeypot */
    ], $params));

    if (!empty($username)):
        return ['error' => 'Please try again later.'];
    endif;

    if (empty($poll_id) || (empty($response) && empty($clear)) || empty($email)):
        return ['error' => 'Required fields missing.'];
    endif;

    $poll = $poll_id ? get_post($poll_id) : null;
    if (is_wp_error($poll)):
        return ['error' => 'Invalid poll.'];
    endif;

    $poll_choices = get_field('poll_choices', $poll) ?: [];
    $poll_choice_term_ids = array_column($poll_choices, 'term');
    $choices = !empty($response) && array_intersect($response, $poll_choice_term_ids) === $response ? get_terms(['include' => $response]) : null;
    if (is_wp_error($choices)):
        return ['error' => 'Invalid choice.'];
    endif;

    if (empty($uid)):
        // Check user status
        $status_result = carnage_status(['email' => $email]);
        if (empty($status_result['error']) && in_array($status_result['data']['status'], ['subscribed'])):
            // If subscribed, set UID from status result
            $uid = $status_result['data']['unique_email_id'];
            if (!empty($email) && !empty($uid)):
                cookie_email_uid($email, $uid);
            endif;
        elseif (($subscribe_result = carnage_subscribe(['email' => $email, 'status' => 'subscribed', 'source' => 'Poll'])) && empty($subscribe_result['error'])):
            // If not subscribed, subscribe and set UID
            $uid = $subscribe_result['data']['unique_email_id'];
        endif;
    endif;

    if (empty($uid)):
        return ['error' => 'Something went wrong. Please try again.'];
    endif;

    // Record response
    delete_post_meta($poll_id, "response_$uid");
    if (!$clear):
        $result = update_post_meta($poll_id, "response_$uid", json_encode([
            'response' => $response,
            'time' => time(),
            'ip' => $ip
        ]));
    endif;

    // Return result
    return ['success' => !empty($result), 'data' => ['uid' => $uid]];
}

add_action('rest_api_init', function() {
    register_rest_route('poll/v1', '/response', array(
        'methods' => 'POST',
        'callback' => __NAMESPACE__ . '\\poll_response',
    ));
});

/**
* Verifies that a MailChimp UID is associated with a particular email address
*/

function verify_uid($request) {
    $params = is_array($request) ? $request : $request->get_params();
    extract(shortcode_atts([
        'email' => null,
        'uid' => null,
        'username' => null /* honeypot */
    ], $params));

    if (!empty($username)):
        return ['error' => 'Please try again later.'];
    endif;

    if (empty($email) || empty($uid)):
        return ['error' => 'Both email and UID are required.'];
    endif;

    $list_id = MAILCHIMP_LIST_ID;
    $MailChimp = new MailChimp(MAILCHIMP_API_KEY);
    $mc_hash = $MailChimp->subscriberHash($email);

    // Compare UID against cached value, if available
    $saved_uid = get_transient("uid_$mc_hash");
    if (!empty($saved_uid)):
        return ['success' => ($saved_uid === $uid)];
    endif;

    // Otherwise, fetch value from MailChimp
    $result = $MailChimp->get("lists/$list_id/members/$mc_hash", [
        'fields' => 'unique_email_id'
    ]);

    if ($MailChimp->success()):
        set_transient("uid_$mc_hash", $result['unique_email_id']);
        return ['success' => ($uid === $result['unique_email_id'])];
    else:
        return ['error' => 'Please try again later.'];
    endif;
}

add_action('rest_api_init', function() {
    register_rest_route('carnage/v1', '/verify', array(
        'methods' => 'GET',
        'callback' => __NAMESPACE__ . '\\verify_uid',
    ));
});

/**
* Get subscriber's status
*/

function carnage_status($request) {
    $params = is_array($request) ? $request : $request->get_params();
    extract(shortcode_atts([
        'email' => null,
        'username' => null /* honeypot */
    ], $params));

    if (empty($email)):
        return ['error' => 'Please enter your email address.'];
    endif;

    if (!empty($username)):
        return ['error' => 'Please try again later.'];
    endif;

    $list_id = MAILCHIMP_LIST_ID;

    $MailChimp = new MailChimp(MAILCHIMP_API_KEY);
    $mc_hash = $MailChimp->subscriberHash($email);
    $result = $MailChimp->get("lists/$list_id/members/$mc_hash", [
        'fields' => 'status,unique_email_id'
    ]);

    if ($MailChimp->success()):
        return ['success' => true, 'data' => $result];
    else:
        if (preg_match('/^404/', $MailChimp->getLastError())):
            return ['success' => true, 'data' => ['status' => 'not_subscribed']];
        else:
            $error = 'Please try again later.';
            return ['error' => $error];
        endif;
    endif;
}

add_action('rest_api_init', function() {
    register_rest_route('carnage/v1', '/status', array(
        'methods' => 'GET',
        'callback' => __NAMESPACE__ . '\\carnage_status',
    ));
});

/**
* Subscribes the provided email to the Daily Carnage list with the referral ID
*/

function carnage_subscribe($request) {
    $params = is_array($request) ? $request : $request->get_params();
    extract(shortcode_atts([
        'email' => null,
        'status' => 'pending',
        'source' => null,
        'username' => null /* honeypot */
    ], $params));

    if (empty($email)):
        return ['error' => 'Please enter your email address.'];
    endif;

    if (!empty($username)):
        return ['error' => 'Please try again later.'];
    endif;

    $list_id = MAILCHIMP_LIST_ID;

    $MailChimp = new MailChimp(MAILCHIMP_API_KEY);
    $result = $MailChimp->post("lists/$list_id/members", [
        'email_address' => $email,
        'status' => 'pending' === $status ? 'pending' : 'subscribed',
        'merge_fields' => !$source ? null : [
            'SOURCE' => $source
        ]
    ]);

    if ($MailChimp->success()):
        return ['success' => true, 'data' => $result];
    else:
        $error = preg_match('/already a list member/', $MailChimp->getLastError()) ? 'You are already subscribed.' : 'Please try again later.';
        return ['error' => $error];
    endif;
}

add_action('rest_api_init', function() {
    register_rest_route('carnage/v1', '/subscribe', array(
        'methods' => 'POST',
        'callback' => __NAMESPACE__ . '\\carnage_subscribe',
    ));
});

/**
* Creates or updates a WordPress user when a MailChimp user
* subscribes, unsubscribes, or changes their email address
*/

add_action('handle_carnage_update_action', __NAMESPACE__ . '\\handle_carnage_update');
function handle_carnage_update($params) {
    extract(shortcode_atts([
        'type' => null,
        'data' => []
    ], $params));

    extract(shortcode_atts([
        'email' => null,
        'old_email' => null,
        'new_email' => null,
        'merges' => []
    ], $data));

    extract(shortcode_atts([
        'FNAME' => '',
        'LNAME' => '',
        'REF_ID' => '',
        'REF_BY' => ''
    ], $merges));

    if (empty($email) && empty($old_email)):
        return false;
    endif;

    $list_id = MAILCHIMP_LIST_ID;
    $hashids = new Hashids($list_id);
    $MailChimp = new MailChimp(MAILCHIMP_API_KEY);

    // get or create user
    $update_user = get_user_by('email', !empty($old_email) ? $old_email : $email);
    if (empty($update_user) && !empty($REF_ID)):
        $update_user_search = !empty($REF_ID) ? get_users(['meta_key' => 'referral_id', 'meta_value' => $REF_ID]) : null;
        $update_user = !empty($update_user_search) ? $update_user_search[0] : null;
    endif;

    $time = time();
    $referral_id = !empty($update_user) ? get_field('referral_id', "user_{$update_user->ID}", false) : $hashids->encode($time);
    if (empty($update_user)):
        if ('subscribe' === $type):
            // make sure referral ID is never duplicated
            $duplicate_ref_id = true;
            while (!empty($duplicate_ref_id) && !is_wp_error($duplicate_ref_id)):
                $time = time();
                $referral_id = $duplicate_ref_id === true ? $referral_id : $hashids->encode($time);
                $duplicate_ref_id = get_users([
                    'meta_key' => 'referral_id',
                    'meta_value' => $referral_id
                    ]);
            endwhile;

            // insert new user
            $user_login = "mc-$time-$referral_id";
            $user_id = wp_insert_user([
                'user_login' => $user_login,
                'user_nicename' => $user_login,
                'nickname' => $user_login,
                'display_name' => 'Carnage User',
                'user_pass' => wp_generate_password(20),
                'user_email' => !empty($new_email) ? $new_email : $email,
                'first_name' => $FNAME,
                'last_name' => $LNAME,
                'show_admin_bar_front' => false,
                'role' => 'carnage_user'
            ]);
            $update_user = get_user_by('id', $user_id);
            update_field('referral_id', $referral_id, "user_{$update_user->ID}");

            $mc_hash = $MailChimp->subscriberHash(!empty($new_email) ? $new_email : $email);
            $result = $MailChimp->patch("lists/$list_id/members/$mc_hash", [
                'merge_fields' => ['REF_ID' => $referral_id]
            ]);
        endif;
    else:
        $update_user->add_role('carnage_user');
    endif;

    // get referring user
    $referring_user_search = !empty($REF_BY) ? get_users(['meta_key' => 'referral_id', 'meta_value' => $REF_BY]) : null;
    $referring_user = !empty($referring_user_search) ? $referring_user_search[0] : null;

    if (!empty($update_user)):
        switch ($type):
            case 'subscribe':
                // set subscription status to subscribed
                update_field('subscription_status', 'subscribed', "user_{$update_user->ID}");

                if (!empty($referring_user)):
                    // set referred by to referring user
                    $referred_by = get_field('referred_by', "user_{$update_user->ID}", false);
                    if (empty($referred_by)):
                        update_field('referred_by', $referring_user->ID, "user_{$update_user->ID}");
                    endif;

                    // update referrals of referring user
                    $referrals = array_filter(array_map(function($user) {
                        return !empty($user->ID) ? $user->ID : false;
                    }, (get_field('referrals', "user_{$referring_user->ID}") ?: [])));
                    $new_referrals = empty($referrals) ? [$update_user->ID] : array_unique(array_merge($referrals, [$update_user->ID]));
                    $new_referral_count = count($new_referrals);
                    update_field('referrals', $new_referrals, "user_{$referring_user->ID}");

                    // update REF_COUNT field in MailChimp
                    $mc_hash = $MailChimp->subscriberHash($referring_user->user_email);
                    $result = $MailChimp->patch("lists/$list_id/members/$mc_hash", [
                        'merge_fields' => ['REF_COUNT' => $new_referral_count]
                    ]);

                    // add a note about updated REF_COUNT in MailChimp
                    $result = $MailChimp->post("lists/$list_id/members/$mc_hash/notes", [
                        'note' => "Automatically incremented referral count to $new_referral_count when user with referral ID $referral_id subscribed."
                    ]);
                endif;
            break;

            case 'unsubscribe':
                // set subscription status to unsubscribed
                update_field('subscription_status', 'unsubscribed', "user_{$update_user->ID}");
            break;

            case 'upemail':
                // only update email address if user is not an admin, etc
                if ($update_user->roles == ['carnage_user'] && !empty($new_email)):
                    wp_update_user([
                        'ID' => $update_user->ID,
                        'user_email' => $new_email
                    ]);
                endif;
            break;

            case 'profile':
                // update first name and last name if different
                if ($FNAME !== $update_user->first_name || $LNAME !== $update_user->last_name):
                    wp_update_user([
                        'ID' => $update_user->ID,
                        'first_name' => $FNAME,
                        'last_name' => $LNAME
                    ]);
                endif;
            break;

            case 'cleaned':
                // set subscription status to cleaned
                update_field('subscription_status', 'cleaned', "user_{$update_user->ID}");
            break;

            default:
        endswitch;
    endif;
}

/**
 * Schedules MailChimp webhook updates
 */
function carnage_update($request) {
    if (function_exists('as_schedule_single_action')):
        as_schedule_single_action(time(), 'handle_carnage_update_action', ['params' => $request->get_params()], 'carnage_update');
    endif;

    return true;
}

add_action('rest_api_init', function() {
    register_rest_route('carnage/v1', '/update', array(
        'methods' => ['GET', 'POST'],
        'callback' => __NAMESPACE__ . '\\carnage_update',
    ));
});
