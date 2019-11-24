<?php

namespace app\controllers;

use Yii;
use app\models\Entry;
use app\models\Heat;
use app\models\Wordcount;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EntryController implements the CRUD actions for Entry model.
 */
class EntryController extends Controller
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
     * Lists all Entry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Entry::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Entry model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Entry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($plan_id, $index = 0)
    {
        
        $model = new Entry();

        if ($model->load(Yii::$app->request->post())) {

            if (($entry = Entry::find()->where(['date' => $model->date, 'plan_id' => $plan_id])->one()) !== null) {

                $entry->amount = $model->amount;
                $entry->entered = true;

                // find existing heat entry or create a new one
                if (($theheat = Heat::find()->where(['date' => $model->date])->one()) !== null) {
                    $heat = $theheat;
                } else {
                    $heat = new Heat();
                }
                $heat->entries = $heat->entries + 1;
                $heat->date = $entry->date;
                $heat->save();

                if ($entry->save()) {
                    $words = null;
                    if (($thewords = Wordcount::find()->one()) !== null) {
                        $words = $thewords;
                    } else {
                        $words = new Wordcount();
                    }
                    $query = Entry::find();
                    $words->totalwords = $query->sum('amount');
                    $words->save();

                    if($index == 1) {
                        return $this->redirect(['plan/index']);
                    } else {
                        return $this->redirect(['plan/view', 'id' => $entry->plan_id]);
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'plan_id' => $plan_id,
        ]);
    }

    /**
     * Creates a new Entry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGlobal()
    {
        
        $model = new Entry();

        if ($model->load(Yii::$app->request->post())) {

            $model->plan_id = null;

            // find existing heat entry or create a new one
            if (($theheat = Heat::find()->where(['date' => $model->date])->one()) !== null) {
                $heat = $theheat;
            } else {
                $heat = new Heat();
            }
            $heat->entries = $heat->entries + 1;
            $heat->date = $model->date;
            $heat->save();

            if ($model->save()) {
                $words = null;
                if (($thewords = Wordcount::find()->one()) !== null) {
                    $words = $thewords;
                } else {
                    $words = new Wordcount();
                }
                $query = Entry::find();
                $words->totalwords = $query->sum('amount');
                $words->save();

                return $this->redirect(['plan/index']);
            }
        }

        return $this->render('global', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Entry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plan/view', 'id' => $model->plan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Entry model.
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
     * Finds the Entry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Entry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Entry::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
