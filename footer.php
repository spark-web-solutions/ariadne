				</main>
<?php
spark_show_panels('bottom');
?>
			</div><!-- end #content -->
<?php
get_template_part('templates/sections/footer');
get_template_part('templates/sections/copyright');
?>
		</div><!-- end .off-canvas-content -->
	</div><!-- end #everything -->
	<?php wp_footer(); ?>
	<script>
		jQuery(document).foundation();

		if (typeof ajaxurl === 'undefined') {
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		}
	</script>
</body>
</html>
