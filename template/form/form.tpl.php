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
    .form{ font-family: "Helvetica Neue Cyr Thin";
    }

    .from-container form{
        /*padding:0px 40px 20px;*/
        margin:0px;

        text-align:center;
        color:white;
    }
    .from-container form a{
        color:white;
    }
    .from-container{
        padding:40px;
    }

    .from-container input, .from-container .tr-form-select{
        background-color:transparent;
        border:none;
        border-bottom:1px solid white;
        border-radius:0px;
    }
    .from-container{

        background-color:#64b8df;
        border-width: 0.5px;
        border-color: rgb(149, 149, 149);
        border-style: solid;
        border-radius: 6px;
    }
    .from-container p {
        color:white;
        font-size: 26px;
        line-height: 0.714;
        text-align: center;
    }
    p.title {
        font-size: 25px;

        color: rgb(94, 100, 100);
        margin-bottom:30px;
        text-align: center;

    }

    button {
        font-size: 22px;
        color: rgb(255, 255, 255);
        line-height: 0.909;
        text-align: center;

        border-radius: 4px;
        background-color: #f27b30;
        width:100%;

    }
    .links{
        color:#5e6464;
        padding-top:20px;
        text-align:center;
    }
    .links a {
        margin:0px 20px;
        color:#5e6464;
    }
    .form-group div.domain{
        margin:0px;
        padding:15px 0px 0px;
        text-align:left;
        color:white;
    }
    .tr-form-select{
        
        color: #b2dcef;
    }
    input[type="text"]::-webkit-input-placeholder { color: #b2dcef; }
    input[type="text"]:-ms-input-placeholder { color: #b2dcef; }
    input[type="text"]::-ms-input-placeholder { color: #b2dcef; }
    input[type="text"]::placeholder { color: #b2dcef; } 

</style>
<? kint($variables["form"]);?>
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
                                        <?
                                        if (is_array($f['#attributes'])):
                                            foreach ($f['#attributes'] as $attr_name => $attr_value):
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
                                            <?
                                            if (is_array($f['#attributes'])):
                                                foreach ($f['#attributes'] as $attr_name => $attr_value):
                                                    echo " " . $attr_name . "=" . $attr_value;
                                                endforeach;
                                            endif;
                                            ?>
                                            class="form-text form-control">
                                    </div>
                                    <div class="col-sm-5 domain">
                                        <?=getDomainZone()?>
                                    </div>
                                </div>
                             <? elseif ($f['#type'] == "select" && is_array($f['#options'])): ?>
                                <div class="form-group form-item form-type-select form-item-country">
                                    <select id="edit-country" name="<?= $f_name ?>" class="custom-select tr-form-select" >
                                        <? foreach ($f['#options'] as $id => $value): ?>
                                            <option 
                                                value="<?= $id ?>"
                                                <? ($f['#value'] == $id) ? print("selected") : false; ?> 
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
                                    value="<?= $variables["form"]["code"]["#value"] ?>" 
                                    <? if (is_array($variables["form"]["code"]['#attributes'])):
                                        foreach ($variables["form"]["code"]['#attributes'] as $attr_name => $attr_value):
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
                                    <?if (is_array($variables["form"]["phone"]['#attributes'])):
                                        foreach ($variables["form"]["phone"]['#attributes'] as $attr_name => $attr_value):
                                            echo " " . $attr_name . "=" . $attr_value;
                                        endforeach;
                                    endif;?>
                                    class="form-text form-control">
                            </div>
                        </div>
                   
                    <div class="form-group">
                        <button type="submit" id="edit-submit" name="op" class="btn "><?= $variables["form"]['submit']["#value"] ?></button>
                        <? if(isset($variables["form"]["form_build_id"]["#value"])):?><input type="hidden" name="form_build_id" value="<?= $variables["form"]['form_build_id']["#value"] ?>"><? endif;?>
                        <? if(isset($variables["form"]["form_token"]["#value"])):?><input type="hidden" name="form_token" value="<?= $variables["form"]["form_token"]["#value"] ?>"><? endif;?>
                        <? if(isset($variables["form"]["form_id"]["#value"])):?><input type="hidden" name="form_id" value="<?= $variables["form"]['form_id']["#value"] ?>"><? endif;?>
                    </div>
                    <hr>
                    <a href="<?=$base_path?>user/register"><? user_is_logged_in() ? print(t('Edit Profile')) :  print(t('Create Account'))?></a>
                </form>
            </div>
            <div class="links">
                <a href="<?=$base_path?>"><?=t('Main Page')?></a> | <a href="<?=$base_path?>contacts"><?=t('Contacts')?></a>
            </div>
        </div>
    </div>
</div>
