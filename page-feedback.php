<?php 
/*
 Template Name: Feedback Page
*/

	//If the form is submitted
	if(isset($_POST['submitted'])) {
	//Check to make sure that the name field is not empty
		if(trim($_POST['subject']) === '') {
			$subjectError = 'Please attach a subject name.';
			$hasError = true;
		} else {
			$subject = trim($_POST['subject']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['feedback-text']) === '') {
			$textError = 'Come on. I am sure you can think of something to say!';
			$hasError = true;
		} else {
			$feedback = stripslashes(trim($_POST['feedback-text']));
		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {
			
			$option_name = 'ececlub_theme_options';
			$options = get_option( $option_name );
			$subject = 'Anonymous Feedback - ' . $subject;
			
			$emailTo = $options['ece_feedback_email'];
			$body = $feedback;
			
			wp_mail($emailTo, $subject, $body);
			$emailSent = true;
		}
	} 
?>
				
<?php get_header(); ?>
	<div class="page-container container-fluid">
		<div class="row-fluid ">
			<div class="col-md-7 col-md-offset-3">
				<?php while ( have_posts() ) : the_post(); ?>
					<section class="page">
						<div class="page-content">
							<h1><?php the_title(); ?></h1>
							<hr class="horizontal-rule">
							
							<?php if(isset($emailSent) && $emailSent == true) {?>
								<div class="thanks">
									Thank you for the submission. We will address the issue as soon as we can.
								</div>
							<?php } else { ?>
								<?php the_content(); ?>
								<form role="form" action="<?php the_permalink(); ?>" id="feedback-form" method="post">
									<div class="form-group <?php if($subjectError != '') { echo ' has-error';}?>">
										<label for="subject">Subject</label>
										<input type="text" name="subject" id="subject" value="<?php if(isset($_POST['subject'])) echo $_POST['subject'];?>" class="form-control"/>
										<?php if($subjectError != '') { ?>
											<span class="error"><?=$subjectError;?></span> 
										<?php } ?>
									</div>
									
									<div class="form-group <?php if($textError != '') { echo ' has-error';}?>">
										<label for="feedback-text">Suggestions/Feedback</label>
										<textarea name="feedback-text" id="feedback-text" class="form-control">
											<?php if(isset($_POST['feedback-text'])) {
												echo trim(stripslashes($_POST['feedback-text']));
					   						} ?>
					   					</textarea>
					   					<?php if($textError != '') { ?>
					   						<span class="error"><?=$textError;?></span> 
					   					<?php } ?>
				   					</div>
				   					<input type="hidden" name="submitted" id="submitted" value="true" />
					   				<button type="submit" class="btn btn-primary">Submit</button>
								</form>
							<?php } ?>
						</div>
					</section>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
