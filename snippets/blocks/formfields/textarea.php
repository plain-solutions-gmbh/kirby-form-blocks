<textarea
    id="<?= $formfield->slug() ?>"
    name="<?= $formfield->slug() ?>"
    rows="<?= $formfield->row() ?>"
    placeholder="<?= $formfield->placeholder() ?>"
    <?= $formfield->required('attr') ?>
    <?= $formfield->ariaAttr() ?>
    <?= $formfield->autofill(true) ?>
>
    <?= $formfield->value() ?>
</textarea>