<?php
/**
 * Plugin Name: Custom Slideshow by Shortcode
 * Description: Display slideshow by shortcodes.
 * Version: 1.0.0
 * Tested up to: 7.4
 * Requires PHP: 7.4
 * Requires at least: 5.6.8
 * Text Domain: custom-slideshow
 * Domain Path: /languages
 * Network: True
 */
if (!defined('ABSPATH')) die;

define('CS_TEXT_DOMAIN', 'custom-slidehow');

define('CS_FILE', __FILE__);
define('CS_BASENAME', plugin_basename(CS_FILE));
define('CS_DIRECTORY', plugin_dir_path(CS_FILE));
define('CS_DIRECTORY_URI', plugin_dir_url(CS_FILE));
define('CS_VERSION', '1.0.0');

if (!class_exists('CUSTOM_SLIDESHOW')) {
    class CUSTOM_SLIDESHOW
    {

        private static $instance;

        public static function get_instance()
        {
            if (!self::$instance) {
                self::$instance = new CUSTOM_SLIDESHOW();
            }
            return self::$instance;
        }

        public function __construct()
        {
            /**
             * admin menu setting
             */
            add_action('admin_menu', array($this, 'admin_menu'));

            /**
             * front end javascript, stylesheets loading
             */
            add_action('wp_enqueue_scripts', array($this, 'enqueue_front_end_scripts'));

            /**
             * admin side javascript, stylesheets loading
             */
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

            /**
             * file upload ajax treat
             */
            add_action('wp_ajax_cs_upload_slides', array($this, 'upload_slides'));
            add_action('wp_ajax_cs_save_slides_order', array($this, 'save_slides_order'));
            add_action('wp_ajax_cs_remove_slide', array($this, 'remove_slide'));

            add_shortcode('myawesomecar', array($this, 'display_slideshow'));
        }

        /**
         * admin menu page
         * @return void
         */
        function admin_menu()
        {
            add_menu_page(
                'Custom Slideshow', // page title
                'Custom Slideshow', // menu title
                'manage_options', // capability
                'custom-slideshow', //menu slug
                array($this, 'admin_page_renderer'), // page renderer call back function
                'dashicons-calendar', // admin icon class
                99 // position on admin sidebar
            );
            /**
             * put admin menu under Settings admin menu item
             */
//            add_submenu_page(
//                'options-general.php', // slug for Settings page
//                'Custom Slideshow', // page title
//                'Custom Slideshow', // menu title
//                'manage_options', // capability
//                'custom-slideshow', //menu slug
//                array($this, 'admin_page_renderer'), // page renderer call back function
//                99 // position on admin sidebar
//            );
        }

        /**
         * admin page renderer
         * @return void
         */
        function admin_page_renderer()
        {
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="w-100 mt-10">
                            <h1 class="mb-3 fw-boldest fs-1 text-dark">Slides Upload</h1>
                            <div id="portfoliosFTrigger"
                                 class="mt-0 card w-200px flex-center bg-light-primary border-primary border border-dashed p-8 cursor-pointer">
                                <!--begin::Image-->
                                <img src="<?= CS_DIRECTORY_URI ?>assets/images/upload.svg" class="mb-5" alt="">
                                <!--end::Image-->
                                <!--begin::Link-->
                                <a href="javascript:void(0)" class="text-hover-primary fs-5 fw-bolder mb-2">File
                                    Upload</a>
                                <!--end::Link-->
                                <!--begin::Description-->
                                <div class="fs-7 fw-bold text-gray-400">Click here to upload files here</div>
                                <!--end::Description-->
                            </div>
                            <form id="uploadForm" enctype="multipart/form-data" class="d-none" method="post">
                                <input type="hidden" name="action" value="cs_upload_slides">
                                <input type="file" class="d-none" id="chosen_files" name="cs_files[]" multiple
                                       accept=".png, .jpg, .jpeg">
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="w-100 mt-10">
                            <h1 class="mb-3 fw-boldest fs-1 text-dark">Change Order of Slides</h1>
                            <form enctype="multipart/form-data" id="slidesOrderForm" method="post">
                                <input type="hidden" name="action" value="cs_save_slides_order"/>
                                <div class="table-responsive">
                                    <table class="table table-bordered w-100" id="slidesTable">
                                        <tr>
                                            <th>Index</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Remove</th>
                                        </tr>
                                        <?php
                                        $cs_slides = get_option('cs_slides', array());
                                        foreach ($cs_slides as $cs_slide) {
                                            $index = array_search($cs_slide, $cs_slides) + 1;
                                            $src = wp_get_attachment_image_url($cs_slide);
                                            $name = basename(get_attached_file($cs_slide));
                                            ?>
                                            <tr>
                                                <td draggable="true" ondragstart="start()" ondragover="dragover()"
                                                    ondragend="end()" class="cursor-move">
                                                    <?= $index ?>
                                                </td>
                                                <td>
                                                    <img src="<?= $src ?>" class="w-60px h-auto"/>
                                                </td>
                                                <td><?= $name ?></td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                       class="btn btn-icon btn-circle btn-active-color-danger w-25px h-25px bg-body shadow remove-slide"
                                                       data-bs-toggle="tooltip" title="Remove"
                                                       data-id="<?= $cs_slide ?>" data-bs-original-title="Remove">
                                                        <span class="svg-icon svg-icon-1 text-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.5" x="6" y="17.3137" width="16"
                                                                      height="2" rx="1"
                                                                      transform="rotate(-45 6 17.3137)"
                                                                      fill="currentColor"></rect>
                                                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                      transform="rotate(45 7.41422 6)"
                                                                      fill="currentColor">
                                                                </rect>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    <input type="hidden" name="cs_slides[]" value="<?= $cs_slide ?>">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                                <button type="submit" class="btn btn-success mt-3" href="javascript:void(0)"
                                        id="btnSaveSlidesOrder">Save
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <?php
        }

        /**
         * front end js, css files loading
         * @return void
         */
        function enqueue_front_end_scripts()
        {
            wp_enqueue_style('cs-style-bundle', CS_DIRECTORY_URI . 'assets/css/style.bundle.css', array(), CS_VERSION, 'all');
            wp_enqueue_style('cs-plugins-bundle', CS_DIRECTORY_URI . 'assets/css/plugins.bundle.css', array(), CS_VERSION, 'all');


            wp_register_script('cs-plugins-js', CS_DIRECTORY_URI . 'assets/js/plugins.bundle.js', array(), CS_VERSION, true);
            wp_enqueue_script('cs-plugins-js');
            wp_register_script('cs-scripts-js', CS_DIRECTORY_URI . 'assets/js/scripts.bundle.js', array(), CS_VERSION, true);
            wp_enqueue_script('cs-scripts-js');

            $cs_objects = array(
                'ajax_url' => admin_url('admin-ajax.php'),
            );

            wp_register_script('cs-main-js', CS_DIRECTORY_URI . 'assets/js/main.js', array(), CS_VERSION, true);
            wp_enqueue_script('cs-main-js');
            wp_localize_script('cs-main-js', 'cs_objects', $cs_objects);
        }

        /**
         * admin side js, css files loading
         * @return void
         */
        function enqueue_admin_scripts()
        {
            $screen = get_current_screen();
//            var_dump($screen->id);
            if ($screen->id == "toplevel_page_custom-slideshow") {
                wp_enqueue_style('cs-style-bundle', CS_DIRECTORY_URI . 'assets/css/style.bundle.css', array(), CS_VERSION, 'all');
                wp_enqueue_style('cs-plugins-bundle', CS_DIRECTORY_URI . 'assets/css/plugins.bundle.css', array(), CS_VERSION, 'all');


                wp_register_script('cs-plugins-js', CS_DIRECTORY_URI . 'assets/js/plugins.bundle.js', array(), CS_VERSION, true);
                wp_enqueue_script('cs-plugins-js');
                wp_register_script('cs-scripts-js', CS_DIRECTORY_URI . 'assets/js/scripts.bundle.js', array(), CS_VERSION, true);
                wp_enqueue_script('cs-scripts-js');

                $cs_objects = array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                );

                wp_register_script('cs-admin-js', CS_DIRECTORY_URI . 'assets/js/admin.js', array(), CS_VERSION, true);
                wp_enqueue_script('cs-admin-js');
                wp_localize_script('cs-admin-js', 'cs_objects', $cs_objects);
            }

        }

        function upload_slides()
        {
            $cs_slides = get_option('cs_slides', array());

            $response = array(
                'status' => true,
                'message' => 'Success',
            );

            if (isset($_FILES)) {

                if (!function_exists('wp_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                }

                $upload_overrides = array('test_form' => false);
                $added = false;

                if (count($_FILES['cs_files']['name']) > 0) {

                    $files = $_FILES['cs_files'];
                    foreach ($files['name'] as $key => $value) {
                        if ($files['name'][$key]) {
                            $uploadedfile = array(
                                'name' => $files['name'][$key],
                                'type' => $files['type'][$key],
                                'tmp_name' => $files['tmp_name'][$key],
                                'error' => $files['error'][$key],
                                'size' => $files['size'][$key]
                            );
                            $filename_to_show = $uploadedfile['name'];
                            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                            if ($movefile && !isset($movefile['error'])) {
                                $filename = $movefile['file'];
                                $filetype = wp_check_filetype(basename($filename), null);
                                $wp_upload_dir = wp_upload_dir();
                                $attachment_name = preg_replace('/\.[^.]+$/', '', basename($filename));
                                $attachment_arg = array(
                                    'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                                    'post_mime_type' => $filetype['type'],
                                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                                    'post_content' => '',
                                    'post_status' => 'inherit'
                                );
                                $attach_id = wp_insert_attachment($attachment_arg, $filename, -1);
                                $cs_slides[] = $attach_id;

                                require_once(ABSPATH . 'wp-admin/includes/image.php');

                                $attach_data = get_post_mime_type($attach_id);

                                $attach_img_data = wp_generate_attachment_metadata($attach_id, $filename);
                                wp_update_attachment_metadata($attach_id, $attach_img_data);

                            }
                        }
                    }
                }

                update_option('cs_slides', $cs_slides);

                $response = array(
                    'status' => true,
                    'message' => $_FILES['cs_files'],
                );

            }

            echo json_encode($response);
            exit;
        }

        function save_slides_order()
        {
            if (isset($_POST['cs_slides'])) {
                $slides = $_POST['cs_slides'];
                update_option('cs_slides', $slides);
            } else update_option('cs_slides', array());

            $response = array(
                'status' => true,
                'message' => 'success',
            );
            echo json_encode($response);

            exit;
        }

        function remove_slide()
        {
            $slide_id = $_POST['slide_id'];
            $cs_slides = get_option('cs_slides', array());
            unset($cs_slides[array_search($slide_id, $cs_slides)]);
            update_option('cs_slides', $cs_slides);

            $response = array(
                'status' => true,
                'message' => 'Success',
            );

            echo json_encode($response);
            exit;
        }

        function display_slideshow()
        {
            $cs_slides = get_option('cs_slides', array());
            $html = '';
            if (count($cs_slides) > 0) {
                $html = '<div class="tns tns-default">
                    <!--begin::Slider-->
                    <div
                            data-tns="true"
                            data-tns-loop="true"
                            data-tns-swipe-angle="false"
                            data-tns-speed="2000"
                            data-tns-autoplay="true"
                            data-tns-autoplay-timeout="18000"
                            data-tns-controls="true"
                            data-tns-nav="false"
                            data-tns-items="2"
                            data-tns-center="false"
                            data-tns-dots="false"
                            data-tns-prev-button="#kt_team_slider_prev1"
                            data-tns-next-button="#kt_team_slider_next1">';
                        foreach ($cs_slides as $cs_slide) {
                            $src = wp_get_attachment_image_url($cs_slide);
                            $html .= '<div class="text-center px-5 py-5">
                                <img src="'.$src.'" class="mw-100" alt=""/>
                            </div>';
                        }

                        $html .= '

                    </div>
                    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_prev1">
                        <span class="svg-icon svg-icon-3x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_next1">
                        <span class="svg-icon svg-icon-3x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                </div>';
            }
            return $html;
        }
    }
}

CUSTOM_SLIDESHOW::get_instance();