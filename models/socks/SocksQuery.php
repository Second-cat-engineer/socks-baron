<?php

namespace app\models\socks;

use yii\db\ActiveQuery;

class SocksQuery extends ActiveQuery
{
    /**
     * Find socks by color.
     *
     * @param string $color
     * @return SocksQuery
     */
    public function byColor(string $color): SocksQuery
    {
        return $this->andWhere(['color' => $color]);
    }

    /**
     * Find socks by cotton part.
     *
     * @param int $cottonPart
     * @return SocksQuery
     */
    public function byCottonPart(int $cottonPart): SocksQuery
    {
        return $this->andWhere(['cotton_part' => $cottonPart]);
    }

    /**
     * Find socks by cotton part condition.
     *
     * @param string $operation
     * @param int $cottonPart
     * @return SocksQuery
     */
    public function byCottonPartCondition(string $operation, int $cottonPart): SocksQuery
    {
        $operator = '=';

        switch ($operation) {
            case SocksQueryForm::MORE_THAN_OPERATION:
                $operator = '>';
                break;
            case SocksQueryForm::LESS_THAN_OPERATION:
                $operator = '<';
                break;
            case SocksQueryForm::EQUAL_OPERATION:
                $operator = '=';
                break;
        }

        return $this->andWhere([$operator, 'cotton_part', $cottonPart]);
    }
}