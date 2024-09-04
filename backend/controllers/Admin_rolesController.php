<?php

namespace backend\controllers;

use app\models\AdminRoles;
use app\models\AdminRolesSearch;
use app\models\RoleForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Admin_rolesController implements the CRUD actions for AdminRoles model.
 */
class Admin_rolesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['superAdmin'],
                        'permissions' => ['Admin_roles']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdminRoles models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new AdminRolesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdminRoles model.
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
     * Creates a new AdminRoles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AdminRoles();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AdminRoles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $auth = Yii::$app->authManager;
        $model = $this->findModel($id);

        // Barcha rol va permissionlarni olish
        $roles = $auth->getRoles();
        $permissions = $auth->getPermissions();

        // Foydalanuvchiga hozirda assign qilingan permissionlarni olish
        $assignedPermissions = array_keys($auth->getPermissionsByUser($model->user_id));

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            // Role ni olish
            $role = $auth->getRole($model->role_name);
            if (!$role) {
                throw new \yii\web\NotFoundHttpException('Requested role not found.');
            }

            // Avvalgi role va permissionlarni olib tashlash
            $auth->revokeAll($model->user_id);

            // Yangi role ni assign qilish
            $auth->assign($role, $model->user_id);

            // Yangi permissionlarni assign qilish
            if (is_array($model->permissions)) {
                // Role bilan bog'liq bo'lgan barcha eski permissionlarni olib tashlash
                $rolePermissions = $auth->getPermissionsByRole($model->role_name);
                foreach ($rolePermissions as $permission) {
                    $auth->removeChild($role, $permission);
                }

                // Foydalanuvchi tomonidan tanlangan yangi permissionlarni qo'shish
                foreach ($model->permissions as $permissionName) {
                    $permission = $auth->getPermission($permissionName);
                    if ($permission) {
                        $auth->addChild($role, $permission);
                        $auth->assign($permission, $model->user_id);
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
            'permissions' => $permissions,
            'assignedPermissions' => $assignedPermissions,
        ]);
    }

    public function actionGetRolePermissions($role_name = null, $permissions = [])
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $auth = Yii::$app->authManager;

        if ($role_name) {
            $permissions = $auth->getPermissionsByRole($role_name);
            return [
                'permissions' => array_keys($permissions),
                'role_name' => $role_name, // ro'lni ham qaytaramiz
            ];
        } elseif ($permissions) {
            // permissions orqali role_name ni aniqlaymiz
            foreach ($auth->getRoles() as $role) {
                $rolePermissions = $auth->getPermissionsByRole($role->name);
                if (array_keys($rolePermissions) == $permissions) {
                    return ['role_name' => $role->name];
                }
            }
        }

        return ['permissions' => []];
    }


    /**
     * Deletes an existing AdminRoles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdminRoles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return AdminRoles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminRoles::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
