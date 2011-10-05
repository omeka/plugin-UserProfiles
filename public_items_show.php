<div id="user-profiles-user-link">
    <p>
    <?php   $user = get_current_item()->getUserWhoCreated();
            user_profiles_link_to_profile($user, 'About the person who added this item.');
    ?>
    </p>
</div>
