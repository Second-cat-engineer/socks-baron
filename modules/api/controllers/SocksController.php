<?php

namespace app\modules\api\controllers;

use app\models\socks\Socks;
use app\models\socks\SocksQueryForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Controller;

class SocksController extends Controller
{
    protected function verbs(): array
    {
        return [
            'index'     => ['GET'],
            'income'    => ['POST'],
            'outcome'   => ['POST'],
        ];
    }

    public function actionIndex()
    {
        $socksQueryForm = new SocksQueryForm();
        $socksQueryForm->load(Yii::$app->getRequest()->getQueryParams(), '');

        if (!$socksQueryForm->validate()) {
            Yii::$app->getResponse()->setStatusCode(400);

            return $socksQueryForm->getValidationErrors();
        }

        $socksQuery = Socks::find()->select('quantity');
        if ($socksQueryForm->color !== null) {
            $socksQuery = $socksQuery->byColor($socksQueryForm->color);
        }
        if ($socksQueryForm->cottonPart !== null && $socksQueryForm->operation !== null) {
            $socksQuery = $socksQuery->byCottonPartCondition($socksQueryForm->operation, $socksQueryForm->cottonPart);
        }

        return (int)$socksQuery->sum('quantity');
    }

    /**
     * action income.
     *
     * params: {
     *  "color": string,
     *  "cottonPart": int,
     *  "quantity": int
     * }
     *
     * @throws InvalidConfigException
     */
    public function actionIncome()
    {
        $params = Yii::$app->getRequest()->getBodyParams();

        $model = new Socks();
        $model->color = $params['color'];
        $model->cotton_part = $params['cottonPart'];
        $model->quantity = $params['quantity'];

        if (!$model->validate()) {
            Yii::$app->getResponse()->setStatusCode(400);

            return $model->getValidationErrors();
        }

        if ($model->incrementSocksCount()) {
            return Yii::$app->getResponse()->setStatusCode(200);
        }

        return Yii::$app->getResponse()->setStatusCode(500);
    }

    /**
     * action outcome.
     *
     * params: {
     *  "color": string,
     *  "cottonPart": int,
     *  "quantity": int
     * }
     *
     * @throws InvalidConfigException
     */
    public function actionOutcome()
    {
        $params = Yii::$app->getRequest()->getBodyParams();

        $model = new Socks();
        $model->color = $params['color'];
        $model->cotton_part = $params['cottonPart'];
        $model->quantity = $params['quantity'];

        if (!$model->validate()) {
            Yii::$app->getResponse()->setStatusCode(400);

            return $model->getValidationErrors();
        }

        if ($model->decrementSocksCount()) {
            return Yii::$app->getResponse()->setStatusCode(200);
        }

        return Yii::$app->getResponse()->setStatusCode(500);
    }

}