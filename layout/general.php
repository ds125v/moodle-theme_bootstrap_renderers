<?php include('header.php') ;?>
<!-- GENERAL -->
<div id="region-main-box" class="row-fluid">

<?php if ($hassidepre) : ?>
	<aside class="span3">
	<?php echo $OUTPUT->blocks_for_region('side-pre') ?>
	</aside>
<?php endif; ?>

<?php if ($hassidepre AND $hassidepost) : ?>
	<article class="span6">
<?php else : ?>
	<article class="span9">
<?php endif; ?>
	<?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
	</article>
                
                
<?php if ($hassidepost) : ?>                
	<aside class="span3">
	<?php echo $OUTPUT->blocks_for_region('side-post') ?>
    </aside>
<?php endif; ?>          
</div>
<!-- END GENERAL -->  
<?php include('footer.php') ;?>
