<!-- START OF FOOTER -->
    <?php if ($hasfooter) { ?>
    <footer role="contentinfo" id="page-footer">
	<nav role="navigation">
	 <p>Designed and built with all the love in the world la la la</p>
	 <?php
        echo $OUTPUT->login_info();
        echo page_doc_link(get_string('moodledocslink'));
        echo $OUTPUT->home_link();
        echo $OUTPUT->standard_footer_html();
      ?>
      <a href="#">Back to top</a>
	</nav>	
	
	</footer>	
	<?php } ?>			

</div><!-- close container -->	

<?php echo $OUTPUT->standard_end_of_body_html(); ?>
</body>
</html>
