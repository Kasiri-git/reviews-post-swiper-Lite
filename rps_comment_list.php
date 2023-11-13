<?php
/**
 * 管理画面でコメント一覧を表示するページ
 */

function rps_display_comments_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rpswiper_reviews';
    $comments = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    ?>
    <div class="wrap">
        <h1>コメント一覧</h1>
        <?php if ($comments): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>タイトル</th>
                        <th>内容</th>
                        <th>評価</th>
                        <th>投稿日</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?php echo esc_html($comment->title); ?></td>
                            <td><?php echo esc_html($comment->content); ?></td>
                            <td><?php echo esc_html($comment->rating); ?></td>
                            <td><?php echo esc_html($comment->created_at); ?></td>
                            <td>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=rps_delete_comment&comment_id=' . $comment->review_id), 'rps_delete_comment_' . $comment->review_id)); ?>" onclick="return confirm('本当に削除しますか？');">削除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>コメントはまだありません。</p>
        <?php endif; ?>
    </div>
    <?php
}

add_action('admin_post_rps_delete_comment', 'rps_delete_comment_handler');

function rps_delete_comment_handler() {
    global $wpdb;

    // 権限チェック
    if (!current_user_can('manage_options')) {
        wp_die('権限がありません');
    }

    $comment_id = isset($_GET['comment_id']) ? intval($_GET['comment_id']) : 0;
    check_admin_referer('rps_delete_comment_' . $comment_id);

    if ($comment_id) {
        $table_name = $wpdb->prefix . 'rpswiper_reviews';
        $wpdb->delete($table_name, ['review_id' => $comment_id]);

        // 削除後にリダイレクト
        wp_redirect(admin_url('admin.php?page=rps-comments-list'));
        exit;
    }
}
