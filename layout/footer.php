<!-- START OF FOOTER -->
    <?php if ($hasfooter) { ?>
    <footer role="contentinfo" id="page-footer">
	<nav role="navigation">
	 <!-- <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p> -->
	 <p>Designed and built with all the love in the world la la la</p>
	 <?php
        echo $OUTPUT->login_info();
        // echo $OUTPUT->home_link();
        echo $OUTPUT->standard_footer_html();
      ?>
      <a href="#">Back to top</a>
	</nav>	
	
	</footer>	
	<?php } ?>			

</div><!-- close container -->	
</div><!-- close #page -->

<?php echo $OUTPUT->standard_top_of_body_html(); ?> 
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
