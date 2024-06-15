<?php

namespace magicalella\sef;

use Yii;
use magicalella\sef\Sef;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SefRule
 *
 * @author art
 */
class SefRule extends BaseObject implements UrlRuleInterface {

    //put your code here
    public $connectionID = 'db';
    public $name;

    public function init() {
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }

    /**
    Scrive url sef
     */
    public function createUrl($manager, $route, $params) {
        //debug($route);
        //Determiniamo i controller che devono essere aggiunti alle pagine .html
        $controller = explode('/', $route)[0]; //Otteniamo il controllore        
        $html = '';
        $language_code = false;

        //Se vengono passati parametri (ad esempio ?id=3&page=2) li salviamo in $link uno per uno
        $link = '';
        $page = '';
        if (count($params)) {
            $link = "?";
            $page = false;
            foreach ($params as $key => $value) {
                if ($key == 'page') {
                    $page = $value;
                    continue;
                }
                if (!is_array($value)) {
                    $newValue = $value;
                } else {
                    $newValue = '';
                }
                $link .= "$key=$newValue&";
            }
            $link = substr($link, 0, -1); //rimuovere l'ultimo carattere (&)
        }
        //se attivo motore lingue
        $language_code = Sef::getLanguageCode();
        if($language_code){
            $url_language_code = $language_code.'/';
        }
        
        //Dal database otteniamo una riga con un collegamento a cui dovremo modificare
        $sef = Sef::find()->where(['link' => $url_language_code . $route . $link])
        ->andWhere(['!=','link_sef',''])
        ->one();

        if ($sef) {
            //se attivo motore lingue e prefisso lingua lo tolgo da url perchè verrà aggiunto da motore lingue
            $sef->link_sef = Sef::removeLanguageCode($sef->link_sef,$language_code);
            //Se c'è, aggiungi l'impaginazione alla fine (?page=2)
            if ($page)
                return $sef->link_sef . "?page=$page";
            else
                return $sef->link_sef;
        }
        return false;
    }

    /**
    esegue url sef
     */
    public function parseRequest($manager, $request) {
        //prendiamo URL
        $pathInfo = $request->getPathInfo();

        //Otteniamo 1 parte, prima della barra, se presente
        $alias = explode('/', $pathInfo)[0];
        //Se alla fine è presente .html, lo rimuoviamo per la ricerca nel database
        $alias_small = str_replace(".html", "", $alias);

        //non restituire .html per gli URL specificati (prima parte dell'alias)
        $not_html = [
            'category', 'article', 'posts', 'notes'
        ];

        /*
          * Verifica la presenza di un URL (prima della barra) in $not_html
          * Se sì, l'URL non deve terminare con .html
          * $exception = true consente la ricerca URL nel database
        */

        //se attivo motore lingue
        $language_code = Sef::getLanguageCode();
        if($language_code){
            $url_language_code = $language_code.'/';
        }

        //riceviamo i dati dal database per una riga contenente un dato alias
        $sef = Sef::find()->where(['link_sef' => $url_language_code.$pathInfo])->one();
        $sef->link = Sef::removeLanguageCode($sef->link,$language_code);
        if ($sef) {
            //Divide una stringa come post/view?id=5 in un array in base al delimitatore
            $link_data = explode('?', $sef->link);
            //prendi solo la prima parte senza parametri (controller/azione)
            $route = $link_data[0];
            $params = array();
            //se ci sono parametri inserirli
            if (isset($link_data[1])) {
                $temp = explode('&', $link_data[1]);
                foreach ($temp as $t) {
                    $t = explode('=', $t);
                    $params[$t[0]] = $t[1];
                }
            }
            //$route - controllore/azione
            //$params - opzioni
            return [$route, $params];
        }

        return false;
    }

}
