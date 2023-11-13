<?php
/**
 * ReviewsPostSwiperの関数とデータベースの処理。
 */

// データベースに接続し、テーブルを作成する
function rps_create_tables() {
    global $wpdb;

    $table_name_reviews = $wpdb->prefix . 'rpswiper_reviews';
    $table_name_forms = $wpdb->prefix . 'rpswiper_forms';

    $sql_reviews = "CREATE TABLE $table_name_reviews (
        review_id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        title VARCHAR(255),
        content TEXT,
        rating INT,
        form_id INT, // 新しく追加されたカラム
        created_at TIMESTAMP
    );";

    $sql_forms = "CREATE TABLE $table_name_forms (
        form_id INT PRIMARY KEY AUTO_INCREMENT,
        form_name VARCHAR(255),
        form_shortcode VARCHAR(255),
        created_at TIMESTAMP
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_reviews);
    dbDelta($sql_forms);
}

// ここに他の関数を追加...

