<?php

namespace backend\controllers;

use app\models\ProductImages;
use app\models\Products;
use app\models\ProductsSearch;
use app\models\Specifications;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
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
                        'permissions' =>['Products']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Products models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $images = ProductImages::find()->where(['product_id' => $id])->all();

        return $this->render('view', [
            'model' => $model,
            'images' => $images,
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new Products();
        $initialPreview = [];
        $initialPreviewConfig = [];

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();

            if ($model->load($postData) && $model->save()) {
                // Rasm fayllarini yuklash
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                $savedFiles = $model->upload();

                if ($savedFiles) {
                    foreach ($savedFiles as $fileName) {
                        $image = new ProductImages();
                        $image->product_id = $model->id; // product_id emas, id bo'lishi kerak
                        $image->image_file_name = $fileName;
                        if (!$image->save(false)) {
                            Yii::error($image->errors);
                        }
                    }
                } else {
                    Yii::error($model->errors);
                }

                // Spesifikatsiyalarni saqlash
                $specifications = $postData['Products']['specifications'] ?? [];
                foreach ($specifications as $specificationData) {
                    $specification = new Specifications();
                    $specification->product_id = $model->id; // product_id emas, id bo'lishi kerak
                    $specification->key_uz = $specificationData['key_uz'] ?? '';
                    $specification->key_ru = $specificationData['key_ru'] ?? '';
                    $specification->key_en = $specificationData['key_en'] ?? '';
                    $specification->value_uz = $specificationData['value_uz'] ?? '';
                    $specification->value_ru = $specificationData['value_ru'] ?? '';
                    $specification->value_en = $specificationData['value_en'] ?? '';

                    // Agar har qanday til uchun kalit (key) bo'sh bo'lmasa, spesifikatsiya saqlanadi
                    if (!empty($specification->key_uz) || !empty($specification->key_ru) || !empty($specification->key_en)) {
                        if (!$specification->save()) {
                            Yii::error($specification->errors);
                        }
                    }
                }

                // Yaratilgan mahsulotning ko'rish sahifasiga qayta yo'naltirish
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error($model->errors);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $productImages = ProductImages::findAll(['product_id' => $id]);

        $initialPreview = [];
        $initialPreviewConfig = [];
        foreach ($productImages as $image) {
            $initialPreview[] = Url::to('http://localhost:8881/uploads/' . $image->image_file_name);
            $initialPreviewConfig[] = [
                'caption' => $image->image_file_name,
                'url' => Url::to(['http://localhost:8881/uploads/', 'id' => $image->id]),
                'key' => $image->id
            ];
        }

        // Load existing specifications
        $existingSpecifications = Specifications::findAll(['product_id' => $id]);
        $specifications = [];
        foreach ($existingSpecifications as $spec) {
            $specifications[] = $spec;
        }
        $model->specifications = $specifications;

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();

            if ($model->load($postData) && $model->save()) {
                // Handle image updates
                $images = UploadedFile::getInstances($model, 'imageFiles');
                if (!empty($images)) {
                    foreach ($productImages as $image) {
                        $image->delete();
                    }
                    foreach ($images as $image) {
                        $filePath = Yii::getAlias('@frontend/web/uploads/') . $image->baseName . '.' . $image->extension;
                        if ($image->saveAs($filePath)) {
                            $newProductImage = new ProductImages();
                            $newProductImage->product_id = $model->id;
                            $newProductImage->image_file_name = $image->baseName . '.' . $image->extension;
                            if (!$newProductImage->save()) {
                                Yii::$app->session->setFlash('error', 'Failed to save new image.');
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'Failed to upload new image.');
                        }
                    }
                }

                // Handle specifications updates
                $specificationData = $postData['Products']['specifications'] ?? [];
                foreach ($specificationData as $index => $specData) {
                    $specification = isset($specifications[$index]) ? $specifications[$index] : new Specifications();
                    $specification->product_id = $model->id;
                    $specification->key_uz = $specData['key_uz'] ?? '';
                    $specification->key_ru = $specData['key_ru'] ?? '';
                    $specification->key_en = $specData['key_en'] ?? '';
                    $specification->value_uz = $specData['value_uz'] ?? '';
                    $specification->value_ru = $specData['value_ru'] ?? '';
                    $specification->value_en = $specData['value_en'] ?? '';

                    // Only save if there's a key in any language
                    if (!empty($specification->key_uz) || !empty($specification->key_ru) || !empty($specification->key_en)) {
                        if (!$specification->save()) {
                            Yii::error($specification->errors);
                        }
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
        ]);
    }

    /**
     * Deletes an existing Products model.
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
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
