<?

/**
 *  Implements hook_menu
 */

function tr_form_menu() {
    $items['learn/tr-form'] = [
        'title' => t('TerraSoft'),
        'page callback' => 'drupal_get_form',
        //   'page callback' => "tr_form_html_page",
        'page arguments' => ['first_form'],
        'access callback' => TRUE,
        'type' => MENU_NORMAL_ITEM
    ];
    return $items;
}

function getCountries($id = NULL) {
   
    if (isset($countries[$id])) {
        return $countries[$id];
    }else{
      //  $countries = db_select("tr_form_countries")->ge//>addField("name")
    }
    return $countries;
}

function first_form() {
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
        '#value' => 'Submit',
    );

    return $form;
}
function getDomainZone(){
    return "bpmonline.com";
}
/**
 *  Implements hook_theme()
 */
function tr_form_theme($existing, $type, $theme, $path) {

    $items['first_form'] = array(
        'render element' => 'form',
        'template' => 'form',
        'path' => drupal_get_path('module', 'tr_form') . '/template/form',
    );

    return $items;
}

function first_form_validate($form, &$form_state) {
    // form_set_error('zip',t('sasasa'));
}

function first_form_submit($form, &$form_state) {
    //exit;
    $domain = $insert = [];
    $domain["name"] = $form_state["values"]["domain"].".".getDomainZone();
    $domain_id = db_insert("tr_form_domains")->fields($domain)->execute();
    
    
    $insert["phone"] = $form_state["values"]["code"].$form_state["values"]["phone"];
  
    $insert["country_id"] = $form_state["values"]["country_id"];
    $insert["login"] = $form_state["values"]["login"];
    $insert["domain_id"] = $domain_id;
   
    db_insert("tr_form_result")->fields($insert)->execute();
    drupal_set_message(t("success"));

}



