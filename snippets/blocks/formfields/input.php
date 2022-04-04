
<input
    type="<?= $formfield->inputtype() ?>"
    id="<?= $formfield->slug() ?>"
    name="<?= $formfield->slug() ?>"
    value="<?= $formfield->value() ?>"
    <?= $formfield->autofill(true) ?>
    <?= $formfield->required('attr') ?>
    <?= $formfield->ariaAttr() ?>
/>
