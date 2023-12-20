Collegamenti CNC Yii2 (URL) per il sito
=======


Installazione
------------

Il modo migliore per installare questa estensione è tramite [composer](http://getcomposer.org/download/).

Lancia

```
php compositore.phar require --prefer-dist magicalella/yii2-sef "*"
```

o aggiungi

```
"magicalella/yii2-sef": "*"
```

nella sezione require del tuo file "composer.json".

**E avvia la migrazione dei file**

yii migrazione/up --migrationPath=@vendor/magicalella/yii2-sef/migrations

Può essere creato manualmente. Vale a dire, la tabella `sef` ha solo 3 campi:

id(chiaveprimaria, AUTO_INCREMENT);

collegamento(varchar(255));

link_sef(varchar(255)).


Utilizzo
-----

Nel file: `config/web.php` o `frontend/config/main.php` se yii advanced scrivi

         'urlManager' => [
         'enablePrettyUrl' => vero,
         'showScriptName' => falso,
         'regole' => [
                 // Ci sono delle regole qui.
                 [
                     'classe' => 'magicalella\sef\SefRule',
                     'IDconnessione' => 'db',
                 ],
             ],
         ],

Per il pannello di amministrazione, puoi creare un modello "Sef" oppure puoi utilizzare un modello "magicalella\sef\src\Sef" già pronto

'link' = questo è il campo del collegamento originale, ad esempio articolo/vista?id=49

'link_sef' = questo campo è sinonimo dell'URL

Memorizzazione automatica delle URL in db e sostituzione automatica Meta Title e Description
-----
Inserire: 
in  _protected\ (Yii basic) o _protected\common\ (Yii advanced)
 - models Sef.php e SefSearch.php
 
in _protected\backend\controllers
 - controller SefController.php
 
in FrontendController
use app\models\Sef (Yii basic)
use common\models\Sef (Yii advanced)

public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        $request = Yii::$app->request;
        
        if ($request->isGet){
            Sef::inserisciSef($action,$request);
        }    
        return $result;
    }
    
    public function render ( $view, $params = [] ){
            $request = Yii::$app->request;
            $controller  = Yii::$app->controller->id;
            // ! SEO
            $meta_titol = Sef::scriviMetaTitle($view,$controller,$request);
            $meta_description =  Sef::scriviMetaDescription($view,$controller,$request);
            
            Yii::$app->view->title = $meta_titol;
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' =>  $meta_description,
            ]);
            return parent::render($view, $params);   
        }


