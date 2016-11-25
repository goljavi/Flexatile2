<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if(isset($item_title)){echo $item_title,' | ';} if(isset($title)){echo $title,' | ';} ?>  Flores Argentina</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?=base_url()?>public-bootstrap-css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url()?>public-bootstrap-css/font-awesome.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?=base_url()?>public-bootstrap-css/owl.carousel.css">
    <link rel="stylesheet" href="<?=base_url()?>public-bootstrap-css/style.css">
    <link rel="stylesheet" href="<?=base_url()?>public-bootstrap-css/responsive.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
   
    <div class="header-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="user-menu">
                        <ul>
                            <li><i class="fa fa-phone"></i> (011) 46027953</li>
                            <li><i class="fa fa-whatsapp"></i> 1162176823</li>
                            <li><i class="fa fa-map-marker"></i> Montiel 3912 - Lugano</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="header-right">
                    <div id="jm-top-bar">
                        <div id="jm-top-bar1" class="clearfix">
                            <div class="jm-module-raw  hidden-phone">
                                <ul class="nav menu">
                                    <li><a href="/quienes-somos">Quienes somos</a></li>
                                    <li><a href="/como-comprar">¿Como comprar?</a></li>
                                    <li><a href="/contacto">Contacto</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- End header area -->
    
    <div class="site-branding-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="logo">
                        <h1><a href="<?=base_url()?>"><img src="<?=base_url()?>images/logo.png"></a></h1>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End site branding area -->
    
    <?=Modules::run('store_categories/_draw_top_navbar')?>
         
        <?php 
            if(isset($view_file) && isset($view_module))
            {
              $this->load->view($view_module.'/'.$view_file);
            }
            else
            {
              $this->load->view('index/index');
            }
        ?>
    
    <div class="footer-bottom-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="footer-ul user-menu">
                        <ul>
                            <li><i class="fa fa-phone"></i> (011) 46027953</li>
                            <li><i class="fa fa-whatsapp"></i> 1162176823</li>
                            <li><i class="fa fa-map-marker"></i> Montiel 3912 - Lugano</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="header-right">
                    <div id="jm-top-bar">
                        <div id="jm-top-bar1" class="clearfix">
                            <div class="jm-module-raw  hidden-phone">
                                <ul class="nav menu footer">
                                    <li><a href="/quienes-somos">Quienes somos</a></li>
                                    <li><a href="/como-comprar">¿Como comprar?</a></li>
                                    <li><a href="/contacto">Contacto</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="copyright">
                        <p>&copy; Tu comercio</p>
                        <a target="_blank" href="http://www.alternativab.com">Desarrollado por Alternativa B</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End footer bottom area -->
   
    <!-- Latest jQuery form server -->
    <script src="https://code.jquery.com/jquery.min.js"></script>
    
    <!-- Bootstrap JS form CDN -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <!-- jQuery sticky menu -->
    <script src="<?=base_url()?>js/owl.carousel.min.js"></script>
    <script src="<?=base_url()?>js/jquery.sticky.js"></script>
    
    <!-- Main Script -->
    <script src="<?=base_url()?>js/main.js"></script>
    <script>
    $(document).ready(function(){$('form.jsform').on('submit', function(form){form.preventDefault(); $.post('<?=base_url()?>contacto/submit', $(this).serialize(), function(data){$('div.jsError').html(data);});});});
    </script>
  </body>
</html>