<html>

<head>
    <title>NFTA Bus Routes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</head>

<body>
    <?php include "nfta_functions.php"?>
    <?php $stop = getTimes();?>

    <div class="container-fluid w-100 h-100">
        <div class="row">
            <div class="col-3">
                <div class="p-3">
                    <div class="card p-3 shadow">
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
                                    <?php foreach($stop['times'] as $v){ ?>
                                    <div class="border-bottom p-2  d-flex justify-content-between align-items-center">
                                        <span class="badge badge-warning">
                                            <?php echo $stop['route_name']?>
                                        </span>
                                        <?php echo $v;?>
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
                    </div>
                </div>
            </div>
            <div class="col-3">
                <?php $theStops = getStops(); 
                    foreach($theStops as $s){ ?>
                <div>
                    <a href="nfta.php?stop_id=<?php echo $s['id']?>">
                        <?php echo $s['stop_name']?>
                    </a>
                </div>
                <?php } ?>
            </div>
            <!--
            <div class="col-3">
                <div class="p-3">
                    <div class="card p-3 shadow">
                        <h1 class="h3">Stop #4321</h1>
                        <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="inbound-tab2" data-toggle="pill" href="#inbound2" role="tab" aria-controls="pills-home" aria-selected="true">Inbound</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="outbound-tab2" data-toggle="pill" href="#outbound2" role="tab" aria-controls="pills-profile" aria-selected="false">Outbound</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="inbound2" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="border-top">
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-success">Route #1234</div>
                                        <div>9:30 AM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-warning">Route #4321</div>
                                        <div>10:30 AM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-success">Route #1234</div>
                                        <div>11:30 AM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-warning">Route #4321</div>
                                        <div>12:30 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-success">Route #1234</div>
                                        <div>1:30 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-warning">Route #4321</div>
                                        <div>2:30 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-success">Route #1234</div>
                                        <div>3:30 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-warning">Route #4321</div>
                                        <div>4:30 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-success">Route #1234</div>
                                        <div>5:30 PM</div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="outbound2" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="border-top">
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-primary">Route #1234</div>
                                        <div>9:00 AM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-danger">Route #4321</div>
                                        <div>10:00 AM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-primary">Route #1234</div>
                                        <div>11:00 AM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-danger">Route #4321</div>
                                        <div>12:00 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-primary">Route #1234</div>
                                        <div>1:00 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-danger">Route #4321</div>
                                        <div>2:00 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-primary">Route #1234</div>
                                        <div>3:00 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-danger">Route #4321</div>
                                        <div>4:00 PM</div>
                                    </div>
                                    <div class="border-bottom p-2 d-flex justify-content-between align-items-center">
                                        <div class="badge badge-primary">Route #1234</div>
                                        <div>5:00 PM</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
-->
        </div>
    </div>
</body>

</html>
