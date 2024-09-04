<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_roles".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $role_name
 * @property string|null $created_at
 *
 * @property Users $user
 */
class AdminRoles extends \yii\db\ActiveRecord
{
    public $permissions = [];
    public $roles = [];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['role_name'], 'required'],
            [['created_at'], 'safe'],
            [['role_name'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
            [['permissions','roles'], 'safe'],
        ];
    }


    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

}
