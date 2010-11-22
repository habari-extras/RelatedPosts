<?php echo $rl_header; ?>
<ul class="related-post">
<?php
	$li_class= 'related-post-odd';
	foreach( $theme->related_entry as $post ) {
		$li_class= ( $li_class == 'related-post-even' ? 'related-post-odd' : 'related-post-even' );
		echo "<li class=\"{$li_class}\"><a href=\"{$post->permalink}\">{$post->title}<small> {$post->pubdate->get('l, F jS, Y')}</small></a></li>\n";
} 
?>
</ul>