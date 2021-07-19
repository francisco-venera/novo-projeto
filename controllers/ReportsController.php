<?php

namespace app\controllers;

use app\models\reports\ReportCliente;

use Yii;
use yii\filters\AccessControl;

class ReportsController extends \yii\web\Controller
{

    //Essas permissões não precisa

    /**
     * @return string
     */
    public function actionReportClient()
    {
        $model = new ReportCliente();
        $dataProvider = $model->process();

        if ($model->load(\Yii::$app->request->post())) {
            try {
                $dataProvider = $model->process();
            } catch (FeedbackException $e) {
                Yii::$app->getSession()->setFlash('error', $e->getMessage());
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('report-cliente/index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    }

