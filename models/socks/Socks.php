<?php

namespace app\models\socks;

use yii\db\ActiveRecord;

/**
 * Socks model.
 *
 * @property integer $id
 * @property string  $color
 * @property integer $cotton_part
 * @property integer $quantity
 */
class Socks extends ActiveRecord
{
    public const MAX_COLOR_LENGTH = 100;
    public const MIN_COTTON_PART_VALUE = 0;
    public const MAX_COTTON_PART_VALUE = 100;

    public static function tableName(): string
    {
        return '{{%socks}}';
    }

    public function rules(): array
    {
        return [
            [
                'color', 'string', 'max' => self::MAX_COLOR_LENGTH
            ],
            [
                'cotton_part', 'integer', 'min' => self::MIN_COTTON_PART_VALUE, 'max' => self::MAX_COTTON_PART_VALUE
            ],
            [
                'quantity', 'integer', 'min' => 0
            ],
            [
                ['color', 'cotton_part', 'quantity'], 'required'
            ]
        ];
    }

    public static function find(): SocksQuery
    {
        return new SocksQuery(get_called_class());
    }

    public function getValidationErrors(): array
    {
        $result = [];
        foreach ($this->getFirstErrors() as $name => $message) {
            $result[] = [
                'field' => $name,
                'message' => $message,
            ];
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function incrementSocksCount(): bool
    {
        $socksByParams = self::find()
            ->byColor($this->color)
            ->byCottonPart($this->cotton_part)
            ->one();

        if (empty($socksByParams)) {
            return $this->save();

        }

        $socksByParams->quantity += $this->quantity;
        return $socksByParams->save();
    }

    /**
     * @return bool
     */
    public function decrementSocksCount(): bool
    {
        $socksByParams = self::find()
            ->byColor($this->color)
            ->byCottonPart($this->cotton_part)
            ->one();

        if (empty($socksByParams)) {
            return false;

        }

        $socksByParams->quantity -= $this->quantity;
        if ($socksByParams->quantity < 0) {
            return false;
        }

        return $socksByParams->save();
    }
}