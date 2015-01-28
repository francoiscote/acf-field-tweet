<?php

class acf_field_tweet extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'tweet';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Tweet', 'acf-tweet');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = __('Social Media', 'acf-tweet');


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'consumer_key'               => '',
			'consumer_secret'            => '',
			'token'                      => '',
			'secret'                     => '',
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('tweet', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-tweet'),
		);


		// do not delete!
    	parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		acf_render_field_setting( $field, array(
			'required'  => true,
			'label'			=> __('Consumer Key','acf-tweet'),
			'instructions'	=> __('Create a Twitter app and get your credentials at: ','acf-flickr') . ' <a href="https://apps.twitter.com/">https://apps.twitter.com/</a>',
			'type'			=> 'text',
			'name'			=> 'consumer_key'
		));

		acf_render_field_setting( $field, array(
			'required'  => true,
			'label'			=> __('Consumer Secret','acf-tweet'),
			'type'			=> 'text',
			'name'			=> 'consumer_secret'
		));

		acf_render_field_setting( $field, array(
			'required'  => true,
			'label'			=> __('Token','acf-tweet'),
			'type'			=> 'text',
			'name'			=> 'token'
		));

		acf_render_field_setting( $field, array(
			'required'  => true,
			'label'			=> __('Secret','acf-tweet'),
			'type'			=> 'text',
			'name'			=> 'secret'
		));

		acf_render_field_setting( $field, array(
			'required'  => true,
			'label'			=> __('Cache lifetime','acf-tweet'),
			'instructions'	=> __('Number of seconds before we try to fetch the tweet info again','acf-flickr'),
			'default_value'  => '900',
			'min'       => 0,
			'max'       => 1000000,
			'type'			=> 'number',
			'append'		=> 'sec.',
			'name'			=> 'cache_lifetime'
		));



	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {

		/*
		*  Create a simple text input
		*/

		?>

		<input type="text" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']['tweet_id']) ?>" />
		<?php

		if ( $tweet = $field['value']['tweet_data'] ) {
			$html = '';

			// Check Transient
			$html_transient = 'tweet-html-'.$tweet['id_str'];
			if ( false === ( $tweet_html = get_transient( $html_transient ) ) ) {

				require_once(dirname(__FILE__) . '/tmhOAuth.php');
				$tmhOAuth = new tmhOAuth(array(
	        'consumer_key'               => $field['consumer_key'],
	        'consumer_secret'            => $field['consumer_secret'],
	        'token'                      => $field['token'],
	        'secret'                     => $field['secret'],
				));

				$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/oembed'), array(
			    'id' => $tweet['id_str'],
					'omit_script' => true
			  ));

				if ( $code == 200 ) {
					$response = json_decode($tmhOAuth->response['response'], true);
					$html = $response['html'];

					// Save Transient
					set_transient( $html_transient, $response['html'], $response['cache_age'] );
				}

			} else {
				$html = $tweet_html;
			}

			echo $html;

			// echo '<pre>';
			// 	print_r( $tweet );
			// echo '</pre>';
		}



	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/



	function input_admin_enqueue_scripts() {

		$dir = plugin_dir_url( __FILE__ );


		// register & include JS
		wp_register_script( 'twitter-widget', "{$dir}js/twitter-widget.js" );
		wp_enqueue_script('twitter-widget');



	}




	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_head() {



	}

	*/


	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/

   	/*

   	function input_form_data( $args ) {



   	}

   	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/



	function load_value( $value, $post_id, $field ) {
		$value['tweet_data'] = json_decode(base64_decode($value['raw_json']), true);
		return $value;

	}




	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/



	function update_value( $value, $post_id, $field ) {

		$data = array(
			'tweet_id' => $value,
			'raw_json'=> ''
		);

		// Check Transient
		$transient_name = 'tweet-'.$value;
		if ( false === ( $tweet_raw_json = get_transient( $transient_name ) ) ) {

			// Fetch Tweet
			if ( $field['consumer_key'] && $field['consumer_secret'] && $field['token'] && $field['secret'] ) {

				require_once(dirname(__FILE__) . '/tmhOAuth.php');
				$tmhOAuth = new tmhOAuth(array(
	        'consumer_key'               => $field['consumer_key'],
	        'consumer_secret'            => $field['consumer_secret'],
	        'token'                      => $field['token'],
	        'secret'                     => $field['secret'],
				));

				$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/show'), array(
			    'id' => $value
			  ));

				if ( $code == 200 ) {
					$data['raw_json'] = base64_encode($tmhOAuth->response['response']);
				}

				// Save Transient
				set_transient( $transient_name, $data['raw_json'], $field['cache_lifetime'] );
			}

		} else {

			$data['raw_json'] = $tweet_raw_json;

		}

		return $data;
	}



	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	/*
	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) {

			return $value;

		}

		// return
		return $value;
	}

 	*/

	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}


		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-tweet'),
		}


		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function load_field( $field ) {

		return $field;

	}

	*/


	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function update_field( $field ) {

		return $field;

	}

	*/


	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/

	/*

	function delete_field( $field ) {



	}

	*/


}


// create field
new acf_field_tweet();

?>
