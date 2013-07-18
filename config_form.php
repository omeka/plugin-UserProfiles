
<?php $view = get_view();?>


<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Link items to owner?')?></label>    
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Add a link from items to the person who added them.')?></p>
        <div class="input-block">        
        <?php echo $view->formCheckbox('user_profiles_link_to_owner', null, array('checked' => (bool) get_option('user_profiles_link_to_owner') ? 'checked' : '')); ?>
        </div>
    </div>
</div>

<?php 
    $contributionFieldsTable = get_db()->getTable('ContributionContributorFields');
    //check the count to see if the table exists for real in the database
    
    if($contributionFieldsTable->count()) {
        set_option('user_profiles_contributors_imported', 0);
        $imported = (boolean) get_option('user_profiles_contributors_imported');
        if(!$imported &&  $contributionFieldsTable->count(array()) != 0 ) {
            $html = "<p>" . __("You have used the Contribution plugin to create Contributor information. For the Omeka 2.x series, that functionality has been folded into the User Profiles plugin.") . "</p>";
            $html .= "<p>" . __("Check this box if you would like to convert Contributor information over into a user profile. Contributors will also become Guest Users.") . "</p>";
            $html .= "<p>" . __("The import may take a long time if there are many contributions.") . "</p>";
            $html .= "<p>" . __("User Profiles offers many new features for your contributor info. After the import is complete, you might want to edit the 'Contributor Info' profile type that is created.") . "</p>";
            $html .= $view->formCheckbox('user_profiles_import_contributors');
            echo $html;
        }        
    }
?>