<option value="0">Only Shop</option><?php if (isset($mall_list) && sizeof($mall_list) > 0) { ?><?php foreach ($mall_list as $list) { ?><option value="<?php echo $list['id_mall']; ?>"><?php echo $list['mall_name'] . ' Mall'; ?></option><?php
    }
}
?>