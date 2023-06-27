<?php
if (!defined('ABSPATH'))
  exit;

// Displaying here plugin settings
function web86_timeline_settings_page()
{
  // we check that the user has access rights
  if (!current_user_can('manage_options')) {
    return;
  }
  ?>
  <div class="wrap">
    <h1>
      <?php echo esc_html(get_admin_page_title()); ?>
    </h1>
    <p>1) Choose where do you want the timeline form will be shown</p>
    <form method="post" action="options.php">

      <?php settings_fields('web86_timeline_plugin_settings'); ?>
      <?php do_settings_sections('web86_timeline_plugin_settings'); ?>
      <label for="web86_timeline_pages">Select Pages:</label>
      <select id="web86_timeline_pages" name="web86_timeline_pages[]" multiple>
        <?php
        $pages = get_pages();
        foreach ($pages as $page) {
          $selected = in_array($page->ID, get_option('web86_timeline_pages', array())) ? 'selected' : '';
          echo '<option value="' . esc_attr($page->ID) . '" ' . esc_html($selected) . '>' . esc_html($page->post_title) . '</option>';
        }
        ?>
      </select>
      <label for="web86_timeline_posts">Select Posts:</label>
      <select id="web86_timeline_posts" name="web86_timeline_posts[]" multiple>
        <?php
        $posts = get_posts(
          array(
            'post_type' => 'post',
            'posts_per_page' => -1,
          )
        );
        foreach ($posts as $post) {
          $selected = in_array($post->ID, get_option('web86_timeline_posts', array())) ? 'selected' : '';
          echo '<option value="' . esc_attr($post->ID) . '" ' . esc_html($selected) . '>' . esc_html($post->post_title) . '</option>';
        }
        ?>
      </select>
      <?php submit_button(); ?>
    </form>
    <p>2) Place this shortcode to the page or post in editor or into template:</p>
    <p style="font-weight:700">
      [web86_timeline]
    </p>
  </div>

  <link rel="stylesheet" href="<?php echo plugins_url('assets/css/select2.min.css', __FILE__); ?>" />
  <script src="<?php echo plugins_url("assets/js/select2.min.js", __FILE__) ?>"></script>
  <script>
    jQuery(document).ready(function ($) {
      $('#web86_timeline_pages').select2();
      $('#web86_timeline_posts').select2();
    });
  </script>
  <?php
}
