<?

/**
 *  Implements hook_menu
 */
function tr_form_menu() {
    $items['learn/tr-form'] = [
        'title' => t('TerraSoft'),
        'page callback' => 'drupal_get_form',
        //   'page callback' => "tr_form_html_page",
        'page arguments' => ['domain_form'],
        'access callback' => TRUE,
        'type' => MENU_NORMAL_ITEM
    ];
    return $items;
}

function getCountries($id = NULL) {

    if (isset($id)) {
        //  return $countries[$id];
        $result = db_select("tr_form_countries", "t")->fields("t", ["name", "cid","phone_code"])->condition("cid", $id)->execute()->fetchAll();
        if(isset($result[0])){
            $countries["name"] = $result[0]->name;
            $countries["phone_code"] = $result[0]->phone_code;
        }
    } else {
        $result = db_select("tr_form_countries", "t")->fields("t", ["name", "cid","phone_code"])->execute()->fetchAllAssoc('cid');
        if (is_array($result)) {
            foreach ($result as $cid => $item) {
                $countries[$item->cid]["name"] = $item->name;
                $countries[$item->cid]["phone_code"] = $item->phone_code;
            }
        }
    }
   
    return $countries;
}
function makePhone($code,$phone){
    $pNumber = str_split($code.$phone);

    $result = "";
    foreach($pNumber as $sym){
        if(is_numeric($sym)){
            $result .=$sym;
        }
    }
    if(count($pNumber)> 18 || count($pNumber)< 11)
        return FALSE;
    return $result;
}
function domain_form() {
 
    $form['domain'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Domain')),
        '#required' => TRUE    
    );
    $form['login'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Login')),
        '#required' => TRUE 
    );
    $country = getCountries();
    foreach($country as $id => $item){
         $countries[$id] = $item["name"];
         $attr["data-code"][$id] = $item["phone_code"];
    }
   
    $form['country_id'] = array(
        '#type' => 'select',
        '#options' => $countries,
        '#attributes' => $attr
    );
    $form['city'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('City')),);
    $form['code'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Code')),
        '#required' => TRUE 
    );
    $form['phone'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Phone')),
        '#required' => TRUE 
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Register',
    );

    return $form;
}

function getDomainZone() {
    return "bpmonline.com";
}

/**
 *  Implements hook_theme()
 */
function tr_form_theme($existing, $type, $theme, $path) {

    $items['domain_form'] = array(
        'render element' => 'form',
        'template' => 'form',
        'path' => drupal_get_path('module', 'tr_form') . '/template/form',
    );

    return $items;
}

function domain_form_validate($form, &$form_state) {
   
     $domain = $form_state["values"]["domain"];
    if(db_select("tr_form_domains","d")->fields("d",["did"])->condition("name",$domain)->execute()->fetch() !== false){
        form_set_error('domain',t('Domain already exixts'));
    }elseif(!preg_match("'^[a-z0-9]+[a-z0-9-]+$'", $domain)){
        form_set_error('domain1',t('Domain contains invalid character'));
    }
 
    //Country validation
    if(empty($form_state["values"]["country_id"])&& is_int($form_state["values"]["country_id"]) && !getCountries($form_state["values"]["country_id"])){
   
        form_set_error('country_id',t('Invalied country'));
    }
  
    //Login validation
    if(!$form_state["values"]["login"]){
        form_set_error('login2',t('Login can not be empty'));
    }elseif(!empty($form_state["values"]["login"]) &&  !preg_match("'^[a-zA-Z0-9-_.]+$'", $form_state["values"]["login"])){
        form_set_error('login1',t('Login contains invalid character'));
    } 
    //City validation
    if(!empty($form_state["values"]["city"]) &&  !preg_match("'^[a-zA-Z0-9-_ .]+$'", $form_state["values"]["city"])){
        form_set_error('domain1',t('City contains invalid character'));
    }
    //Phone validation
    if ( !makePhone($form_state["values"]["code"], $form_state["values"]["phone"])){
        form_set_error('phone1',t('Invalid phone'));
    }
}

function domain_form_submit($form, &$form_state) {
   // kint($form_state);
    /* add new domain */
    $domain = $insert = [];
    $domain["name"] = $form_state["values"]["domain"];
    $domain_id = db_insert("tr_form_domains")->fields($domain)->execute();

    /*
    *  Add form result
    *  making array of fileds for insert to DB
    */
    $insert["phone"] = makePhone($form_state["values"]["code"],$form_state["values"]["phone"]);
    $insert["country_id"] = $form_state["values"]["country_id"];
    isset($form_state["values"]["city"]) ? $insert["city"] = $form_state["values"]["city"] : true;
    $insert["login"] = $form_state["values"]["login"];
    $insert["domain_id"] = $domain_id;
    
    //inserting new form result to DB
    db_insert("tr_form_result")->fields($insert)->execute();
    $insert["domain"] = $domain["name"];
    $insert["country"] = getCountries($insert["country_id"])["name"];
    unset($insert["domain_id"]);
    unset($insert["country_id"]);
    
    //sending mail to site administrator email
    drupal_mail("tr_form", "domain_form", variable_get("site_mail"), language_default(), $insert);
    drupal_set_message(t("success"));
}

/**
 *  Implements hook_mail()
 */
function tr_form_mail($key, &$message, $params) {

    switch ($key) {
        case 'domain_form':
            // Set headers etc
            $msg = "";
            foreach ($params as $f_name => $f_value) {
                if (isset($f_value)){
                    $p["@" . $f_name] = $f_value;
                    $msg .= "<strong>".ucfirst($f_name).":</strong> ".$f_value."<br>";
                }    
            }          
            $message['subject'] = t('Form registration:');
            $message['body'][] = t('Hello you have new message from form registration:<br> '.$msg, $p);
            break;
    }
}
