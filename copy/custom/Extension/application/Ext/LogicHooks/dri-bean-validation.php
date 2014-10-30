<?php

/**
 * @author Emil Kilhage, DRI Nordic <emil.kilhage@dri-nordic.com>
 */
$hook_array['before_save'][] = array (
    1,
    'DRI\SugarCRM\Module\LogicHooks\Validation::validate',
    'data/SugarBean.php', // Just pick a random file, inclusion handled by the autoloader
    'DRI\SugarCRM\Module\LogicHooks\Validation',
    'validateUniqueIndices'
);
