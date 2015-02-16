<?php
/**
 * Profile owner block
 */

$user = elgg_get_page_owner_entity();
$elgg = elgg_get_site_url();

$following = elgg_get_entities_from_relationship(array(
'relationship' => 'friend',
'relationship_guid' => elgg_get_page_owner_guid(),
'types' => 'user',
'count' => true
));

$followers = elgg_get_entities_from_relationship(array(
'relationship' => 'friend',
'relationship_guid' =>  elgg_get_page_owner_guid(),
'inverse_relationship' => TRUE,
'types' => 'user',
'count' => true
));

$poll = elgg_get_entities(array(
	'types' => 'object',
	'subtypes' => 'poll',
	'owner_guid' => elgg_get_page_owner_guid(),
	'count' => true
));

$questions = elgg_get_entities(array(
	'types' => 'object',
	'subtypes' => 'questions',
	'owner_guid' => elgg_get_page_owner_guid(),
	'count' => true
));

$hjalbumimage = elgg_get_entities(array(
	'types' => 'object',
	'subtypes' => 'hjalbumimage',
	'owner_guid' => elgg_get_page_owner_guid(),
	'count' => true
));

$hjwall = elgg_get_entities(array(
	'types' => 'object',
	'subtypes' => 'hjwall',
	'owner_guid' => elgg_get_page_owner_guid(),
	'count' => true
));

$blogs = elgg_get_entities(array(
	'types' => 'object',
	'subtypes' => 'blog',
	'owner_guid' => elgg_get_page_owner_guid(),
	'count' => true
));

if (!$user) {
	// no user so we quit view
	echo elgg_echo('viewfailure', array(__FILE__));
	return TRUE;
}

$icon = elgg_view_entity_icon($user, 'large', array(
	'use_hover' => false,
	'use_link' => false,
));

// grab the actions and admin menu items from user hover
$menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity' => $user), array());
$builder = new ElggMenuBuilder($menu);
$menu = $builder->getMenu();
$actions = elgg_extract('action', $menu, array());
$admin = elgg_extract('admin', $menu, array());

$profile_actions = '';
if (elgg_is_logged_in() && $actions) {
	$profile_actions = '<ul class="elgg-menu profile-action-menu mvm">';
	foreach ($actions as $action) {
		$profile_actions .= '<li>' . $action->getContent(array('class' => 'elgg-button elgg-button-action')) . '</li>';
	}
	$profile_actions .= '</ul>';
}

// if admin, display admin links
$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
	$text = elgg_echo('admin:options');

	$admin_links = '<ul class="profile-admin-menu-wrapper">';
	$admin_links .= "<li><a rel=\"toggle\" href=\"#profile-menu-admin\">$text&hellip;</a>";
	$admin_links .= '<ul class="profile-admin-menu" id="profile-menu-admin">';
	foreach ($admin as $menu_item) {
		$admin_links .= elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
	}
	$admin_links .= '</ul>';
	$admin_links .= '</li>';
	$admin_links .= '</ul>';	
}

// content links
$content_menu = elgg_view_menu('owner_block', array(
	'entity' => elgg_get_page_owner_entity(),
	'class' => 'profile-content-menu',
));

$hello = hello;

echo <<<HTML

<div id="profile-owner-block">
	$icon
		
	<ul class="sidebar-info">
		<li class="sidebar-following">
			<a href="{$elgg}friends/{$user->username}">
				
				<b>$following</b><br /> Following
				
			</a>
		</li>
		<li class="sidebar-followers">
			<a href="{$elgg}friendsof/{$user->username}">
			<b>$followers</b><br />	Followers
			</a>
		</li>

		<li class="sidebar-blogs">
			<a href="{$elgg}blog/owner/{$user->username}">
			<b>$blogs</b><br />	Blogs
			</a>
		</li>

		
	</ul>	

	$profile_actions

	<div class="profile-links">
		$content_menu
		$admin_links
	</div>

</div>

HTML;