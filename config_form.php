
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
    $db = get_db();
    //the Table object might or might not reflect it actually existing,
    //so also check it agains the adapter's list of tables
    $contributionFieldsTable = $db->getTable('ContributionContributorFields');
    $tableName = $contributionFieldsTable->getTableName();
    $tables = $db->getAdapter()->listTables();
    //set_option('user_profiles_contributors_imported', 0);
    if(in_array($tableName, $tables) && get_option('user_profiles_contributors_imported') == 0): ?>
    <div class="field">
        <div class="two columns alpha">
            <label><?php echo __('Import Contribution fields as a user profile type?')?></label>    
        </div>
        <div class="inputs five columns omega">
            <div class="input-block">        
            <?php echo $view->formCheckbox('user_profiles_import_contributors'); ?>
            <p>
            <?php echo __("You have used the Contribution plugin to create Contributor information. " . 
                    "For the Omeka 2.x series, that functionality has been folded into the User Profiles plugin. " .
                    "Check this box if you would like to convert Contributor information over into a user profile. " . 
                    "User Profiles offers many new features for your contributor info. After the import is complete, " .
                    "you might want to edit the 'Contributor Info' profile type that is created."
                    ) ; ?>
            </p>
            </div>
        </div>
    </div>    
    
    <?php endif; ?>

