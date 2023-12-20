<?php
/*
* @author    Magilla Company <info@magilla.company>
* @copyright 2023 Magilla Company
*/
namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\Task;

/**
 * This is the model class for table "sef".
 *
 * @property int $id
 * @property string $link
 * @property string $link_sef
 */
 // ! introdurre anche loyalty_id
class Sef extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sef';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link', 'link_sef'], 'string', 'max' => 255],
            [['meta_title', 'meta_description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('sef', 'ID'),
            'link' => Yii::t('sef', 'Link'),
            'link_sef' => Yii::t('sef', 'Link Sef'),
            'meta_title' => Yii::t('sef', 'Meta Title'),
            'meta_description' => Yii::t('sef', 'Meta Description'),
        ];
    }
    
    public function attributeHints()
    {
        return [
            'link' => Yii::t('sef', 'Url originale'),
            'link_sef' => Yii::t('sef', 'Url Sef'),
            'meta_title' => Yii::t('sef', 'Se impostato prende quello in db con prefisso e separatore se in params add_prefix_meta_titol è impostato a 1, se non impostato prende il nome del sito '),
            'meta_description' => Yii::t('sef', 'Se impostato prende quello in db , altrimenti quello impostato di default Yii::t(\'meta\',\'meta_description\')'),
        ];
    }
    
    /**
    * Inserisce in db , se non già presente ,l'url
    */
    public static function inserisciSef($action,$request){
        $model_sef = [];
        $controller = $action->controller->id;
        $view = $action->id;
        $link = Self::_buildUrl($view,$controller,$request);
        $model_sef = static::findOneByLink($link);
        
        if(!$model_sef){
            $model_sef = new Sef();
            $model_sef->link = $link;
            if(!$model_sef->save()){
                Yii::error(sprintf('Errore salvataggio sef: %s', print_r($model_sef->getErrors(), true)), __METHOD__);
            }
        }
    }
    
    /**
    * Restituisce meta title 
    * se c'è meta custom in db restituisce quello con o senza prefisso , altrimenti nome action senza - e iniziale maiuscola con o senza prefisso
    */
    public static function scriviMetaTitle($view,$controller,$request){
        $prefix_meta_titol = (Yii::$app->params['prefix_meta_titol'] == '')?Yii::$app->name:Yii::$app->params['prefix_meta_titol'];
        $separazione_meta_titol = (Yii::$app->params['separazione_meta_titol'] == '')?' | ':Yii::$app->params['separazione_meta_titol'];
        // $controller = $action->controller->id;
        // $view = $action->id;
        
        $meta_titol = '';
        if(Yii::$app->params['add_prefix_meta_titol'] ){
            $meta_titol .= $prefix_meta_titol.$separazione_meta_titol;
        }
        $link = Self::_buildUrl($view,$controller,$request);
        $model_sef = static::findOneByLink($link);
        
        if($model_sef){
            if($model_sef->meta_title != ''){
                $meta_titol .= $model_sef->meta_title;
            }else{
                switch($controller){
                    case 'task':
                        if($view == 'view'){
                            $task_id = Yii::$app->request->get('id'); 
                               $task = Task::find()->where(['id'=>$task_id])->one();
                               $meta_titol .= Yii::t('meta','task').' '.$task->titolo;
                        }
                    break;
                    default:
                        //pensare bene come fare
                        $meta_titol .= Yii::t('meta',$controller).' '.Yii::t('meta',$view);
                }
            }
        }
        return Html::encode(strip_tags($meta_titol));
    }
    
    /**
    * Restituisce meta title 
    * se c'è meta custom in db restituisce quello , altrimenti lo prende dai params
    */
    public static function scriviMetaDescription($view,$controller,$request){
        $link = Self::_buildUrl($view,$controller,$request);
        $model_sef = static::findOneByLink($link);
        
        if($model_sef){
            if($model_sef->meta_description != ''){
                $meta_description = $model_sef->meta_description;
            }else{
                $meta_description = Yii::$app->params['meta_description'] ;
            }
        }
        return $meta_description;
    }
    
    /**
    costruisce link 
    
     */
    private static function _buildUrl($view,$controller,$request){
        $link = $controller.'/'.$view;
        $array_parametri = $request->getQueryParams();
        if(!empty($array_parametri)){
            $parametri = http_build_query($array_parametri, '', '&');
            $link .= '?'.$parametri;
        }
        return $link;
    }
    
    public static function findOneByLink($link){
        $model_sef = static::find()->where(['link'=>$link])->one();
        return (!empty($model_sef))?$model_sef:false;
    }
}
