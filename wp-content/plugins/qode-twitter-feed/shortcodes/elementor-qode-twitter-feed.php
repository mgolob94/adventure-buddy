<?php

class QodeTwitterElementorShortcode extends \Elementor\Widget_Base{
    public function get_name() {
        return 'qode_twitter_feed';
    }

    public function get_title() {
        return esc_html__( "Qode Twitter Feed", 'qode-twitter-feed' );
    }

    public function get_icon() {
        return 'bridge-elementor-custom-icon bridge-elementor-twitter-feed';
    }

    public function get_categories() {
        return [ 'qode' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'general',
            [
                'label' => esc_html__( 'General', 'qode-twitter-feed' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'user_id',
            [
                'label' => esc_html__( "User ID", 'qode-twitter-feed' ),
                'type' => \Elementor\Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'count',
            [
                'label' => esc_html__( "Number of Tweets", 'qode-twitter-feed' ),
                'type' => \Elementor\Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'show_tweet_time',
            [
                'label' => esc_html__( "Show tweet time", 'qode-twitter-feed' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => bridge_qode_get_yes_no_select_array( false, true ),
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'transient_time',
            [
                'label' => esc_html__( "Tweets Cache Time", 'qode-twitter-feed' ),
                'type' => \Elementor\Controls_Manager::TEXT
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'design_options',
            [
                'label' => esc_html__( 'Design Options', 'qode-twitter-feed' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'author_name_color',
            [
                'label' => esc_html__( "Author Name Color", 'qode-twitter-feed' ),
                'type' => \Elementor\Controls_Manager::COLOR
            ]
        );

        $this->add_control(
            'sc_date_color',
            [
                'label' => esc_html__( "Screen Name and Date Color", 'qode-twitter-feed' ),
                'type' => \Elementor\Controls_Manager::COLOR
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( "Text Color", 'qode-twitter-feed' ),
                'type' => \Elementor\Controls_Manager::COLOR
            ]
        );

        $this->end_controls_section();
    }

    protected function render(){
        $params = $this->get_settings_for_display();

        $html = '';

        $transient_time = !empty( $params['transient_time'] ) ? $params['transient_time'] : 0;
        $user_id = !empty( $params['user_id'] ) ? $params['user_id'] : '';
        $count = !empty( $params['count'] ) ? $params['count'] : '';

        $twitter_api = QodeTwitterApi::getInstance();
        $name_style = $this->getTwitterNameStyle($params);
        $info_style = $this->getTwitterInfoStyle($params);
        $text_style = $this->getTwitterTextStyle($params);
        if($twitter_api->hasUserConnected()) {
            $response = $twitter_api->fetchTweets($user_id, $count, array(
                'transient_time' => $transient_time,
                'transient_id' => 'qode_twitter_'.$user_id
            ));

            if($response->status) {
                if(is_array($response->data) && count($response->data)) {
                    $html = '<div class="qode-twitter-feed-shortcode">';
                    $html .= '<div class="qode-tfs-inner clearfix">';
                    foreach($response->data as $tweet) {
                        $html .= '<div class="qode-tfs-item">';
                        $html .= '<div class="qode-tfs-item-inner">';
                        $html .= '<div class="qode-tfs-image-info-holder clearfix">';
                        $html .= '<div class="qode-tfs-image">';
                        $html .= '<img src="'. esc_url($twitter_api->getHelper()->getBiggerProfileImageURL($tweet)) .'" alt="" />';
                        $html .= '</div>';
                        $html .= '<div class="qode-tfs-info-holder">';
                        $html .= '<h5 class="qode-tfs-author-name" '. qode_twitter_get_inline_style($name_style) .'>';
                        $html .=  wp_kses_post($twitter_api->getHelper()->getTweetAuthorName($tweet));
                        $html .= '</h5>';
                        $html .= '<div class="qode-tfs-info" '. qode_twitter_get_inline_style($info_style) .'>';
                        $html .= '<span class="qode-tfs-author-screen-name">';
                        $html .= wp_kses_post($twitter_api->getHelper()->getTweetAuthorScreenName($tweet));
                        $html .= '</span>';
                        if($params['show_tweet_time'] == 'yes') {
                            $html .= '<span class="qode-tfs-time">';
                            $html .= wp_kses_post($twitter_api->getHelper()->getTweetCreatedTime($tweet));
                            $html .= '</span>';
                        }
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="qode-tfs-text"  '. qode_twitter_get_inline_style($text_style) .'>';
                        $html .= wp_kses_post($twitter_api->getHelper()->getTweetText($tweet));
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                }
            } else {
                $html .= esc_html($response->message);
            }
        } else {
            esc_html_e('It seems that you haven\'t connected with your Twitter account', 'qode-twitter-feed');
        }

        echo bridge_qode_get_module_part( $html );
    }

    private function getTwitterNameStyle($params){

        $style = array();

        if(!empty($params['author_name_color'])) {
            $style[] = 'color:'.$params['author_name_color'];
        }
        return implode(';', $style);
    }

    private function getTwitterInfoStyle($params){

        $style = array();

        if(!empty($params['sc_date_color'])) {
            $style[] = 'color:'.$params['sc_date_color'];
        }
        return implode(';', $style);
    }
    private function getTwitterTextStyle($params){

        $style = array();

        if(!empty($params['text_color'])) {
            $style[] = 'color:'.$params['text_color'];
        }
        return implode(';', $style);
    }

}

\Elementor\Plugin::instance()->widgets_manager->register( new QodeTwitterElementorShortcode() );