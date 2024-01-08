<?php

if(!function_exists('bridge_qode_child_theme_enqueue_scripts')) {

	Function bridge_qode_child_theme_enqueue_scripts() {
		wp_register_style('bridge-childstyle', get_stylesheet_directory_uri() . '/style.css');
		wp_enqueue_style('bridge-childstyle');
        wp_enqueue_script( 'axios', 'https://unpkg.com/axios/dist/axios.min.js' );

        wp_enqueue_script('jquery');

        // Enqueue your custom JavaScript file
        wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/custom.js', array('jquery'), '1.0', true);
    
        // Pass the AJAX URL to your JavaScript file
        wp_localize_script('custom-scripts', 'ajaxurl', admin_url('admin-ajax.php'));
	}

	add_action('wp_enqueue_scripts', 'bridge_qode_child_theme_enqueue_scripts', 11);
}


function shapeSpace_display_search_form() {
   
	return get_search_form(false);
}
add_shortcode('display_search_form', 'shapeSpace_display_search_form');


function custom_bookings_search_query($query)
{
    if ($query->is_search && !is_admin() && isset($_GET['booking_search'])) {
        $booking_search = sanitize_text_field($_GET['booking_search']);

        if ($booking_search === '1') {
            $meta_query = array('relation' => 'AND');

            // Category filter
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $meta_query[] = array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['category'])
                );
            }

            // Location filter
            if (isset($_GET['location']) && !empty($_GET['location'])) {
                $meta_query[] = array(
                    'taxonomy' => 'product_location',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['location'])
                );
            }

            // Date range filter
            if (isset($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                $meta_query[] = array(
                    'key' => '_booking_date',
                    'value' => array(sanitize_text_field($_GET['start_date']), sanitize_text_field($_GET['end_date'])),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                );
            }

            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'custom_bookings_search_query');


// Add a callback function to the user_register action
add_action( 'user_register', 'create_bookable_product_after_user_register');

function create_bookable_product_after_user_register( $user_id) {
 
    var_dump($user_id);
    var_dump($_POST['Textbox_63']);

    $product_data = array(
        'post_title'   => 'Bookable Product – Private lessons',
        'post_content' => 'This is a bookable product description.',
        'post_status'  => 'publish',
        'post_type'    => 'product',
    );

    // Create the product
    $product_id = wp_insert_post($product_data);

    wp_remove_object_terms( $product_id, 'simple', 'product_type', true );
    wp_set_object_terms( $product_id, 'booking', 'product_type', true );

    // Save product data
    update_post_meta($product_id, '_visibility', 'visible');
    update_post_meta($product_id, '_stock_status', 'instock');
    update_post_meta($product_id, 'userid', $user_id);

    update_post_meta($product_id, '_wc_booking_has_resources', 'yes');
    
        // Set the resource IDs
    $resource_ids = [878]; // Replace with the actual resource IDs
    update_post_meta($product_id, '_wc_booking_linked_resources', $resource_ids);

    // Update product version
    wp_update_post(array('ID' => $product_id));

    return $product_id;

}


function set_booking_resource_availability($resource_id) {
    // Define availability data.
    $dayMap = [
        'monday' => 'time:1',
        'tuesday' => 'time:2',
        'wednesday' => 'time:3',
        'thursday' => 'time:4',
        'friday' => 'time:5',
        'saturday' => 'time:6',
        'sunday' => 'time:7',
    ];

    if (isset($_POST['data'])) {
        $data = $_POST['data'];
        $cleanedJson = stripslashes($data);

        $dataArray = json_decode($cleanedJson, true);
 
        $availabilityData = array(); // Initialize the availability data array

        $int = 1;
        foreach ($dataArray as $key => $value) {
            if ($value === true) {
                // Extract day, start, and end time information
                list($day, $start_end) = explode('_', $key);
                list($start, $end) = explode('-', $start_end);

            

                // Create the availability data for the day
                $array = $availabilityData[$int] = array(
                    'type' => $dayMap[$day],
                    'from' => $start,
                    'to' => $end,
                    'from_to' => $start . "-" . $end,
                    'bookable' => 'yes',
                    'priority' => '10',
                );

                $int++;

                var_dump($array);
            }
        }

        // Update resource availability.
        update_post_meta($resource_id, '_wc_booking_availability', $availabilityData);

        wp_die();
    }
}






function create_and_set_availability($name) {
    // Create the booking resource.
    $resource_id = create_booking_resource($name);

    // Set availability for the created resource.
    if (!is_wp_error($resource_id)) {
        set_booking_resource_availability($resource_id);
    }
}

// Hook for logged-in users
add_action('wp_ajax_custom_trigger_action', 'create_and_set_availability');
// Hook for non-logged-in users
add_action('wp_ajax_nopriv_custom_trigger_action', 'create_and_set_availability');


//add_action('rm_submission_completed', 'save_registration_data_to_custom_post', 10, 1);

function save_registration_data_to_custom_post($submission_id) {
    // Retrieve form data based on the submission ID.

    $form_data = $_POST['rm']['fields'];
    //$form_data = get_post_meta($submission_id, 'rm_data');
    $name = sanitize_text_field($form_data['Fname_8']);

    create_and_set_availability($name);

    // Create a new custom post of your custom post type.

}

// function that runs when shortcode is called
function availability_shortcode() { 
  
// Things that you want to do.
$content = '<form>'; 
$content .= '<div class="parent">';

$content .= '<div>';
$content .= '<p class="hours"></p>';
$content .= '</div>';

$content .= '<div>';
$content .= '<p>Ponedeljek</p>';
$content .= '</div>';

$content .= '<div>';
$content .= '<p>Torek</p>';
$content .= '</div>';

$content .= '<div>';
$content .= '<p>Sreda</p>';
$content .= '</div>';

$content .= '<div>';
$content .= '<p>Četrtek</p>';
$content .= '</div>';

$content .= '<div>';
$content .= '<p>Petek</p>';
$content .= '</div>';

$content .= '<div>';
$content .= '<p>Sobota</p>';
$content .= '</div>';

$content .= '<div>';
$content .= '<p>Nedelja</p>';
$content .= '</div>';

$content .= '<div class="hours">08:00-09:00</div>';
$content .= '<div><input name="monday_08:00-09:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_08:00-09:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_08:00-09:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_08:00-09:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_08:00-09:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_08:00-09:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_08:00-09:00" type="checkbox" /></div>';

$content .= '<div class="hours">09:00-10:00</div>';
$content .= '<div><input name="monday_09:00-10:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_09:00-10:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_09:00-10:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_09:00-10:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_09:00-10:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_09:00-10:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_09:00-10:00" type="checkbox" /></div>';

$content .= '<div class="hours">10:00-11:00</div>';
$content .= '<div><input name="monday_10:00-11:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_10:00-11:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_10:00-11:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_10:00-11:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_10:00-11:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_10:00-11:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_10:00-11:00" type="checkbox" /></div>';

$content .= '<div class="hours">11:00-12:00</div>';
$content .= '<div><input name="monday_11:00-12:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_11:00-12:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_11:00-12:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_11:00-12:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_11:00-12:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_11:00-12:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_11:00-12:00" type="checkbox" /></div>';

$content .= '<div class="hours">12:00-13:00</div>';
$content .= '<div><input name="monday_12:00-13:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_12:00-13:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_12:00-13:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_12:00-13:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_12:00-13:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_12:00-13:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_12:00-13:00" type="checkbox" /></div>';

$content .= '<div class="hours">13:00-14:00</div>';
$content .= '<div><input name="monday_13:00-14:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_13:00-14:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_13:00-14:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_13:00-14:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_13:00-14:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_13:00-14:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_13:00-14:00" type="checkbox" /></div>';

$content .= '<div class="hours">14:00-15:00</div>';
$content .= '<div><input name="monday_14:00-15:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_14:00-15:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_14:00-15:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_14:00-15:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_14:00-15:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_14:00-15:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_14:00-15:00" type="checkbox" /></div>';

$content .= '<div class="hours">15:00-16:00</div>';
$content .= '<div><input name="monday_15:00-16:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_15:00-16:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_15:00-16:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_15:00-16:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_15:00-16:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_15:00-16:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_15:00-16:00" type="checkbox" /></div>';

$content .= '<div class="hours">16:00-17:00</div>';
$content .= '<div><input name="monday_16:00-17:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_16:00-17:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_16:00-17:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_16:00-17:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_16:00-17:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_16:00-17:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_16:00-17:00" type="checkbox" /></div>';

$content .= '<div class="hours">17:00-18:00</div>';
$content .= '<div><input name="monday_17:00-18:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_17:00-18:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_17:00-18:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_17:00-18:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_17:00-18:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_17:00-18:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_17:00-18:00" type="checkbox" /></div>';

$content .= '<div class="hours">18:00-19:00</div>';
$content .= '<div><input name="monday_18:00-19:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_18:00-19:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_18:00-19:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_18:00-19:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_18:00-19:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_18:00-19:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_18:00-19:00" type="checkbox" /></div>';

$content .= '<div class="hours">19:00-20:00</div>';
$content .= '<div><input name="monday_19:00-20:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_19:00-20:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_19:00-20:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_19:00-20:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_19:00-20:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_19:00-20:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_19:00-20:00" type="checkbox" /></div>';

$content .= '<div class="hours">20:00-21:00</div>';
$content .= '<div><input name="monday_20:00-21:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_20:00-21:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_20:00-21:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_20:00-21:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_20:00-21:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_20:00-21:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_20:00-21:00" type="checkbox" /></div>';

$content .= '<div class="hours">21:00-22:00</div>';
$content .= '<div><input name="monday_21:00-22:00" type="checkbox" /></div>';
$content .= '<div><input name="tuesday_21:00-22:00" type="checkbox" /></div>';
$content .= '<div><input name="wednesday_21:00-22:00" type="checkbox" /></div>';
$content .= '<div><input name="thursday_21:00-22:00" type="checkbox" /></div>';
$content .= '<div><input name="friday_21:00-22:00" type="checkbox" /></div>';
$content .= '<div><input name="saturday_21:00-22:00" type="checkbox" /></div>';
$content .= '<div><input name="sunday_21:00-22:00" type="checkbox" /></div>';

$content .= '</div>';
$content .= '</form>  ';    

$script = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll("input[type=checkbox]");
    
    // Function to save checkbox state to local storage
    function saveCheckboxState() {
        const checkboxState = {};
        checkboxes.forEach(function(checkbox) {
            checkboxState[checkbox.name] = checkbox.checked;
        });
        localStorage.setItem("checkboxState", JSON.stringify(checkboxState));
    }
    
    // Function to load checkbox state from local storage
    function loadCheckboxState() {
        const checkboxState = JSON.parse(localStorage.getItem("checkboxState")) || {};
        checkboxes.forEach(function(checkbox) {
            if (checkboxState[checkbox.name] !== undefined) {
                checkbox.checked = checkboxState[checkbox.name];
            }
        });
    }
    
    // Listen for checkbox changes and save state
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", saveCheckboxState);
    });
    
    // Load checkbox state on page load
    loadCheckboxState();
});
</script>';
               
                   
        
// Output needs to be return
return $content . $script;
}
// register shortcode
add_shortcode('availability', 'availability_shortcode');
