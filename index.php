


<html lang="en">
<head>
  <meta charset="utf-8">

  <title>CULTURE LAB - Daily L3 Lift Report</title>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87285269-2', 'auto');
    ga('send', 'pageview');
  </script>
  
  <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
  <script type="text/javascript" src="./node_modules/jquery/dist/jquery.min.js"></script>
  <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

  <style>
    @font-face {
      font-family: gothbook;
      src: url("./fonts/GothamNarrow-Book.otf")
    }
    @font-face {
      font-family: gothmed;
      src: url("./fonts/GothamNarrow-Medium.otf")
    }

    body{font-family: gothbook;}

    table{
      font-size: 0.9em;
          //border-top: solid 1px;
    }

    thead{
      border-bottom: solid 1px grey;
      //font-size: 0.8em;
      color: grey;
    }

    .timecol{
      width:5em;
      text-align: left;
    }

    .progname{
      width: 17em;
    }
    .epname{
      width: 15em;
    }
    .column-data{
      width: 6em;
      text-align: right;
    }

    .column-diff{
      width: 4em;
      text-align: center;
      border-right: solid 1px;
      color: grey;
    }

  </style>

  <?php
   header("Access-Control-Allow-Origin: *");
   ?>

  <script>

    var makecall = function(tag, data){
      $.ajax({
          url: baseurl+"telecasts.php/getliftreport",
          xhrFields: {
              withCredentials: true
           },
          crossDomain: true,
          data: data
        }).done(function(dat) {
          //console.log("Loading table data! ", tag, dat);
          var htmlcode = "<div><h3>"+tag+"</h3><table>";
          htmlcode += "<thead><tr><td class='timecol'>start</td> \
                        <td class='progname'>program</td> \
                        <td class='epname'>episode</td>";

          for (var b = 0; b < demos.length; b++) {
            for (var c = 0; c < streams.length; c++) {
              htmlcode+= "<td class='column-data'>"+streams[c]+"-"+demos[b]+"</td>";
            }
              htmlcode+= "<td class='column-diff'>%lift</td>";
          }
          htmlcode += "</tr></thead>";


          var d, h, m;
          for (var i = 0; i < dat.length; i++) {
            d=new Date(dat[i]['date_time'])
            h=d.getHours()
            h = ((h + 11) % 12 + 1);
            m =('0'+d.getMinutes()).slice(-2);

            htmlcode += "<tr> \
                          <td class='timecol'>"+h+":"+m+"</td> \
                          <td class='progname'>"+dat[i]['concat_name']+"</td> \
                          <td class='epname'>"+dat[i]['telecast_episode']+"</td>";

            for (var b = 0; b < demos.length; b++) {
              for (var c = 0; c < streams.length; c++) {
                htmlcode+= "<td class='column-data'>"+dat[i][metric+"-"+streams[c]+"-"+demos[b]]+"</td>";
              }
                htmlcode+= "<td class='column-diff'>"+Math.round(((dat[i][metric+"-l3d-"+demos[b]]-dat[i][metric+"-lsd-"+demos[b]])/dat[i][metric+"-lsd-"+demos[b]])*100)+"%</td>";
            }



            htmlcode += "</tr>";
          }

          htmlcode += "</table></div>";
          $(".dynamic-sect").append(htmlcode);

        });
    }


    var subclick = function(e){
      //grey out submit button
      var params = null;
      $(".dynamic-sect").empty();

      console.log("button clicked!!", params);
      for (var i = 0; i < nets.length; i++) {
        currnet = nets[i];
        params = {
          starttime: $(".date-select").val(),
          demos: demos,
          net: nets[i]
        }

        makecall(nets[i], params);
      }
    }

    var dropchange = function(e){
      console.log("drop down changed");
    }

    var nets = null;
    var demos = null;
    var streams = ["lsd", "l3d"];
    var metric = "aa";
    //var baseurl = "http://localhost/api-tvratings-phpslim/";
    var baseurl = "http://dctrydatrk01.discovery.com/api/";

    $(document).ready(function(){
      console.log("App is ready!");
      nets = <?php echo json_encode($_GET['nets']); ?>;
      demos = <?php echo json_encode($_GET['demos']); ?>;
      console.log(nets);
      console.log(demos);

      $.ajax({
          url: baseurl+"telecasts.php/getdates",
          context: document.body
        }).done(function(dat) {
          console.log("Dates are back! ", dat);
          htmlcode = '<select onchange="dropchange()" class="date-select">';


          for (var i = 0; i < dat.length; i++) {
            htmlcode += '<option value="'+dat[i]['date']+'">'+dat[i]['date']+'</option>';
            dat[i]
          }

          htmlcode += '</select>';
          htmlcode += '<button onclick="subclick()" type="button" class="btn btn-default">Submit</button>';

          $(".drop-sect").html(htmlcode);
        });

    });
  </script>

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>
  <div class="container">
    <div class="row">
      <h3>CULTURE LAB - Daily L3 Lift Report</h3>
    </div>

    <div class="row drop-sect">
      Drop Down Box
    </div>

    <div class="row dynamic-sect">
      Dynamic Content Section
    </div>

  </div>

</body>
</html>
