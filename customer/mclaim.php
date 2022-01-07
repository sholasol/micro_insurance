<!DOCTYPE html>
<html>
    <head>
        
        <!-- Title -->
        <title>Claim Process</title>
        
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta charset="UTF-8">
        <meta name="description" content="Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
        <link href="../res/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet"/>
        <link href="../res/plugins/uniform/css/uniform.default.min.css" rel="stylesheet"/>
        <link href="../res/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../res/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="../res/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>	
        <link href="../res/plugins/offcanvasmenueffects/css/menu_cornerbox.css" rel="stylesheet" type="text/css"/>	
        <link href="../res/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>	
        <link href="../res/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
        <link href="../res/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>	
        <link href="../res/plugins/slidepushmenus/css/component.css" rel="stylesheet" type="text/css"/>
        <link href="../res/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
        
        <!-- Theme Styles -->
        <link href="../res/css/modern.css" rel="stylesheet" type="text/css"/>
        <link href="../res/css/themes/white.css" class="theme-color" rel="stylesheet" type="text/css"/>
        <link href="../res/css/custom.css" rel="stylesheet" type="text/css"/>
        
        <script src="../res/plugins/3d-bold-navigation/js/modernizr.js"></script>
        <script src="../res/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <?php 
    include 'nav.php';
    $pol= check_input($_GET['policy']);
    
    $pl=$con->query("SELECT * FROM transaction WHERE policy='$pol'");
    $r=$pl->fetch_array();

    $pro=$r['product'];
    $plan=   $r['plan'];
    $prm=   $r['premium'];
    $sum=   $r['sum_assured'];
    $insurance=   $r['insurance_id'];
    $part=   $r['partner_id'];
    //$mkt = $r['marketer_id'];
    $cAmt = $r['sum_assured'];

    $pr=$con->query("SELECT product_name, product_id FROM product_setup WHERE product_id='$pro'");
    $rw=$pr->fetch_array();
    $pr_id=$rw['product_id'];
    $prdName=$rw['product_name'];
    
    $pln=$con->query("SELECT * FROM plan_setup WHERE plan_id='$plan'");
    $row=$pln->fetch_array();
    
    $chk =$con->query("SELECT count(claim_id) AS claim FROM claims WHERE policy='$pol'");
    $rv=$chk->fetch_array();
    $cnt=$rv['claim'];
    
    
    if($cnt > 0){
       $e="There already exist a claim on this $pol policy. Edit claim "; 
       echo  " <script>alert('$e'); window.location='eclaim.php?policy=$pol' </script>"; 
    }else{
        
    
    
    
    if(isset($_POST['submit'])){
        if(empty($_POST['name'])){
        $e="Please fill in your full name!"; 
        echo  " <script>alert('$e'); window.location='mclaim.php?policy=$pol'</script>";
	}
        elseif(empty($_POST['policy'])){
        $e="Please fill in the policy number"; 
        echo  " <script>alert('$e'); window.location='mclaim.php?policy=$pol'</script>";
	}
        elseif(empty($_POST['date'])){
        $e="Please fill in the incident date "; 
        echo  " <script>alert('$e'); window.location='mclaim.php?policy=$pol'</script>";
	}
        elseif(empty($_POST['nature'])){
        $e="Please fill in nature of loss"; 
        echo  " <script>alert('$e'); window.location='mclaim.php?policy=$pol'</script>";
	}
        elseif(empty($_POST['naration'])){
        $e="Please briefly narate nature of loss"; 
        echo  " <script>alert('$e'); window.location='mclaim.php?policy=$pol'</script>";
	}else{
           $usr = check_input($_POST["usr"]); 
           $prd = check_input($_POST["prd"]);
           $poly = check_input($_POST["poly"]);
           $claim = check_input($_POST["claim"]);
           $nature = check_input($_POST["nature"]);
           $naration = check_input($_POST["naration"]);
           $date = check_input($_POST["date"]);
           
          //Getting marketer_id
           $kt=$con->query("SELECT marketer_id FROM transaction WHERE policy='$pol'");
           $ro=$kt->fetch_array();
           $mkt =$ro['marketer_id'];
            
            //Converting date picker from string to date e.g 12/11/2018
            $time = strtotime($date);
            $newformat = date('Y-m-d',$time);
            $dat=  $newformat;
            
            $clm=$con->query("INSERT INTO claims SET insurance_id='$insurance', partner_id='$part', marketer_id ='$mkt', user_id='$usr', policy='$poly',product='$prd', plan='$plan', premium='$prm', sum_assured='$sum', notif_date=now(), incident_date ='$dat', claim_amount ='$claim', nature_of_loss='$nature', narration='$naration' ");
            if($clm){
                $e="Your claim has been lodged"; 
                echo  " <script>alert('$e'); window.location='home.php' </script>";
            } else {
                $e="Processing error! Please try again"; 
                echo  " <script>alert('$e'); window.location='mclaim.php?policy=$pol' </script>";
            }
        }
        
    }
    
    }
    
    function check_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
    ?>
         
            <div class="page-inner">
                <div class="page-title">
                    <h3>Claim Process</h3>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="home.php">Home</a></li>
                            <li><a href="#">Claim Process</a></li>
                        </ol>
                    </div>
                </div>
                <div id="main-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    <div id="rootwizard">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-briefcase m-r-xs"></i>Claim Process</a></li>
                                        </ul>
                          
                                    
                                        <div class="progress progress-sm m-t-sm">
                                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                            </div>
                                        </div>
                                        <?php 
                                        
                                        ?>
                                        <form id="wizardForm" method="post" action="">
                                            <div class="tab-content">
                                                <div class="tab-pane active fade in" id="tab1">
                                                    <div class="row m-b-lg">
                                                        <div class="col-md-10">
                                                            <input type="hidden" name="prd" value="<?php echo $pr_id; ?>" />
                                                            <input type="hidden" name="usr" value="<?php echo $uid; ?>" />
                                                            <input type="hidden" name="poly" value="<?php echo $pol; ?>" />
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputName">Customer Name</label>
                                                                    <input type="text" class="form-control" name="name" id="exampleInputName" value="<?php echo $name; ?>">
                                                                </div>
<!--                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputName">Phone</label>
                                                                    <input type="text" class="form-control" name="phone" id="exampleInputName" value="<?php echo $phone; ?>" required/>
                                                                </div>-->
                                                                 <div class="form-group  col-md-6">
                                                                    <label for="exampleInputName2">Product Name</label>
                                                                    <input type="text" class="form-control col-md-6" name="product" id="exampleInputName2" value="<?php echo $prdName; ?>"  required />
                                                                </div>

                                                                <div class="form-group  col-md-6">
                                                                    <label for="exampleInputName2">Policy Number</label>
                                                                    <input type="text" class="form-control col-md-6" name="policy" id="exampleInputName2" value="<?php echo $pol; ?>" required />
                                                                </div>
                                                                
                                                                <div class="form-group  col-md-6">
                                                                    <label for="exampleInputName2">Claim Amount</label>
                                                                    <input type="number" class="form-control col-md-6" name="claim" id="exampleInputName2" value="<?php echo $cAmt;?>"  required/>
                                                                </div>
<!--                                                                <div class="form-group  col-md-6">
                                                                    <label for="exampleInputName2">Sum Assured</label>
                                                                    <input type="number" class="form-control col-md-6" name="assured" id="exampleInputName2" value="" />
                                                                </div>-->
                                                                <div class="form-group  col-md-6">
                                                                    <label for="exampleInputName2">Incident Date</label>
                                                                    <input type="text" class="form-control col-md-6 date-picker" name="date" id="exampleInputName2"  required />
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail">Nature of Loss</label>
                                                                    <textarea class="form-control" name="nature" id="exampleInputEmail" rows="4" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail">Narration of Loss</label>
                                                                    <textarea class="form-control" name="naration" id="exampleInputEmail" rows="4" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                 <button type="submit" name="submit" class="btn btn-success pull-right">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- Row -->
                </div><!-- Main Wrapper -->
                <div class="page-footer">
                    <p class="no-s">2018 &copy; ITH <span class="pull-right">Powered By IT Horizons.</span></p>
                </div>
            </div><!-- Page Inner -->
        </main><!-- Page Content -->
        <nav class="cd-nav-container" id="cd-nav">
            <header>
                <h3>Navigation</h3>
                <a href="#0" class="cd-close-nav">Close</a>
            </header>
            <ul class="cd-nav list-unstyled">
                <li class="cd-selected" data-menu="index">
                    <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-home"></i>
                        </span>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li data-menu="profile">
                    <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-user"></i>
                        </span>
                        <p>Profile</p>
                    </a>
                </li>
                <li data-menu="inbox">
                    <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-envelope"></i>
                        </span>
                        <p>Mailbox</p>
                    </a>
                </li>
                <li data-menu="#">
                    <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-tasks"></i>
                        </span>
                        <p>Tasks</p>
                    </a>
                </li>
                <li data-menu="#">
                    <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-cog"></i>
                        </span>
                        <p>Settings</p>
                    </a>
                </li>
                <li data-menu="calendar">
                    <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <p>Calendar</p>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="cd-overlay"></div>
	

        <!-- Javascripts -->
        <script src="../res/plugins/jquery/jquery-2.1.3.min.js"></script>
        <script src="../res/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="../res/plugins/pace-master/pace.min.js"></script>
        <script src="../res/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../res/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="../res/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="../res/plugins/switchery/switchery.min.js"></script>
        <script src="../res/plugins/uniform/jquery.uniform.min.js"></script>
        <script src="../res/plugins/offcanvasmenueffects/js/classie.js"></script>
        <script src="../res/plugins/offcanvasmenueffects/js/main.js"></script>
        <script src="../res/plugins/waves/waves.min.js"></script>
        <script src="../res/plugins/3d-bold-navigation/js/main.js"></script>
        <script src="../res/plugins/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
        <script src="../res/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../res/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="../res/js/modern.min.js"></script>
        <script src="../res/js/pages/form-wizard.js"></script>
        
    </body>
</html>