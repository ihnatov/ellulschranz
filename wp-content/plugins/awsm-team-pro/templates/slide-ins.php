<div id="<?php echo $this->add_id(array('awsm-team',$id));?>" class="awsm-grid-wrapper">
	<div class="awsm-modal">
	<?php if ($team->have_posts()): ?>
		<div class="awsm-grid-modal awsm-grid <?php echo $this->item_style($options);?>">
		<?php while ($team->have_posts()): $team->the_post();
    		$teamdata = $this->get_options('awsm_team_member', $team->post->ID);?>
                <div id="<?php echo $this->add_id(array('awsm-member',$id,$team->post->ID));?>" class="awsm-grid-card">
                    <a href="#" class="awsm-modal-trigger" data-trigger="#<?php echo $this->add_id(array('slide-ins',$id,$team->post->ID));?>">
                        <figure>
                                <img src="<?php echo $this->team_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
                                <figcaption>
                                    <div class="awsm-personal-info">
                                       	<?php $this->checkprint('<span>%s</span>', $teamdata['awsm-team-designation']);?>
                                        <h3><?php the_title(); ?></h3>
                                    </div>
                                </figcaption>
                        </figure>
                    </a>
                </div>
			<?php endwhile; wp_reset_postdata();?>
		</div>
		<div class="awsm-modal-items <?php echo $this->item_style($options);?>">
		    <div class="awsm-modal-items-main">
		    		<div class="awsm-modal-header clearfix">
					    <a href="#" class="awsm-modal-close"></a>
					    <span class="awsm-modal-nav">
					    <a href="#" class="awsm-nav-item awsm-nav-left"><i class="awsm-icon-arrow-left"></i></a>
					    <a href="#" class="awsm-nav-item awsm-nav-right"><i class="awsm-icon-arrow-right"></i></a>
					    </span>
					</div>
		    		<?php 
		    		while ($team->have_posts()): $team->the_post(); 
		    			$teamdata = $this->get_options('awsm_team_member', $team->post->ID);
		    			include( $this->settings['plugin_path'].'templates/partials/popup-slide.php' );
		    		endwhile;
		    		?>	
		    </div>
		</div>
	<?php endif;?>	
	</div>
</div>