<?php
/**
 * ReviewsPostSwiperの管理画面。
 */

// 管理画面の表示
function rps_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rpswiper_forms';

    // フォームの削除処理
    if (isset($_POST['delete']) && isset($_POST['form_id'])) {
        $form_id = intval($_POST['form_id']);
        $wpdb->delete($table_name, ['form_id' => $form_id]);
    }

    // フォームの登録処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_name'])) {
        $form_name = sanitize_text_field($_POST['form_name']);
        $wpdb->insert($table_name, ['form_name' => $form_name, 'created_at' => current_time('mysql', 1)]);
        $form_id = $wpdb->insert_id;
        $form_shortcode = 'rps_review_form id="' . $form_id . '"';
        $wpdb->update($table_name, ['form_shortcode' => $form_shortcode], ['form_id' => $form_id]);
    }

    $forms = rps_get_registered_forms();

    ?>
    <div class="wrap">
        <style>
            .rps-table {
                border-collapse: collapse;
                width: 100%;
            }
            .rps-table th, .rps-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            .rps-table th {
                background-color: #f2f2f2;
            }
        </style>
        <h1>ReviewsPostSwiper 設定</h1>
        <form method="post" action="">
            <label for="form_name">フォーム名:</label>
            <input type="text" name="form_name" id="form_name" required>
            <input type="submit" value="フォーム作成">
        </form>

        <h2>登録されているフォーム</h2>
        <table class="rps-table">
            <thead>
                <tr>
                    <th>フォーム名</th>
                    <th>ショートコード</th>
                    <th>作成日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forms as $form): ?>
                    <tr>
                        <td><?php echo esc_html($form->form_name); ?></td>
                        <td>[rps_review_form id="<?php echo esc_attr($form->form_id); ?>"]</td>
                        <td><?php echo esc_html($form->created_at); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="form_id" value="<?php echo $form->form_id; ?>">
                                <input type="submit" name="delete" value="削除" onclick="return confirm('本当に削除しますか？');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function rps_get_registered_forms() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rpswiper_forms';
    return $wpdb->get_results("SELECT * FROM $table_name");
}
