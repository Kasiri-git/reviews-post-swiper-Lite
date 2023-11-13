<?php
/**
 * フロントエンド用の口コミ一覧と投稿フォーム表示
 */

function rps_show_review_form_by_shortcode($atts) {
    global $wpdb;
    $atts = shortcode_atts(['id' => ''], $atts, 'rps_review_form');
    $form_id = $atts['id'];

    ob_start(); // 出力バッファリングを開始

    // レビューを取得
    $table_name = $wpdb->prefix . 'rpswiper_reviews';
    $reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE form_id = %d ORDER BY created_at DESC", $form_id));

    // 取得したレビューを表示
    if ($reviews) {
echo '<div class="rps-reviews-container" style="border: 2px solid #ccc; padding: 15px; margin-bottom: 20px;">';
foreach ($reviews as $review) {
    // 星の表示を生成
    $rating = intval($review->rating);
    $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);

    echo '<div class="rps-review" style="border: 1px solid #ddd; margin-bottom: 10px; padding: 10px; background-color: #f9f9f9;">';
    echo '<h3 style="margin-top: 0;">' . esc_html($review->title) . '</h3>';
    // 星の表示（指定されたオレンジ色、黒い枠線、サイズを大きく）
    echo '<div style="color: #fcb900; font-size: 24px; text-shadow: 0 0 0px #000;">' . $stars . '</div>';
    echo '<p>' . esc_html($review->content) . '</p>';
    echo '</div>';
}
echo '</div>';
    }

    // フォームの送信を処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rps_submit_review'])) {
        rps_handle_review_submission($form_id);
    }

    // フォームの表示
    ?>
    <form method="post" action="">
        <label for="title">タイトル:</label>
        <input type="text" name="title" id="title" required>

        <label for="content">本文:</label>
        <textarea name="content" id="content" required></textarea>

        <label for="rating">評価:</label>
        <select name="rating" id="rating" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>

        <input type="hidden" name="form_id" value="<?php echo esc_attr($form_id); ?>">
        <input type="submit" name="rps_submit_review" value="口コミを投稿">
    </form>
    <?php
    return ob_get_clean(); // バッファの内容を取得し、バッファを消去
}

// フォームの送信データを処理
function rps_handle_review_submission($form_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rpswiper_reviews';

    // 入力値をサニタイズ
    $title = sanitize_text_field($_POST['title']);
    $content = sanitize_textarea_field($_POST['content']);
    $rating = intval($_POST['rating']);

    // データベースに登録
    $wpdb->insert(
        $table_name,
        [
            'user_id' => get_current_user_id(), // 現在のユーザーIDを使用
            'title' => $title,
            'content' => $content,
            'rating' => $rating,
            'form_id' => $form_id, // フォームIDを保存
            'created_at' => current_time('mysql', 1)
        ]
    );
}

// ショートコードを登録
add_shortcode('rps_review_form', 'rps_show_review_form_by_shortcode');
