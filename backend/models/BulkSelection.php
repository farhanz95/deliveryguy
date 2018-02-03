<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "locations".
 *
 * @property integer $location_id
 * @property string $zip_code
 * @property string $city
 * @property string $province
 */
class BulkSelection extends \yii\db\ActiveRecord
{
    public $selection;

    public function rules()
    {
        return [
            [['selection'], 'required','message'=>'Please Mark Processs To Be Done With Checked Application'],
        ];
    }
}
