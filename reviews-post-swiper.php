<?php
/**
 * Plugin Name: ReviewsPostSwiper
 * Plugin URI: プラグインURL
 * Description: 口コミを投稿し、スライド形式で表示するプラグイン。
 * Version: 1.0.3
 * Author: Kasiri
 * Author URI: https://kasiri.icu
 * License: GPL2
 * Text Domain: reviews-post-swiper
 */

// ファイルの読み込み
include_once 'rps_function.php';
include_once 'rps_admin_core.php';
include_once 'rps_frontform.php';
include_once 'rps_comment_list.php';

// 管理画面メニューの追加
function rps_add_admin_menu() {
    add_menu_page(
        'ReviewsPostSwiper 設定', 
        'RP-Swiper', 
        'manage_options', 
        'reviews-post-swiper', 
        'rps_admin_page', 
        'dashicons-testimonial'

    );

    // コメント一覧のサブメニューを追加
    add_submenu_page(
        'reviews-post-swiper', // 親メニューのスラッグ（ここが重要）
        'コメント管理', // ページのタイトル
        'コメント管理', // サブメニューのタイトル
        'manage_options', // 必要な権限
        'rps-comments-list', // このサブメニューのスラッグ
        'rps_display_comments_list' // 表示するための関数
    );
}

add_action('admin_menu', 'rps_add_admin_menu');

// ショートコードの登録
function rps_shortcode($atts) {
    return rps_display_reviews($atts);
}

add_shortcode('reviews_post_swiper', 'rps_shortcode');

// プラグイン有効化時の処理
function rps_activate_plugin() {
    rps_create_tables();
}

register_activation_hook(__FILE__, 'rps_activate_plugin');
