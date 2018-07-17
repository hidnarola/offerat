<div class="content">
    <div class="row">
        <div class="col-lg-4">            
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Not Verified Stores List</h5>
                    <a class="heading-elements-toggle"><i class="icon-more"></i></a>
                </div>                
                <div class="table-responsive">
                    <?php $this->load->view('Countryadmin/Dashboard/store_list'); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Notifications List <span class="label label-flat border-pink text-pink-600">Expire Date not defined</span></h6>
                    <a class="heading-elements-toggle"><i class="icon-more"></i></a>
                </div>
                <div class="table-responsive">
                    <?php $this->load->view('Countryadmin/Dashboard/offer_list'); ?>
                </div>
            </div>
        </div>
    </div>    
</div>