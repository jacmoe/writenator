<?php

namespace app\controllers;

use app\models\Plan;
use app\models\Entry;
use app\models\Wordcount;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;

/**
 * PlanController implements the CRUD actions for Plan model.
 */
class PlanController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Plan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Plan::find(),
        ]);

        $totalwords = 0;
        if (($words = Wordcount::find()->one()) !== null) {
            $totalwords = $words->totalwords;
        }

        return $this->render('index', [
            'plans' => $dataProvider->getModels(),
            'totalwords' => $totalwords,
        ]);
    }

    /**
     * Displays a single Plan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $end_date = date_create($model->end);
        $today_date = date_create(date("d-M-Y"));
        $interval = date_diff($end_date, $today_date)->format('%R%a');
        $has_ended = $interval > 0;

        return $this->render('view', [
            'plan' => $model,
            'ended' => $has_ended,
        ]);
    }

    /**
     * Creates a new Plan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Plan();

        if ($model->load(Yii::$app->request->post())) {
            $datetime1 = date_create($_POST['start']);
            $datetime2 = date_create($_POST['end']);
            $interval = date_diff($datetime1, $datetime2);
            $daycount = $interval->format('%R%a') + 1;
            $model->daycount = $daycount;
            $model->start = $_POST['start'];
            $model->end = $_POST['end'];
            $date = date("d-M-Y", strtotime($model->start));
             if($model->save()) {
                for ($i=0; $i < $model->daycount; $i++) { 
                    $entry = new Entry();
                    $entry->plan_id = $model->id;
                    $entry->date =  $date;
                    $date = date("d-M-Y", strtotime($date . ' + 1 days'));
                    $entry->amount = 0;
                    $entry->save();
                }
                return $this->redirect(['view', 'id' => $model->id]);
             }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Edits an existing Plan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Plan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $query = (new Query())->select('*')->from('entry');
        $query->where(['plan_id' => $id, 'entered' => 1]);
        $query->orderBy(['id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('update', [
            'dataProvider' => $dataProvider,
            'plan_id' => $id,
        ]);
    }

    /**
     * Deletes an existing Plan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Plan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
