<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}

$comment_count = get_comments_number();
?>
<div id="comments">
<?php
if (have_comments()) {
?>
	<h2 class="comments-title">
<?php
		/* translators: %s: Comment count number. */
	printf(esc_html(_nx('%s comment', '%s comments', $comment_count, 'Comments title', SPARK_THEME_TEXTDOMAIN)), esc_html(number_format_i18n($comment_count)));
?>
	</h2>
	<ol class="comment-list">
<?php
	wp_list_comments(array(
			'avatar_size' => 60,
			'style' => 'ol',
			'short_ping' => true,
	));
?>
	</ol>
<?php
	echo spark_foundation_comments_pagination($comment_count);
	if (!comments_open()) {
?>
	<p class="no-comments"><?php esc_html_e('Comments are closed.', SPARK_THEME_TEXTDOMAIN); ?></p>
<?php
	}
}

comment_form(array(
		'logged_in_as' => null,
		'title_reply' => esc_html__('Leave a comment', SPARK_THEME_TEXTDOMAIN),
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after' => '</h2>'
));
?>
</div>
