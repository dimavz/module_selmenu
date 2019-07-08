<?php

defined('_JEXEC') or die;
?>

<div   id="<?php echo $module->name.'_'.$module->id ;?>">
<?php foreach ($listLinks as $key=>$item) : ?>
<select name="<?php echo $key ?>">
    <option value="select">Выберите меню</option>
    <?php foreach ($item as $i) : ?>
    <option value="<?php echo $i->link ?>"><?php echo $i->text ?></option>
    <?php endforeach; ?>
    ?>
</select>
<?php endforeach; ?>
</div>




