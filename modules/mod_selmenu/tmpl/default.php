<?php

defined('_JEXEC') or die;


//echo "<pre>";
//print_r($listLinks);
//echo "</pre>";
//exit();

?>

<div id="module_<?= $module->id ?>" class='hidden_module_id' data-id='<?= $module->id ?>'></div>
<div id="list_<?= $module->id ?>" data-list='<?= json_encode($listLinks) ?>'></div>

<div id="<?php echo $module->name . '_' . $module->id; ?>">
    <?php
    //    $listLinks = json_encode($listLinks);
    //            echo "<pre>";
    //            print_r($listLinks);
    //            echo "</pre>";
    //            exit();
    ?>
    <?php foreach ($listLinks as $key => $item) : ?>
    <?php if ($key == 'level_1'): ?>
        <select id="<?php echo $module->name . '_' . $module->id.'_'.$key ?>" name="<?php echo $key ?>">
            <option>Выберите пункт меню</option>
        <?php else: ?>
            <select disabled="disabled" id="<?php echo $module->name . '_' . $module->id.'_'.$key ?>" name="<?php echo $key ?>">
                <option>Выберите пункт меню</option>
        <?php endif; ?>
            <?php foreach ($item as $i) : ?>
                <?php
//        echo "<pre>";
//        print_r($i);
//        echo "</pre>";
//        exit();
                ?>
                <?php if ($key == 'level_1'): ?>
                    <?php if ($i->published && $i->selected): ?>
                        <option data-menuid="<?php echo $i->menu_id ?>" data-type="<?php echo $i->type ?>"
                                value="<?php echo
                                $i->value ?>" link="<?php echo $i->link ?>">
                            <?php echo $i->text ?></option>
                    <?php endif; ?>
                <?php endif; ?>

            <?php endforeach; ?>
            ?>
        </select>
    <?php endforeach; ?>
</div>




