<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_tweets".
 *
 * @property string $id_tweet
 * @property string $id
 * @property string $txt_usuario
 * @property string $txt_tweet
 */
class EntTweets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_tweets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'txt_usuario', 'txt_tweet'], 'required'],
            [['id'], 'integer'],
            [['txt_tweet'], 'string'],
            [['txt_usuario'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tweet' => 'Id Tweet',
            'id' => 'ID',
            'txt_usuario' => 'Txt Usuario',
            'txt_tweet' => 'Txt Tweet',
        ];
    }
}
