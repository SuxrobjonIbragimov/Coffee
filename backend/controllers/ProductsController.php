<?php

namespace backend\controllers;

use app\models\ProductImages;
use app\models\Products;
use app\models\ProductsSearch;
use Yii;
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
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
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

        $productImages = $model->isNewRecord ? [] : ProductImages::find()->where(['product_id' => $model->id])->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

                if ($model->save()) {
                    $savedImages = $model->upload();
                    if ($savedImages !== false) {
                        foreach ($savedImages as $imageName) {
                            $productImage = new ProductImages();
                            $productImage->product_id = $model->id;
                            $productImage->image_file_name = $imageName;
                            $productImage->save();
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
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

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();

            if ($model->load($postData) && $model->save()) {
                $images = UploadedFile::getInstances($model, 'imageFiles');

                if (!empty($images)) {
                    // Unassociate old images from the product without deleting the files
                    foreach ($productImages as $image) {
                        $image->delete(); // Only delete the record from the database, not the file
                    }

                    // Save new images
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
