var companyMap;
var reversedMap;
var companyNames = [];



var chart;
var svgChildren;
var stock1Name = "GOOGL";
var stock2Name = "YHOO"
var curDate = new Date();
var endDate = '&end_date=' + curDate.toISOString().substring(0, 10);
var sDate = (new Date());
sDate.setUTCFullYear((curDate.getUTCFullYear() - 1));
startDate = 'start_date=' + sDate.toISOString().substring(0, 10);
var chartName = "Chart Name";
var priceOption = 1;

var stockData;
var data;
var demo;
var maxDate;
var minDate;
var stockData2;


//setup before functions
var typingTimer; //timer identifier
var doneTypingInterval = 1000; //time in ms, 5 second for example



var APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';


function refreshChart(APICall) {
    d3.select("svg").remove();
    d3.select("#main").append("svg").attr("width", 1000).attr("height", 500).attr("id", "newChart");
    chartCreation(APICall);
}

function returnCompanyMap(callback) {
    $.ajax({
        type: "GET",
        url: "WIKI-datasets-codes.csv",
        dataType: "text",
        success: function(data) {

            // Generates map of company names to company codes, and reversed map
            var companyMap = new Map();
            var reversedMap = new Map();
            var result = $.csv.toArrays(data);
            for (i = 0; i < result.length; i++) {
                companyMap.set(result[i][0].split("/")[1], result[i][1].split(" (")[0]);
                reversedMap.set(result[i][1].split(" (")[0], result[i][0].split("/")[1]);

            }

            // Generates array of company names to be used for autocompletion
            var companyNames = [];
            for (i = 0; i < result.length; i++) {
                companyNames.push(result[i][1].split(" (")[0]);
            }

            // Callback allows global variables to be set to appropriate maps after completion of the Ajax call
            callback(companyMap, reversedMap, companyNames);
            $("#stock1").autocomplete({
                source: companyNames
            });
            $("#stock2").autocomplete({
                source: companyNames
            });
        }
    });
}


function getSecondStockData(callback) {
    if (stock2 != "") {
        stock2 = reversedMap.get(stock2);
        console.log("test 1.5");
        var secondAPICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock2 + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
        $.getJSON(secondAPICall, function(result2) {
            console.log("test 2");
            var data2 = result2["dataset"];
            stockData2 = data2["data"];
            for (i = 0; i < stockData2.length; i++) {
                if (stockData2[i][priceOption] > priceYMax) {
                    priceYMax = stockData2[i][priceOption];
                }
            }
            console.log("WELL HERE I AM");
            stock2Completed = true;
            callback(stockData2, stock2Completed);
        }).fail(function(jqxhr) {
            alert("The data you inputted was invalid - please choose a company from the autocomplete feature");
            stock2Completed = true;
            callback(stockData2, stock2Completed);
        });
    } else {
        callback([], true);
    }
}


function chartCreation(APICall) {

    $.getJSON(APICall).done(function(result) {
        data = result["dataset"];
        stockData = data["data"];
        demo = d3.select("#newChart");
        maxDate = new Date(stockData[0][0]);
        minDate = new Date(stockData[stockData.length - 1][0]);
        stockData2;

        // Sets height, width, and margins for the new chart
        var height = 400;
        var width = 900;
        var margins = {
            top: 20,
            right: 20,
            bottom: 20,
            left: 50
        };

        // Computes maximum value of y axis based on highest stock price
        var priceYMax = stockData[0][priceOption];
        for (i = 0; i < stockData.length; i++) {
            if (stockData[i][priceOption] > priceYMax) {
                priceYMax = stockData[i][priceOption];
            }
        }


        // Sets up x and y axis
        var xScale = d3.time.scale().range([margins.left, width - margins.right]).domain([minDate, maxDate]);
        var yScale = d3.scale.linear().range([height - margins.top, margins.bottom]);

        // Adds line chart for second company, if not empty
        // TD: Even if second API call fails, this still executes first one.
        console.log("Test 1");

        //getSecondStockData()


        function displaychart() {
            console.log("callback completed");
            priceYMax += 20;
            yScale.domain([0, priceYMax]);

            var xAxis = d3.svg.axis().scale(xScale).ticks(8);
            var yAxis = d3.svg.axis().scale(yScale).orient("left");
            var formatTime = d3.time.format("%e %B");
            // Orients axes
            demo.append("svg:g").attr("class", "axis").attr("transform", "translate(0," + (height - margins.bottom) + ")").call(xAxis);
            demo.append("svg:g").attr("class", "axis").attr("transform", "translate(" + margins.left + ",0)").call(yAxis);

            // var div = demo.append("div").attr("class", "tooltip").style("opacity", 0);

            // Generates lines using open stock price
            var lineGen = d3.svg.line()
                .x(function(d) {
                    return xScale(new Date(d[0]));
                })
                .y(function(d) {
                    return yScale(d[priceOption]);
                })
                .interpolate("basis");

            // Appends line chart to svg with dank attributes
            demo.append('svg:path')
                .attr('d', lineGen(stockData))
                .attr("id", "lineChart")
                .attr('stroke', 'green')
                .attr('stroke-width', 2)
                .attr('fill', 'none');


            /*.on("mousemove", mMove)
            .append("title");

            function mMove() {
                var m = d3.svg.mouse(this);
                d3.select("#lineChart").select("title").text(m[1]);
            }*/
            // if (stock2 != "") {
            //     demo.append('svg:path')
            //         .attr('d', lineGen(stockData2))
            //         .attr('stroke', 'blue')
            //         .attr('stroke-width', 2)
            //         .attr('fill', 'none');
            // }

            // Adds x-axis label
            demo.append("text").attr("x", width / 2).attr("y", height + 30).style("text-anchor", "middle").style("font-size", 16).style("font-family", "Lato").text("Date");

            // Adds y-axis label
            demo.append("text").attr("transform", "rotate(-90)").attr("y", 10).attr("x", -height / 2).style("text-anchor", "middle").style("font-family", "Lato").text("Open Stock Price (in USD)");

            demo.append("text")
                .attr("x", (width / 2))
                .attr("y", 0 - (margins.top / 2))
                .attr("text-anchor", "middle")
                .attr("id", "charTitle")
                .style("font-size", "16px")
                .style('fill', "black")
                .style("text-decoration", "underline")
                .text(chartName);

            // Gets HTML representation of svg element
            svgChildren = document.getElementById("newChart").outerHTML;

        }
        displaychart();
    });
};



$(function() {

    // Adds datepicker calendar feature to the following input fields
    $("#startDatePicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        onSelect: function(selected, inst) {
            // Ensures that end date is after start date
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#endDatePicker").datepicker("option", "minDate", dt);
            startDate = 'start_date=' + dt.toISOString().substring(0, 10);
            APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
            refreshChart(APICall);

        }
    });
    $("#endDatePicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        onSelect: function(selected, inst) {
            // Ensures that start date is before end date
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#startDatePicker").datepicker("option", "maxDate", selected);
            endDate = '&end_date=' + dt.toISOString().substring(0, 10);
            APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
            refreshChart(APICall);
        }
    });

    // Reads csv of company data and generates maps of company codes to company names

    // Sets global variables, as well as flag to indicate Ajax call has completed
    returnCompanyMap(function(map, reversed, names) {
        companyMap = map;
        reversedMap = reversed;
        companyNames = names;
    });


    chartCreation(APICall);


    //on keyup, start the countdown
    $('#chartName').keyup(function() {
        clearTimeout(typingTimer);
        if ($('#chartName').val) {
            typingTimer = setTimeout(updateName, doneTypingInterval);
        }
    });

    //user is "finished typing," do something
    function updateName() {
        //Check if valid company name or symbo
        input = $('#chartName').val();
        if (input != "" || (input == chartName)) {
            console.log(input);
            chartName = input;
            APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
            refreshChart(APICall);
        }
    }

    $('#stock1').keyup(function() {
        clearTimeout(typingTimer);
        if ($('#stock1').val) {
            typingTimer = setTimeout(updateStock, doneTypingInterval);
        }
    });

    $("input[name='stockValue']").change(updatePriceOption);

    function updatePriceOption() {
        priceOption = $("input[name='stockValue']:checked").val();
        console.log(priceOption);
        APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
        refreshChart(APICall);
    }

    $('#startDatePicker').change(function() {
        clearTimeout(typingTimer);
        if ($('#startDatePicker').val) {
            typingTimer = setTimeout(updateStartTime, doneTypingInterval);
        }
    });

    function updateStartTime() {
        console.log("update");
        input = $('#startDatePicker').val();
    }




    function updateStock() {
        var input = $('#stock1').val();
        if (companyMap.has(input)) {
            console.log(input);
            stock1Name = input;
            APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
            refreshChart(APICall);
        } else if (reversedMap.has(input)) {
            stock1Name = reversedMap.get(input);
            APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
            refreshChart(APICall);
        }
    }

    $('#add').click(function() {
        $('#secondOne').toggle("fast");
    });
    $('#finish').click(function() {

        // Spin waits until Ajax call has completed - next segment needs updated companyMap
        // Retrieves start date, end date, and stock options from user input fields
        chartName = $("#chartName").val();
        sDate = $("#startDatePicker").val();
        eDate = $("#endDatePicker").val();
        startDate = (sDate != '') ? ('start_date=' + sDate) : '';
        endDate = (eDate != '') ? ('&end_date=' + eDate) : '';
        stock1Name = $("#stock1").val();
        stock1 = reversedMap.get(stock1Name);
        stock2 = $("#stock2").val();
        stock2Completed = (stock2 == "") ? true : false;
        priceOption = document.querySelector('input[name="stockValue"]:checked').value;
        //        priceOption = (stockValue == "Low") ? 3 : (stockValue == "High") ? 2 : 1;

        // Forms API call from user inputs
        APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1 + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
        console.log(APICall);

        // TD: What if company has no stock info for given date range?
        // Hint: use newest available date range from the API data before accessing stockData
        console.log(svgChildren);

        var parameters = JSON.stringify({
            svg: svgChildren,
            company: stock1Name,
            start_date: sDate,
            end_date: eDate,
            chartName: chartName
        });

        // Send relevant input, including SVG, to PHP
        $.ajax({
                type: 'POST',
                url: 'ChartToDB.php',
                data: {
                    'param': parameters
                },
                dataType: 'json'
            })
            .done(function(data) {
                console.log('done');
                console.log(data);
                //window.location.replace("test.php");
            })
            .fail(function(data) {
                console.log('failure');
                console.log(data);
            });
    });
});
