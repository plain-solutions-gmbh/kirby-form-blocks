
<input
    type="<?= $formfield->inputtype() ?>"
    id="<?= $formfield->slug() ?>"
    name="<?= $formfield->slug() ?>"
    value="<?= $formfield->value() ?>"
    <?= e($formfield->required()->isTrue(), "required") ?>
    <?= e($formfield->isInvalid(), "invalid") ?>
    <?= e($formfield->isInvalid(), "aria-describedby='" . $formfield->slug() . "-error-message'") ?>
    />
