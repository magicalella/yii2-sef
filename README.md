Collegamenti CNC Yii2 (URL) per il sito
=======


Installazione
------------

Il modo migliore per installare questa estensione è tramite [composer](http://getcomposer.org/download/).

Lancia

```
php compositore.phar require --prefer-dist alex290/yii2-sef "*"
```

o aggiungi

```
"alex290/yii2-sef": "*"
```

nella sezione require del tuo file "composer.json".

**E avvia la migrazione dei file**

yii migrazione/up --migrationPath=@vendor/alex290/yii2-sef/migrations

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
                     'classe' => 'alex290\sef\SefRule',
                     'IDconnessione' => 'db',
                 ],
             ],
         ],

Per il pannello di amministrazione, puoi creare un modello "Sef" oppure puoi utilizzare un modello "alex290\sef\Sef" già pronto

'link' = questo è il campo del collegamento originale, ad esempio articolo/vista?id=49

'link_sef' = questo campo è sinonimo dell'URL
