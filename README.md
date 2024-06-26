Collegamenti CNC Yii2 (URL) per il sito
=======


Installazione
------------

Il modo migliore per installare questa estensione è tramite [composer](http://getcomposer.org/download/).

Lancia

```
php composer.phar require --prefer-dist magicalella/yii2-sef "*"
```

o aggiungi

```
"magicalella/yii2-sef": "*"
```

nella sezione require del tuo file "composer.json".

**E avvia la migrazione dei file**

yii migrate/up --migrationPath=@vendor/magicalella/yii2-sef/migrations

Può essere creato manualmente. Vale a dire, la tabella `sef` ha solo 3 campi:

id(primaryKey, AUTO_INCREMENT);
link(varchar(255));
link_sef(varchar(255));
 meta_title(varchar(255));
 meta_description(varchar(255));


Utilizzo
-----

Nel file: `config/web.php` o `frontend/config/main.php` se yii advanced scrivi

        'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
                // Ci sono delle regole qui.
                [ 
                    'class' => 'magicalella\sef\SefRule',
                    'connectionID' => 'db',
                ],
            ],
        ],

Per il pannello di amministrazione, puoi creare un modello "Sef" oppure puoi utilizzare un modello "magicalella\sef\src\Sef" già pronto

'link' = questo è il campo del collegamento originale, ad esempio articolo/vista?id=49

'link_sef' = questo campo è sinonimo dell'URL

'meta_title' = Titolo della pagina

'meta_description' = Meta description della pagina


Memorizzazione automatica delle URL in db e sostituzione automatica Meta Title e Description

Inserire: 
----
in  _protected\ (Yii basic) o _protected\common\ (Yii advanced)
----
 - models Sef.php e SefSearch.php

in _protected\backend\controllers
----
 - controller SefController.php
 
in FrontendController
----
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

in params
----
//se impostato prende aggiunge prefisso
'add_prefix_meta_titol' => 1,
//se non impostato prende di default il nome del sito sempre se add_prefix è a 1
'prefix_meta_titol' => 'prefix',
//se non impostato prende di default ' | '
'separazione_meta_titol' => ' | ',
'meta_description' => 'Default meta description',
