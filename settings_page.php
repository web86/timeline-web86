<?php
// Displaying here plugin settings
function timeline_web86_settings_page()
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
      <?php settings_fields('timeline_web86_plugin_settings'); ?>
      <?php do_settings_sections('timeline_web86_plugin_settings'); ?>
      <label for="timeline_web86_pages">Select Pages:</label>
      <select id="timeline_web86_pages" name="timeline_web86_pages[]" multiple>
        <?php
        $pages = get_pages();
        foreach ($pages as $page) {
          $selected = in_array($page->ID, get_option('timeline_web86_pages', array())) ? 'selected' : '';
          echo '<option value="' . esc_attr($page->ID) . '" ' . $selected . '>' . esc_html($page->post_title) . '</option>';
        }
        ?>
      </select>
      <label for="timeline_web86_posts">Select Posts:</label>
      <select id="timeline_web86_posts" name="timeline_web86_posts[]" multiple>
        <?php
        $posts = get_posts(
          array(
            'post_type' => 'post',
            'posts_per_page' => -1,
          )
        );
        foreach ($posts as $post) {
          $selected = in_array($post->ID, get_option('timeline_web86_posts', array())) ? 'selected' : '';
          echo '<option value="' . esc_attr($post->ID) . '" ' . $selected . '>' . esc_html($post->post_title) . '</option>';
        }
        ?>
      </select>
      <?php submit_button(); ?>
    </form>
    <p>2) Place this shortcode to the page or post in editor or into template:</p>
    <p style="font-weight:700">
      [timeline_web86]
    </p>
  </div>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
    jQuery(document).ready(function ($) {
      $('#timeline_web86_pages').select2();
      $('#timeline_web86_posts').select2();
    });
  </script>
  <?php
}