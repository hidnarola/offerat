<?php if (isset($sub_category_list) && sizeof($sub_category_list) > 0) { ?><option value="">Select Sub Category</option><?php foreach ($sub_category_list as $cat) { ?><option value="<?php echo $cat['id_sub_category']; ?>"><?php echo $cat['sub_category_name']; ?></option><?php
    }
}
?>