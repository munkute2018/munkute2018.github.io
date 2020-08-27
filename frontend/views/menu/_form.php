<?php
$linkInputOpts = []; 
$linkInputOpts['maxlength'] = 60;
if ($node->isReadonly()) {
    $linkInputOpts['readonly'] = true;
}
if ($node->isDisabled()) {
    $linkInputOpts['disabled'] = true;
}
?>
<div class="row">
    <div class="col-sm-12">
<?php
		echo $form->field($node, 'link')->textInput($linkInputOpts);
?>
	</div>
</div>