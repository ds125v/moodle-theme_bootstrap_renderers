    <?php if ($hasfooter) { ?>
    <footer role="contentinfo">
	<nav role="navigation">
    <p><?php echo $OUTPUT->login_info()?></p>
    <p><?php echo page_doc_link(get_string('moodledocslink'))?></p>
	 <?php
        echo $OUTPUT->home_link();
        echo $OUTPUT->standard_footer_html();
      ?>
      <p><a href=# class=pull-right>Back to top</a></p>
	</nav>	
	</footer>	
	<?php } ?>			
</div>
<?php echo $OUTPUT->standard_end_of_body_html(); ?>
</body>
</html>
