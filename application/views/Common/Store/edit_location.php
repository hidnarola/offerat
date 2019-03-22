<?php
if ($this->loggedin_user_type == COUNTRY_ADMIN_USER_TYPE) {
    $user_url_prefix = 'country-admin';
} else if ($this->loggedin_user_type == STORE_OR_MALL_ADMIN_USER_TYPE) {
    $user_url_prefix = 'mall-store-user';
}
?>

<form method="POST" action="<?= base_url($user_url_prefix . '/stores/location/update') ?>">
    <input type="hidden" name="id_store_location" value="<?= $store_locations_list['id_store_location'] ?>">
    <input type="hidden" name="id_store" value="<?= $store_locations_list['id_store'] ?>">
    <?php if ($store_locations_list['location_type'] == 0) { ?>
        <div class="form-group">
            <label for="id_location" class="col-form-label">Mall:</label>
            <select name="id_location" id="id_location" class="select form-control">
                <option value="">Select Mall</option>
                <?php foreach ($mall_list as $list) { ?>
                    <option value="<?php echo $list['id_mall']; ?>" <?php echo ($list['id_mall'] == $store_locations_list['id_location']) ? 'selected=selected' : ''; ?>><?php echo $list['mall_name']; ?></option>
                <?php } ?>
            </select>
        </div>
    <?php } else { ?>
        <div class="form-group">
            <label for="branch-name" class="col-form-label">Branch Name:</label>
            <input type="text" class="form-control" id="branch-name" name="branch_name" value="<?= (!empty($store_locations_list['branch_name'])) ? $store_locations_list['branch_name'] : '' ?>">
        </div>
    <?php } ?>
    <div class="form-group">
        <label for="latitude" class="col-form-label">Latitude:</label>
        <input type="text" class="form-control" id="latitude" name="latitude" value="<?= (!empty($store_locations_list['latitude'])) ? $store_locations_list['latitude'] : '' ?>">
    </div>
    <div class="form-group">
        <label for="longitude" class="col-form-label">Longitude:</label>
        <input type="text" class="form-control" id="longitude" name="longitude" value="<?= (!empty($store_locations_list['longitude'])) ? $store_locations_list['longitude'] : '' ?>">
    </div>
    <div class="form-group">
        <label for="contact_number" class="col-form-label">Contact Number 1:</label>
        <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?= (!empty($store_locations_list['contact_number'])) ? $store_locations_list['contact_number'] : '' ?>">
    </div>
    <div class="form-group">
        <label for="contact_number_1" class="col-form-label">Contact Number 2:</label>
        <input type="text" class="form-control" id="contact_number_1" name="contact_number_1" value="<?= (!empty($store_locations_list['contact_number_1'])) ? $store_locations_list['contact_number_1'] : '' ?>">
    </div>
    <div class="form-group">
        <label for="contact_number_2" class="col-form-label">Contact Number 3:</label>
        <input type="text" class="form-control" id="contact_number_2" name="contact_number_2" value="<?= (!empty($store_locations_list['contact_number_2'])) ? $store_locations_list['contact_number_2'] : '' ?>">
    </div>
    <div class="form-group">
        <label for="email" class="col-form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= (!empty($store_locations_list['email'])) ? $store_locations_list['email'] : '' ?>">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>