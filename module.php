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
        $result = db_select("tr_form_countries", "t")->fields("t", ["name"])->condition("cid", $id)->execute()->fetchAll();
        if(isset($result[0])){
            $countries = $result[0]->name;
        }
    } else {
        $result = db_select("tr_form_countries", "t")->fields("t", ["name", "cid"])->execute()->fetchAllAssoc('cid');
        if (is_array($result)) {
            foreach ($result as $cid => $item) {
                $countries[$cid] = $item->name;
            }
        }
    }
    return $countries;
}

function domain_form() {
    /*
      TOODO Country array must take from DB or Another source

     */

    $form['domain'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Domain')),
    );
    $form['login'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Login')),
    );
    $form['country_id'] = array(
        '#type' => 'select',
        '#options' => getCountries()
    );
    $form['city'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('City')),);
    $form['code'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Code')),
    );
    $form['phone'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => t('Phone')),
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
    // form_set_error('zip',t('sasasa'));
}

function domain_form_submit($form, &$form_state) {
   // kint($form_state);
    /* add new domain */
    $domain = $insert = [];
    $domain["name"] = $form_state["values"]["domain"] . "." . getDomainZone();
    $domain_id = db_insert("tr_form_domains")->fields($domain)->execute();

    /*
    *  Add form result
    *  making array of fileds for insert to DB
    */
    $insert["phone"] = $form_state["values"]["code"] . $form_state["values"]["phone"];
    $insert["country_id"] = $form_state["values"]["country_id"];
    isset($form_state["values"]["city"]) ? $insert["city"] = $form_state["values"]["city"] : true;
    $insert["login"] = $form_state["values"]["login"];
    $insert["domain_id"] = $domain_id;
    
    //inserting new form result to DB
    db_insert("tr_form_result")->fields($insert)->execute();
    $insert["domain"] = $domain["name"];
    $insert["country"] = getCountries($insert["country_id"]);
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
