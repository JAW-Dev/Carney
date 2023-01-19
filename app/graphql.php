<?php

// use WPGraphQL\Types;
// use GraphQL\Error\Debug;
// use GraphQL\Type\Definition\Type;
// use GraphQL\Type\Definition\UnionType;
// use GraphQL\Type\Definition\ObjectType;

// add_action('init', function() {
//     // Create graphql carnage_feature post object type
//     $carnageFeaturePostObjectType = Types::post_object('carnage_feature');

//     // Create custom graphql type CarnageCTALink
//     $carnageCTALink = new ObjectType([
//         'name' => 'CarnageCTALink',
//         'description' => 'A Daily Carnage call-to-action link',
//         'fields' => [
//             'url' => Type::string(),
//             'title' => Type::string(),
//             'target' => Type::string()
//         ]
//     ]);

//     // Create custom graphql type carnageFeatureCustomContentType
//     $carnageFeatureCustomContentType = new ObjectType([
//         'name' => 'Carnage_custom_content',
//         'description' => 'A Daily Carnage custom content feature',
//         'fields' => [
//             'title' => Type::string(),
//             'label' => Type::string(),
//             'style' => Type::string(),
//             'content' => Type::string(),
//             'cta_links' => Types::list_of($carnageCTALink)
//         ]
//     ]);

//     // Create custom graphql CarnageFeatureUnionType
//     $carnageFeatureUnionType = new UnionType([
//         'name' => 'CarnageFeatureUnion',
//         'types' => [
//             $carnageFeaturePostObjectType,
//             $carnageFeatureCustomContentType
//         ],
//         'resolveType' => function($value) use ($carnageFeaturePostObjectType, $carnageFeatureCustomContentType) {
//             if (isset($value->post_type)) {
//                 return ('carnage_feature' === $value->post_type) ? $carnageFeaturePostObjectType : null ;
//             } else {
//                 return $carnageFeatureCustomContentType;
//             }
//         }
//     ]);

//     // CTA Links resolver
//     function resolveCTALinks(WP_Post $post) {
//         $cta_links = \get_field('cta_links', $post->ID);
//         return !empty($cta_links) ? array_map(function($item) {
//             return $item['link'];
//         }, $cta_links) : [];
//     }

//     // Customize GraphQL content for carnage_feature posts
//     add_action( 'graphql_carnage_feature_fields', function($fields) use ($carnageCTALink) {
//         $fields['cta_links'] = [
//             'type' => Types::list_of($carnageCTALink),
//             'description' => __('Call-to-action links.'),
//             'resolve' => 'resolveCTALinks'
//         ];

//         return $fields;
//     });

//     // Custom GraphQL fields for carnage_issue posts
//     add_action( 'graphql_carnage_issue_fields', function($fields) use ($carnageFeatureUnionType) {
//         $fields['features'] = [
//             'type' => Types::list_of($carnageFeatureUnionType),
//             'description' => __('Features in this issue.'),
//             'resolve' => function(WP_Post $post) {
//                 $features = array();
//                 if (have_rows('features', $post->ID)):
//                     while (have_rows('features', $post->ID)): the_row();
//                         switch (get_row_layout()):
//                             case 'custom_content':
//                                 $cta_links = get_sub_field('cta_links', $post->ID);
//                                 $title = get_sub_field('title', $post->ID);
//                                 $label = get_sub_field('label', $post->ID);
//                                 $style = get_sub_field('style', $post->ID);
//                                 $content = get_sub_field('custom_content', $post->ID);
//                                 $features[] = (object) array(
//                                     'title' => !empty($title) ? $title : '',
//                                     'label' => !empty($label) ? $label : '',
//                                     'style' => !empty($style) ? $style : '',
//                                     'content' => !empty($content) ? $content : '',
//                                     'cta_links' => resolveCTALinks($post)
//                                 );
//                                 break;
//                             case 'feature_post':
//                                 $features[] = get_sub_field('feature_post');
//                                 break;
//                             default:
//                         endswitch;
//                     endwhile;
//                 endif;

//                 return $features;
//             }
//         ];

//         return $fields;
//     });
// });
