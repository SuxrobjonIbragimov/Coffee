<?php

namespace app\models;

use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions = [];

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255],
            [['permissions'], 'safe'],
        ];
    }
}