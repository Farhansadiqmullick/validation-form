<?php

/** 
 * Plugin Name: Validation Form
 * Plugin URI: https://github.com/farhansadiqmullick/validation-form
 * Description: Form Data with Validation of Regular Expression
 * Version: 1.0
 * Author: Farhan
 * Aurthor URI: https://farhanmullick.com
 * License: GPLv2 or later
 * Text Domain: vform
 * Domain Path: /languages/
 */

if (!defined('ABSPATH'))  exit;
define('VFORM_DB_VERSION', 1.0);
include_once 'inc/class-vform-data.php';
class VFORM
{

    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'vform_plugin_textdomain'));
        add_action('admin_menu', array($this, 'vform_admin_page'));
        register_activation_hook(__FILE__, array($this, 'vform_init'));
        // register_activation_hook(__FILE__, array($this, "vform_load_data"));
        add_action('wp_enqueue_scripts', array($this, 'vform_scripts'));
        add_action('wp_footer', array($this, 'vform_frontend'));
        add_action('wp_ajax_validation', array($this, 'vform_contact'));
        add_action('wp_ajax_nopriv_validation', array($this, 'vform_contact'));
    }

    function vform_plugin_textdomain()
    {
        load_plugin_textdomain('vform', false, dirname(__FILE__) . '/languages');
    }

    function vform_init()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vform';
        $sql = "CREATE TABLE {$table_name} (id int not null AUTO_INCREMENT, name VARCHAR(25), email VARCHAR(30), phone VARCHAR(20),zipcode VARCHAR(10), PRIMARY KEY (id) );";
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");
        dbDelta($sql);
        add_option('vform_db_version', VFORM_DB_VERSION);

        if (get_option('vform_db_version' != VFORM_DB_VERSION)) {
            $sql = "CREATE TABLE {$table_name} (id int not null AUTO_INCREMENT, name VARCHAR(25), email VARCHAR(30), phone VARCHAR(20),zipcode VARCHAR(10), PRIMARY KEY (id) );";
            dbDelta($sql);
            update_option('vform_db_version', VFORM_DB_VERSION);
        }
    }

    // function vform_load_data()
    // {
    //     global $wpdb;
    //     $tableName = $wpdb->prefix . 'vform';
    //     $wpdb->insert($tableName, [
    //         'name' => 'john doe',
    //         'email' => 'john@doe.com',
    //     ]);

    //     $wpdb->insert($tableName, [
    //         'name' => 'jane doe',
    //         'email' => 'jane@doe.com',
    //     ]);
    // }

    function vform_scripts()
    {
        wp_enqueue_style('bootstrap-style', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
        wp_enqueue_style('vform-style', plugin_dir_url(__FILE__) . 'assets/form.css', '', rand(111, 999));
        wp_enqueue_script('bootstrap-popper', '//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js');
        wp_enqueue_script('bootstrap-min', '//cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js');
        wp_enqueue_script('jquery', '//code.jquery.com/jquery-3.6.0.min.js');
        wp_enqueue_script('vform-script', plugin_dir_url(__FILE__) . 'assets/main.js', ['jquery'], rand(11, 999), true);
        wp_enqueue_script('validation-js', plugin_dir_url(__FILE__) . 'assets/validation.js', ['jquery'], rand(11, 999), true);
        wp_localize_script(
            'validation-js',
            'validurl',
            array('ajaxurl' => admin_url('admin-ajax.php'))
        );
    }

    function vform_admin_page()
    {
        add_menu_page(__('Validation Form', 'vform'), __('Validation Form', 'vform'), 'manage_options', 'vform', array($this, 'vform_table_display'), 'dashicons-feedback');
    }

    function vform_table_display()
    {
        // include_once 'inc/dataset.php';

        global $wpdb;
        echo '<h2>Validation Form</h2>';

        global $wpdb;
        $vformUsers = $wpdb->get_results("SELECT id, name, email, phone, zipcode from {$wpdb->prefix}vform ORDER BY id DESC", ARRAY_A);
        $validUsers = new VFORM_DATA($vformUsers);


        /**
         * Filtering the data with Name 
         */
        function vform_search_by_name($item)
        {
            $name = strtolower($item['name']);
            $search = sanitize_text_field($_REQUEST['s']);

            if (strpos($name, $search) !== false) {
                return true;
            }
            return false;
        }

        if (isset($_REQUEST['s'])) {
            $vformUsers = array_filter($vformUsers, 'vform_search_by_name');
        }

        $validUsers->set_data($vformUsers);
        $validUsers->prepare_items();
?>

        <div class="wrap">
            <form method="get">
                <?php
                $validUsers->search_box('search', 'valid_search');
                $validUsers->display(); ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
            </form>
        </div>
<?php

    }

    function vform_contact()
    {
        $nonce = sanitize_text_field($_POST['nonce']);
        var_dump($nonce);
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $zipcode = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';

        if (wp_verify_nonce($nonce, 'vform')) {
            $name = sanitize_text_field($name);
            $email = sanitize_text_field($email);
            $phone = sanitize_text_field($phone);
            $zipcode = sanitize_text_field($zipcode);
            global $wpdb;

            $wpdb->insert("{$wpdb->prefix}vform", ['name' => $name, 'email' => $email, 'phone' => $phone, 'zipcode' => $zipcode]);
            wp_redirect(admin_url('admin.php?page=vform'));
        }
    }


    function vform_frontend()
    {
        include_once 'inc/form.php';
    }
}

new VFORM();
