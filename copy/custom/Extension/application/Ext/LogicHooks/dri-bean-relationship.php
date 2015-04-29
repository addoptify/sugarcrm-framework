<?php

/**
 * @author Emil Kilhage, DRI Nordic <emil.kilhage@dri-nordic.com>
 */
$hook_array['before_delete'][] = array(
    1,
    'DRI\SugarCRM\Module\LogicHooks\Relationship::cascadeMarkDeletedAfter',
    'data/SugarBean.php', // Just pick a random file, inclusion handled by the autoloader
    'DRI\SugarCRM\Module\LogicHooks\Relationship',
    'cascadeMarkDeletedBefore',
);

$hook_array['after_delete'][] = array(
    1,
    'DRI\SugarCRM\Module\LogicHooks\Relationship::cascadeMarkDeletedAfter',
    'data/SugarBean.php', // Just pick a random file, inclusion handled by the autoloader
    'DRI\SugarCRM\Module\LogicHooks\Relationship',
    'cascadeMarkDeletedAfter',
);

$hook_array['before_save'][] = array(
    1,
    'DRI\SugarCRM\Module\LogicHooks\Relationship::saveFetchedRow',
    'data/SugarBean.php', // Just pick a random file, inclusion handled by the autoloader
    'DRI\SugarCRM\Module\LogicHooks\Relationship',
    'saveFetchedRow',
);

$hook_array['after_save'][] = array(
    1,
    'DRI\SugarCRM\Module\LogicHooks\Relationship::cascadeUpdateAfter',
    'data/SugarBean.php', // Just pick a random file, inclusion handled by the autoloader
    'DRI\SugarCRM\Module\LogicHooks\Relationship',
    'cascadeUpdateAfter',
);
