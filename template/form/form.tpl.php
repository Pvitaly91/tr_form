<?
//kint($variables);
global $base_path;
?>
<style>
    @font-face {
        font-family: 'Helvetica Neue Cyr Thin';
        src: url('/sites/all/modules/tr_form/fonts/HelveticaNeueCyr-Thin.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
   
</style>

<div class="row">
    <div class="col-sm-12 form">
        <div class="form">
          
            <p class="title"><?=drupal_get_title();?></p>
            <div class="from-container">
                <p><?=t('Register for a 14-day Free Trial')?></p>
                <form action="/learn/tr-form" method="post" id="<?= $variables["form"]['#form_id'] ?>" accept-charset="UTF-8">
                    <? foreach ($variables["form"] as $f_name => $f): ?>
                        <? if (is_array($f) && isset($f['#type'])): ?>
                            <? if ($f['#type'] == "textfield" && $f_name != "domain" && $f_name != "code" && $f_name != "phone"): ?>
                                <div class="form-group">
                                    <input 
                                        type="text"
                                        id="edit-<?= $f_name ?>"
                                        name="<?= $f_name ?>"
                                        value="<?= $f["#value"] ?>" 
                                        maxlength="<?= $f["#maxlength"] ?>" 
                                        <?/*if($f['#required'] == TRUE):?>
                                            required
                                        <? endif;*/?>
                                        <?
                                        if (is_array($f['#attributes'])):
                                            foreach ($f['#attributes'] as $attr_name => $attr_value):
                                                if($attr_name == 'placeholder' && $f['#required'] == TRUE) { $attr_value = $attr_value."*"; }
                                                echo " " . $attr_name . "=" . $attr_value;
                                            endforeach;
                                        endif;
                                        ?>
                                        class="form-text form-control">
                                </div>
                            <? elseif($f_name == "domain"):?>    
                                <div class="form-group row">
                                    <div class="col-sm-7 ">
                                        <input 
                                            type="text"
                                            id="edit-<?= $f_name ?>"
                                            name="<?= $f_name ?>"
                                            value="<?= $f["#value"] ?>" 
                                            maxlength="<?= $f["#maxlength"] ?>" 
                                            <?if($f['#required'] == TRUE):?>
                                            required
                                            <? endif;?>
                                            <?
                                            if (is_array($f['#attributes'])):
                                                foreach ($f['#attributes'] as $attr_name => $attr_value):
                                                    if($attr_name == 'placeholder' && $f['#required'] == TRUE) { $attr_value = $attr_value."*"; }
                                                    echo " " . $attr_name . "=" . $attr_value;
                                                endforeach;
                                            endif;
                                            ?>
                                            class="form-text form-control">
                                    </div>
                                    <div class="col-sm-5 domain">
                                        .<?=getDomainZone()?>
                                    </div>
                                </div>
                             <? elseif ($f['#type'] == "select" && is_array($f['#options'])): ?>
                                <div class="form-group form-item form-type-select form-item-country">
                                    <select id="edit-country" name="<?= $f_name ?>" class="custom-select tr-form-select" >
                                        <?
                                        $data_codes = $f['#attributes']['data-code'];
                                        $selected_code = $data_codes[key($data_codes)]?>
                                        <? foreach ($f['#options'] as $id => $value): ?>
                                            <option 
                                                data-code="<?=$data_codes[$id]?>"
                                                value="<?=$id ?>"
                                                <? if($f['#value'] == $id) {
                                                    echo "selected";
                                                    $selected_code = $data_codes[$id];
                                                } ?> 
                                             ><?= $value ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            <? endif; ?>
                        <? endif; ?>
                    <? endforeach; ?>
                    <?  /*
                        *   code and phone fields
                        */ ?>
                        <div class=" form-group row">
                            <div class="col-sm-3 ">
                                 <input 
                                    type="text"
                                    id="edit-code"
                                    name="code"
                                    value="<?= $selected_code ?>" 
                                    <?if($variables["form"]["code"]['#required'] == TRUE):?>
                                        required
                                    <? endif;?>
                                    <? if (is_array($variables["form"]["code"]['#attributes'])):
                                        foreach ($variables["form"]["code"]['#attributes'] as $attr_name => $attr_value):
                                            if($attr_name == 'placeholder' && $variables["form"]["code"]['#required'] == TRUE) { $attr_value = $attr_value."*"; }
                                            echo " " . $attr_name . "=" . $attr_value;
                                        endforeach;
                                    endif;?>
                                    class="form-text form-control">
                            </div>	
                            <div class="col-sm-9 ">
                                <input 
                                    type="text"
                                    id="edit-phone"
                                    name="phone"
                                    value="<?= $variables["form"]["phone"]["#value"] ?>" 
                                    <?if($variables["form"]["phone"]['#required'] == TRUE):?>
                                        required
                                    <? endif;?>
                                    <?if (is_array($variables["form"]["phone"]['#attributes'])):
                                        foreach ($variables["form"]["phone"]['#attributes'] as $attr_name => $attr_value):
                                            if($attr_name == 'placeholder' && $variables["form"]["phone"]['#required'] == TRUE) { $attr_value = $attr_value."*"; }
                                            echo " " . $attr_name . "=" . $attr_value;
                                        endforeach;
                                    endif;?>
                                    class="form-text form-control">
                            </div>
                        </div>
                   
                    <div class="form-group form_bottom">
                        <button type="submit" id="edit-submit" name="op" class="btn "><?= $variables["form"]['submit']["#value"] ?></button>
                        <? if(isset($variables["form"]["form_build_id"]["#value"])):?><input type="hidden" name="form_build_id" value="<?= $variables["form"]['form_build_id']["#value"] ?>"><? endif;?>
                        <? if(isset($variables["form"]["form_token"]["#value"])):?><input type="hidden" name="form_token" value="<?= $variables["form"]["form_token"]["#value"] ?>"><? endif;?>
                        <? if(isset($variables["form"]["form_id"]["#value"])):?><input type="hidden" name="form_id" value="<?= $variables["form"]['form_id']["#value"] ?>"><? endif;?>
                         <hr>
                        <a href="<?=$base_path?>user/register"><? user_is_logged_in() ? print(t('Edit Profile')) :  print(t('Create Account'))?></a>
                    </div>
                   
                </form>
            </div>
            <div class="links">
                <a href="<?=$base_path?>"><?=t('Main Page')?></a> | <a href="<?=$base_path?>contacts"><?=t('Contacts')?></a>
            </div>
        </div>
    </div>
</div>
