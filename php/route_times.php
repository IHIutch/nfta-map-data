<?php include "../nfta_functions.php"?>
<?php $stop = getTimes();?>

<h1 class="h3">
    <?php echo $stop['stop_name']?>
</h1>
<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="inbound-tab" data-toggle="pill" href="#inbound" role="tab" aria-controls="pills-home" aria-selected="true">Inbound</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="outbound-tab" data-toggle="pill" href="#outbound" role="tab" aria-controls="pills-profile" aria-selected="false">Outbound</a>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="inbound" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="border-top">
            <?php foreach($stop['route_info'] as $v){ ?>
            <div class="border-bottom p-1 d-flex justify-content-between align-items-center">
                <span class="badge" style="background-color: <?php echo $v['route_color']?>">
                    <?php echo $v['route_name']?>
                </span>
                <?php echo $v['time'];?>
            </div>
            <?php }; ?>
        </div>
    </div>
    <div class="tab-pane fade" id="outbound" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="border-top">
            <div class="border-bottom p-2 text-right">9:00 AM</div>
            <div class="border-bottom p-2 text-right">10:00 AM</div>
            <div class="border-bottom p-2 text-right">11:00 AM</div>
            <div class="border-bottom p-2 text-right">12:00 PM</div>
            <div class="border-bottom p-2 text-right">1:00 PM</div>
            <div class="border-bottom p-2 text-right">2:00 PM</div>
            <div class="border-bottom p-2 text-right">3:00 PM</div>
            <div class="border-bottom p-2 text-right">4:00 PM</div>
            <div class="border-bottom p-2 text-right">5:00 PM</div>
        </div>
    </div>
</div>
