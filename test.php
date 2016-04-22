<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <title>StockFu</title>
</head>

<style type="text/css">
    #stock{
        /* Rectangle 1: */
        background-image: linear-gradient(-180deg, #151515 0%, #6F6F6F 100%);
        border-style: solid;
        border-width: 5px;
        border-color: white;
    }
    #stock:after{
        content: '';
        height: 100%;
        position: absolute;
        width: 10%;
        background: green;
        top: 0;
        bottom: 0;
        right: 0;
    }
    .symbol, .company, .dates, .multi-symbol, .name{
        color: white;
    }
    .symbol{
        font-size: 75px;
    }
    .multi-symbol{
        font-size: 40px;
    }
</style>

<body>
	<div class="container">
        <div class="row">
            <div class="col-md-4" id="stock">
                <h1 class="symbol">AAPL</h1>
                <p class="company">Apple Inc.</p>
                <p class="dates">Jan. 4, 2000 - Present</p>
            </div>
            <div class="col-md-4" id="stock">
                <h1 class="symbol">GOOG</h1>
                <p class="company">Alphabet Inc.</p>
                <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
            </div> 
            <div class="col-md-4" id="stock">
                <h1 class="symbol">CMCSA</h1>
                <p class="company">Comcast Corp.</p>
                <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <h1 class="symbol">NASDAQ</h1>
                <p class="company">Nasdaq Inc.</p>
                <p class="name">How well my portfolio is going</p>
                <p class="dates">This Week</p>
            </div>
            <div class="col-md-4" id="stock">
                <h1 class="multi-symbol">CMCSA</h1>
                <h1 class="multi-symbol">GOOG</h1>
                <p class="company">Comcast Corp. & Alphabet Inc.</p>
                <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
            </div> 
            <div class="col-md-4" id="stock">
                <h1 class="symbol">CMCSA</h1>
                <p class="company">Comcast Corporation</p>
                <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-4" id="stock">
                <h1 class="symbol">NASDAQ</h1>
                <p class="company">Nasdaq Inc.</p>
                <p class="dates">This Week</p>
            </div>
            <div class="col-md-4" id="stock">
                <h1 class="multi-symbol">F</h1>
                <h1 class="multi-symbol">GM</h1>
                <p class="company">Ford Motor Co. & General Motors Co.</p>
                <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
            </div> 
            <div class="col-md-4" id="stock">
                <h1 class="symbol">CMCSA</h1>
                <p class="company">Comcast Corporation</p>
                <p class="dates">Aug. 19, 2004 - Aug. 19, 2014</p>
            </div>  
        </div>    
    </div>

    <?php
        //Example of getting start date from FB stock data up to yesterday
        $json = file_get_contents("https://www.quandl.com/api/v3/datasets/WIKI/AAPL.json?end_date=2015-05-28&api_key=KDzspapgf7Mv2zbUmTgd");
        $ob = json_decode($json);
        $dataset = $ob -> dataset;
        $companySymbol = $dataset -> dataset_code;
        $startDate = $dataset -> start_date;
        $endDate = $dataset -> end_date;
        $companyName = $dataset -> name;
        print("<div class=\"row\">
            <div class=\"col-md-4\" id=\"stock\">
                <h1 class=\"symbol\">$companySymbol</h1>
                <p class=\"company\">$companyName</p>
                <p class=\"name\">Example Name</p>
                <p class=\"dates\">$startDate - $endDate/p>
            </div>
            </div>");
    ?>

    <script type="text/javascript">
    //Same example using Ajax/JQuery
    $.getJSON("https://www.quandl.com/api/v3/datasets/WIKI/AAPL.json?end_date=2015-05-28&api_key=KDzspapgf7Mv2zbUmTgd", function(result){
        var data = result["dataset"];
        var startDate = data["start_date"];
        var endDate = data["end_date"];
        var companySymbol = data["dataset_code"];
        var companyName = data["name"];
        var newStock =  '<div class="col-md-4" id="stock"><h1 class="symbol">' + companySymbol + '</h1><p class="company">' + companyName + '</p><p class="name">Example Name JQuery</p><p class="dates">' + startDate + " - " + endDate + '</p></div>';
        $("body").append(newStock);
    });
    </script>
    <footer>
        <!-- Tell people that this is my website do not steal -->
        <div id="copyright">
            Copyright &copy; 2016 Kevin Guo. All rights reserved.
        </div>
	</footer> 
    
</body>
</html>