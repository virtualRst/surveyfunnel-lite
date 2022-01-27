<?php
/**
 * The file that defines rest api functionality
 *
 * A class definition that includes attributes and functions used for rest api functionality.
 *
 * @link       https://club.wpeka.com
 * @since      1.0.0
 *
 * @package    Surveyfunnel_Lite
 * @subpackage Surveyfunnel_Lite/includes
 */

/**
 * The api plugin class.
 *
 * This is used to define hooks for rest api.
 *
 * @since      1.0.0
 * @package    Surveyfunnel_Lite
 * @subpackage Surveyfunnel_Lite/includes
 * @author     WPEka Club <support@wpeka.com>
 */
class Surveyfunnel_Lite_Rest_Api
{

	/**
	 * Registers rest api routes.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function init()
	{

		if (!function_exists('register_rest_route')) {
			return false;
		}
		register_rest_route(
			'surveyfunnel/v2',
			'fsd',
			array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array('Surveyfunnel_Lite_Rest_Api', 'fetch_survey_details'),
		)
		);
		register_rest_route(
			'surveyfunnel/v2',
			'responses',
			array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array('Surveyfunnel_Lite_Rest_Api', 'fetch_responses_details'),
		)
		);
		register_rest_route(
			'surveyfunnel/v2',
			'responses/(?P<slug>[0-9-]+)',
			array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array('Surveyfunnel_Lite_Rest_Api', 'fetch_responses_with_surveyid'),
		)
		);
		register_rest_route(
			'surveyfunnel/v2',
			'surveys',
			array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array('Surveyfunnel_Lite_Rest_Api', 'fetch_servey_responses'),
		)
		);
		register_rest_route(
			'surveyfunnel/v2',
			'surveys/survey_id=(?P<slug>[0-9-]+)',
			array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array('Surveyfunnel_Lite_Rest_Api', 'fetch_responses_with_surveyid_in_reverse'),
		)
		);
	}

	/**
	 * Rest api callback function which returns survey details.
	 */
	public static function fetch_survey_details()
	{

		$args = [
			'post_type' => 'wpsf-survey',
			'posts_per_page' => -1
		];
		$ans = array();
		$posts = get_posts($args);
		$arr = array();
		foreach ($posts as $post) {
			$a = self::json_change_key($post, 'ID', 'id');
			array_push($arr, $a);
		}
		return $arr;
	}
	public static function fetch_responses_details()
	{
		global $wpdb;
		$table_name=$wpdb->prefix.'srf_entries';
		$query = "SELECT * FROM $table_name";
		$data = $wpdb->get_results($query);
		$arr = array();
		foreach ($data as $d) {
			$a = self::json_change_key($d, 'ID', 'id');
			$a->fields = json_decode($a->fields);
			array_push($arr, $a);
		}
		return $arr;
	}
	public static function fetch_responses_with_surveyid($slug)
	{
		$responses = self::fetch_responses_details();
		$ans = array();
		foreach ($responses as $response) {
			if ($response->survey_id == $slug['slug']) {
				array_push($ans, $response);
			}
		}
		return $ans;
	}
	/**
	 * Fetch surveys responses with most recent response on single array
	 */
	public static function fetch_servey_responses()
	{
		$surveys = self::fetch_survey_details();
		$final_arr = array();
		foreach ($surveys as $survey) {
			$id = ['slug' => $survey->id];
			$response = self::fetch_responses_with_surveyid($id);
			$recent = end($response);
			$innerFields = $recent->fields;
			$a = [
				'id' => $recent->id,
				'post_title' => $survey->post_title,
				'post_name' => $survey->post_name,
				'post_date' => $survey->post_date,
				'comment_status' => $survey->comment_status,
				'comment_count' => $survey->comment_count,
				'survey_id' => $recent->survey_id,
				'user_id' => $recent->user_id,
			];
			$i = 1;
			foreach ($innerFields as $key => $value) {
				$a['question' . $i] = $value->question;
				$a['status' . $i] = $value->status;
				$a['componentName' . $i] = $value->componentName;
				if ($value->componentName == 'FormElements') {
					$answer = $value->answer;
					if ($answer[0]->name != "" && $answer[0]->value != "") {
						$string = lcfirst(preg_replace('/\s+/', '', $answer[0]->name)) . $i;
						$a[$string] = $answer[0]->value;
					}
					if ($answer[1]->name != "" && $answer[1]->value != "") {
						$string = lcfirst(preg_replace('/\s+/', '', $answer[1]->name)) . $i;
						$a[$string] = $answer[1]->value;
					}
					if ($answer[2]->name != "" && $answer[2]->value != "") {
						$string = lcfirst(preg_replace('/\s+/', '', $answer[2]->name)) . $i;
						$a[$string] = $answer[2]->value;
					}
				}
				elseif ($value->componentName == 'MultiChoice' && !empty($value->answer)) {
					$answer = $value->answer;
					$string = "";
					foreach ($answer as $ans) {
						$string = $string . $ans->name . ',';
					}
					$a['answer' . $i] = $string;
				}
				else {
					if ($value->answer != "") {
						$answerString = 'answer' . $i;
						$a[$answerString] = $value->answer;
					}
				}
				$i++;

			}
			array_push($final_arr, $a);
		}
		return $final_arr;
	}

	public static function json_change_key($arr, $oldkey, $newkey)
	{
		$json = str_replace('"' . $oldkey . '":', '"' . $newkey . '":', json_encode($arr));
		return json_decode($json);
	}
	public static function fetch_responses_with_surveyid_in_reverse($slug)
	{
		$responses = self::fetch_responses_details();
		$ans = array();
		foreach ($responses as $response) {
			if ($response->survey_id == $slug['slug']) {
				array_push($ans, $response);
			}
		}
		foreach ($ans as $an) {
			$i = 1;
			$innerFields = $an->fields;
			foreach ($innerFields as $key => $value) {
				$question = 'question' . $i;
				$status = 'status' . $i;
				$componentName = 'componentName' . $i;
				$an->$question = $value->question;
				$an->$status = $value->status;
				$an->$componentName = $value->componentName;
				if ($value->status == "viewed") {
					break;
				}
				if ($value->componentName == 'FormElements') {
					$answer = $value->answer;
					if ($answer[0]->name != "" && $answer[0]->value != "") {
						$string = lcfirst(preg_replace('/\s+/', '', $answer[0]->name)) . $i;
						$an->$string = $answer[0]->value;
					}
					if ($answer[1]->name != "" && $answer[1]->value != "") {
						$string = lcfirst(preg_replace('/\s+/', '', $answer[1]->name)) . $i;
						$an->$string = $answer[1]->value;
					}
					if ($answer[2]->name != "" && $answer[2]->value != "") {
						$string = lcfirst(preg_replace('/\s+/', '', $answer[2]->name)) . $i;
						$an->$string = $answer[2]->value;
					}
				}
				elseif ($value->componentName == 'MultiChoice' && !empty($value->answer)) {
					$answer = $value->answer;
					$string = "";
					foreach ($answer as $ansNew) {
						$string = $string . $ansNew->name . ',';
					}
					$answerString = 'answer' . $i;
					$an->$answerString = $string;
				}
				else {
					if ($value->answer != "") {
						$answerString = 'answer' . $i;
						$an->$answerString = $value->answer;
					}
				}
				$i++;
			}
			unset($an->fields);
		}
		return array_reverse($ans);
	}
}
