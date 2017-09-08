<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_usuarios".
 *
 * @property string $id_usuario
 * @property string $id_tipo_usuario
 * @property string $txt_nombre
 *
 * @property CatTiposUsuarios $idTipoUsuario
 */
class EntUsuarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_usuario', 'txt_nombre'], 'required'],
            [['id_tipo_usuario'], 'integer'],
            [['txt_nombre'], 'string', 'max' => 50],
            [['id_tipo_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposUsuarios::className(), 'targetAttribute' => ['id_tipo_usuario' => 'id_tipo_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'id_tipo_usuario' => 'Id Tipo Usuario',
            'txt_nombre' => 'Txt Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoUsuario()
    {
        return $this->hasOne(CatTiposUsuarios::className(), ['id_tipo_usuario' => 'id_tipo_usuario']);
    }
}
