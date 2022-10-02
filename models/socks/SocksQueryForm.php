<?php

namespace app\models\socks;

use yii\base\Model;

/**
 * SocksQueryForm class.
 *
 * Model for validation socks query params.
 *
 * @property string  $color
 * @property integer $cottonPart
 * @property string  $operation
 */
class SocksQueryForm extends Model
{
    public const MORE_THAN_OPERATION = 'moreThan';
    public const LESS_THAN_OPERATION = 'lessThan';
    public const EQUAL_OPERATION = 'equal';

    public ?string $color = null;
    public ?string $operation = null;
    public ?int $cottonPart = null;

    public function rules(): array
    {
        return [
            [
                'color', 'string'
            ],
            [
                'cottonPart', 'integer', 'min' => Socks::MIN_COTTON_PART_VALUE, 'max' => Socks::MAX_COTTON_PART_VALUE
            ],
            [
                'operation', 'required', 'when' => function() {
                    return $this->cottonPart !== null;
                }
            ],
            [
                'operation', 'in', 'range' => [
                    self::MORE_THAN_OPERATION,
                    self::LESS_THAN_OPERATION,
                    self::EQUAL_OPERATION
                ]
            ],
        ];
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
}