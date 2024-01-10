<?php
/*
* @author    Magilla Company <info@magilla.company>
* @copyright 2023 Magilla Company
*/
/**
* Se vuoi estendere
 */
//namespace magicalella\sef;
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
class Sef extends magicalella\sef\Sef
{
    
    /**
    * Restituisce meta title 
    * se c'è meta custom in db restituisce quello con o senza prefisso , altrimenti nome action senza - e iniziale maiuscola con o senza prefisso
    */
    public static function scriviMetaTitle($view,$controller,$request){
        $meta_titol = parent::scriviMetaTitle($view,$controller,$request);
        $prefix_meta_titol = (Yii::$app->params['prefix_meta_titol'] == '')?Yii::$app->name:Yii::$app->params['prefix_meta_titol'];
        $separazione_meta_titol = (Yii::$app->params['separazione_meta_titol'] == '')?' | ':Yii::$app->params['separazione_meta_titol'];
        // $controller = $action->controller->id;
        // $view = $action->id;
        
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
    
}
