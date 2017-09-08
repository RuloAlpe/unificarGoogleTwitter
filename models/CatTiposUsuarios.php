<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_tipos_usuarios".
 *
 * @property string $id_tipo_usuario
 * @property string $txt_nombre
 * @property string $txt_descripcion
 * @property string $b_habilitado
 *
 * @property EntUsuarios[] $entUsuarios
 */
class CatTiposUsuarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_tipos_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nombre'], 'required'],
            [['b_habilitado'], 'integer'],
            [['txt_nombre'], 'string', 'max' => 50],
            [['txt_descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_usuario' => 'Id Tipo Usuario',
            'txt_nombre' => 'Txt Nombre',
            'txt_descripcion' => 'Txt Descripcion',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntUsuarios()
    {
        return $this->hasMany(EntUsuarios::className(), ['id_tipo_usuario' => 'id_tipo_usuario']);
    }
}
