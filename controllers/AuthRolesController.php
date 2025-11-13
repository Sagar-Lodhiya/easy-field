<?php

namespace app\controllers;

use app\models\AuthModules;
use app\models\AuthRoles;
use app\models\AuthRolesSearch;
use app\models\AuthPermissions;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthRolesController implements the CRUD actions for AuthRoles model.
 */
class AuthRolesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['create', 'update', 'delete', 'view', 'index'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete', 'view', 'index'],
                            'allow' => true,
                            'roles' => ['@'],          
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all AuthRoles models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuthRolesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthRoles model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthRoles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AuthRoles();
  
        $authModules = AuthModules::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                foreach ($this->request->post('AuthRoles')['authPermissions'] as $authItems) {
                    if(is_array($authItems)){
                        
                        foreach ($authItems as $authItemId) {
                            $authPermission = new AuthPermissions();
                            $authPermission->role_id = $model->id;
                            $authPermission->auth_items_id = $authItemId;
                            $authPermission->save();
                        }
                    }
                }
                \Yii::$app->session->setFlash('success', 'Roles Successfully Created');


                return $this->redirect(['view', 'id' => $model->id]);

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'authModules' => $authModules,
        ]);
    }

    /**
     * Updates an existing AuthRoles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
{
    $model = $this->findModel($id);

    // Fetch all active and non-deleted Auth Modules
    $authModules = AuthModules::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();

    // Get existing permissions for the role
    $assignedPermissions = AuthPermissions::find()
        ->where(['role_id' => $id])
        ->select('auth_items_id')
        ->column(); // Get an array of assigned auth_items_id

    if ($this->request->isPost && $model->load($this->request->post())) {
        // Remove all existing permissions for this role
        AuthPermissions::deleteAll(['role_id' => $id]);

        // Save new permissions
        if (!empty($this->request->post('permissions'))) {
            foreach ($this->request->post('permissions') as $authItemId) {
                $authPermission = new AuthPermissions();
                $authPermission->role_id = $model->id;
                $authPermission->auth_items_id = $authItemId;
                $authPermission->save();
            }
        }

        \Yii::$app->session->setFlash('success', 'Roles Successfully Updated');
        return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
        'model' => $model,
        'authModules' => $authModules,
        'assignedPermissions' => $assignedPermissions, // ✅ Pass existing permissions
    ]);
}
    

    /**
     * Deletes an existing AuthRoles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save(false);
        \Yii::$app->session->setFlash('success', 'Roles Successfully Deleted');
        return $this->redirect(['index']);

    }

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->is_active = ($model->is_active == 0) ? 1 : 0;
        if ($model->save(false)) {
            return 1;
        } else {
            return json_encode($model->errors);
        }
    }

    /**
     * Finds the AuthRoles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return AuthRoles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthRoles::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
