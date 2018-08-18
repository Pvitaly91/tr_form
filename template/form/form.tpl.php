<?php
kint($variables);

?><div class="content">
    <form action="/learn/tr-form" method="post" id="<?=$variables["form"]['#form_id']?>" accept-charset="UTF-8">
        <div>
            <? foreach($variables["form"] as $f_name => $f):?>
                <?  if(is_array($f) && isset($f['#type'])):?>
                    <? if($f['#type'] == "textfield" ):?>
                        <div class="form-item form-type-textfield form-item-domain">
                            <input 
                               
                                type="text"
                                id="edit-<?=$f_name?>"
                                name="<?=$f_name?>"
                                value="<?=$f["#value"]?>" 
                                size="<?=$f['#size']?>" 
                                maxlength="<?=$f["#maxlength"]?>" 
                                <?if(is_array($f['#attributes'])):
                                    foreach($f['#attributes'] as $attr_name => $attr_value):
                                        echo " ".$attr_name."=".$attr_value;
                                    endforeach;
                               endif;?>
                                class="form-text">
                        </div>
                   <? elseif($f['#type'] == "select" && is_array($f['#options'])):?>
                        <div class="form-item form-type-select form-item-country">
                           
                            <select id="edit-country" name="<?=$f_name?>" class="form-select">
                                <? foreach ($f['#options'] as $id => $value):?>
                                    <option value="<?=$id?>" <? ($f['#value'] == $id) ? print("selected") : false; ?> ><?=$value?></option>
                                <? endforeach;?>
                            </select>
                        </div>
                    <? endif?>
                <? endif?>
            <? endforeach;?>
            <input type="submit" id="edit-submit" name="op" value="<?=$variables["form"]['submit']["#value"]?>" class="form-submit">
            <input type="hidden" name="form_build_id" value="<?=$variables["form"]['form_build_id']["#value"]?>">
            <input type="hidden" name="form_token" value="<?=$variables["form"]["form_token"]["#value"]?>">
            <input type="hidden" name="form_id" value="<?=$variables["form"]['form_id']["#value"]?>">
        </div>
    </form> 
</div>
