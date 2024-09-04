<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RoleForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */
/* @var $assignedPermissions array */

?>

<div class="admin-roles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'role_name')->dropDownList(
        ArrayHelper::map($roles, 'name', 'name'),
        ['prompt' => 'Select Role']
    ) ?>

    <h3><?= Yii::t('app', 'Assign Permissions') ?></h3>
    <?= $form->field($model, 'permissions')->checkboxList(ArrayHelper::map($permissions, 'name', 'description'), [
        'item' => function ($index, $label, $name, $checked, $value) use ($assignedPermissions) {
            $checked = in_array($value, $assignedPermissions) ? 'checked' : '';
            return "<label><input type='checkbox' {$checked} name='{$name}' value='{$value}'> {$label}</label>";
        }
    ])->label(false) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$permissionsUrl = \yii\helpers\Url::to(['get-role-permissions']); // URL to get role permissions
?>

<script>
    $(document).ready(function() {
        $('#adminroles-role_name').change(function() {
            var roleName = $(this).val(); // Select qilingan rolning nomini oling

            // Rol o'zgartirilganda barcha checkboxlarni unchecked qilamiz
            $('input[name="AdminRoles[permissions][]"]').prop('checked', false);

            $.ajax({
                url: '<?= $permissionsUrl ?>',
                type: 'GET',
                data: {role_name: roleName},
                success: function(data) {
                    // Checkbox listni yangilang
                    $.each(data.permissions, function(index, permissionName) {
                        $('input[name="AdminRoles[permissions][]"][value="' + permissionName + '"]').prop('checked', true);
                    });

                    // Role dropdown ni o'zgartirilgan permissionlarga mos ravishda yangilang
                    $('#adminroles-role_name').val(data.role_name); // Ro'lni yangilang
                }
            });
        });

        // Permissionlar o'zgartirilganda role ni yangilang
        $('input[name="AdminRoles[permissions][]"]').change(function() {
            var selectedPermissions = [];
            $('input[name="AdminRoles[permissions][]"]:checked').each(function() {
                selectedPermissions.push($(this).val());
            });

            $.ajax({
                url: '<?= $permissionsUrl ?>',
                type: 'GET',
                data: {permissions: selectedPermissions},
                success: function(data) {
                    if (data.role_name) {
                        $('#adminroles-role_name').val(data.role_name);
                    }
                }
            });
        });
    });
</script>

