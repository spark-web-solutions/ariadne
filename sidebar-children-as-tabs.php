<?php
/**
 * Children as tabs sidebar
 */
global $post;

if (!isset($children)) { // In theory section that includes the sidebar should be getting list of children before including it, but just in case...
	$children = spark_get_children($post);
}

$has_children = true;
$menu_items = $children;
if (empty($menu_items) && $post->post_parent > 0) { // No children, get siblings
	$has_children = false;
	$menu_items = spark_get_children($post->post_parent);
}

$menu = '';
$is_active = false;
if(!empty($post->post_content)){
	$menu .= ' <li class="menu-item tabs-title is-active bg">'."\n";
	$menu .= '	  <a data-tabs-target="'.get_the_slug($post->ID).'" href="#'.get_the_slug($post->ID).'">'.$post->post_title.'</a><hr>'."\n";
	$menu .= ' </li>'."\n";
	$is_active = true;
}

foreach ($menu_items as $item) {
	$class = '';
	if ($is_active == false) {
		$class = 'is-active';
		$is_active = true;
	}
	$menu .= '		<li class="menu-item tabs-title '.$class.'">'."\n";
	if (!$has_children) { // If we're showing siblings (and not linking to the full page), link to the anchor on the parent page
		$menu .= '			<a href="'.get_the_permalink($post->post_parent).'#'.get_the_slug($item->ID).'">'.$item->post_title.'</a>'."\n";
	} else { // Otherwise just link to the anchor on the current page
		$menu .= '			<a data-tabs-target="'.get_the_slug($item->ID).'" href="#'.get_the_slug($item->ID).'">'.$item->post_title.'</a>'."\n";
		if (spark_has_children($item->ID)) { // If child page has children of its own, add links to them
			foreach (spark_get_children($item) as $grandchild) {
				$menu .= '		<hr></li>'."\n";
				$menu .= '		<li class="menu-item sub-menu-item tabs-title">'."\n";
				$menu .= '			<a data-tabs-target="'.get_the_slug($grandchild->ID).'" href="#'.get_the_slug($grandchild->ID).'">'.$grandchild->post_title.'</a>'."\n";
			}
		}
	}
	$menu .= '		<hr></li>'."\n";
}

if (!empty($menu)) {
?>
	<div class="hide-for-medium row" data-sticky-container>
		<div class="sticky small-24 column" data-sticky data-sticky-on="small" data-anchor="row-children-as-tabs">
			<ul class="menu tabs vertical" data-accordion-menu>
				<li class="selection">
					<a href="#">Quicklinks</a>
					<ul class="menu tabs vertical nested" id="children-as-tabs" data-tabs data-deep-link="true" data-deep-link-smudge="true" data-update-history="true">
						<?php echo $menu; ?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<ul class="show-for-medium menu tabs vertical" id="children-as-tabs" data-tabs data-deep-link="true" data-deep-link-smudge="true" data-update-history="true">
		<?php echo $menu; ?>
	</ul>
<?php
}
