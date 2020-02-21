<?php
spark_show_panels('bottom');
Spark_Theme::section('name=footer&file=footer.php&class=hide-for-print full&type=footer');
Spark_Theme::section('name=copyright&file=copyright.php&class=hide-for-print full&type=footer');
?>
                </section>
            </div><!-- end off-canvas-content -->
        </div><!-- end everything -->
        <?php wp_footer(); ?>
        <script>
            var zurb = jQuery.noConflict();
            zurb(document).foundation();

            if (typeof ajaxurl === 'undefined') {
                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            }
        </script>
    </body>
</html>
