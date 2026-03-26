<?php
/**
 * Plugin Name: Fuel WP
 * Plugin URI: 
 * Description: Fuel for Wordpress
 * Version: 1.0
 * Author: Mike Veilleux
 * Author URI: https://fuel.york.ie
 */

function fuel_wp_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'fuel-style', $plugin_url . 'css/news.css' );
}

add_action( 'wp_enqueue_scripts', 'fuel_wp_load_plugin_css' );

add_shortcode('fuel_news', 'fuel_news_print');

function fuel_news_print($attr)
{
	// input parameters can be passed: [fuel_news input_1='Test Input']
	// accessed safely like: $input_1 = isset($attr['input_1']) ? $attr['input_1'] : "empty";

	// Get Articles to be displayed
	$articles = get_news();

	$shortcode  = "<div class=\"fuel-news-featured\">";

	$shortcode .= "<div>";
	$shortcode = display_featured($articles[0], $shortcode);
	$shortcode .= "</div>";

	$shortcode .= "<div>";
	$shortcode = display_articles(array_slice($articles, 1), $shortcode);
	$shortcode .= "</div>";

	$shortcode .= "</div>";

	return $shortcode;
}

function get_news() {
	// This is where you run the code and display the output
	$curl = curl_init();
	$url = "https://xkkkr0w836.execute-api.us-east-2.amazonaws.com/prod/news/curated/featured?_limit=5";

	curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(),
		)
	);

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {
		//Only show errors while testing
		//echo "cURL Error #:" . $err;
	} else {
		//The API returns data in JSON format, so first convert that to an array of data objects
		$responseObj = json_decode($response);
		return $responseObj->items;
	}
}

function display_featured($article, $html) {
	$article_url = "https://fuel.york.ie/news/{$article->curated_article->mpn_category}/{$article->curated_article->id}";
	$org_url = "https://fuel.york.ie/research/companies/{$article->organization->id}";

	// Title
	$html .= "<h3><a href=\"{$article_url}\" target=\"_blank\">{$article->curated_article->title}</a></h3>";

	// Org
	$html .= "<a href=\"{$org_url}\" target=\"_blank\">";
	$html .= "<img src=\"{$article->organization->logo_url}\" class=\"fuel-article-org-logo\">";
	$html .= "<span class=\"fuel-article-org-name\">{$article->organization->name}</span>";
	$html .= "</a>";
	
	// Article
	$html .= "<img src=\"{$article->curated_article->image}\" class=\"fuel-article-image\"/>";
	$html .= "<p class=\"fuel-article-description\">{$article->curated_article->description}</p>";

	// Details
	$html .= "<span class=\"fuel-article-details\">";
	$html .= date("M j", strtotime($article->curated_article->content_date));
	$html .= " · ";
	$html .= calculate_read_time($article->article->word_count)." MIN";
	$html .= "</span>";

	return $html;
}

function display_articles($articles, $html) {

	foreach ($articles as $article) {
		$article_url = "https://fuel.york.ie/news/{$article->curated_article->mpn_category}/{$article->curated_article->id}";
		$org_url = "https://fuel.york.ie/research/companies/{$article->organization->id}";

		// Title & Description
		$html .= "<h4><a href=\"{$article_url}\" target=\"_blank\">{$article->curated_article->title}</a></h4>";
		$html .= "<p class=\"fuel-article-description\">{$article->curated_article->description}</p>";

		// Details
		$html .= "<span class=\"fuel-article-details\">";
		$html .= date("M j", strtotime($article->curated_article->content_date));
		$html .= " · ";
		$html .= calculate_read_time($article->article->word_count)." MIN";
		$html .= " · ";
		$html .= "<a href=\"{$org_url}\" target=\"_blank\">{$article->organization->name}</a>";
		$html .= "</span>";
	}

	return $html;
}

function calculate_read_time($word_count) {
	return ceil($word_count / 200);
}