<div class="text-center">
<div class="min-container">
<?php 
while ( have_posts() ) :
	the_post();
	echo '<article id="post-' . get_the_ID() . '" class="' . implode( ' ', get_post_class( '', get_the_ID() ) ) . '">';
	get_template_part( 'template-parts/content', get_post_type() );
	echo '<div class="sermon-info">';
	echo '<p class="date">'.get_the_date().'</p>';
	if(get_the_terms( get_the_ID(), 'series' )){
		echo '<p class="series"><span>Series: </span>'.get_the_terms( get_the_ID(), 'series' )[0]->name.'</p>';
	}
	if(get_the_terms( get_the_ID(), 'book' )){
		echo '<p class="passage"><span>Passage: </span>'.get_the_terms( get_the_ID(), 'book' )[0]->name;
		if(get_field('passage_start')){
			echo ' '.get_field('passage_start');
			if(get_field('passage_end')){echo ' - '.get_field('passage_end');}
			echo '</p>';
		}
	}
	if(get_the_terms( get_the_ID(), 'speaker' )){
		echo '<p class="speaker"><span>Speaker: </span>'.get_the_terms( get_the_ID(), 'speaker' )[0]->name;
		echo '</p>';
	}
	echo '</div>';
	echo '</article>';
endwhile;
 ?>
</div>
</div>