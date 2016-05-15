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
var publicChart = 0;

var errorData = 0;
var stockData;
var data;
var demo;
var maxDate;
var minDate;
var stockData2;
var priceYMax;
var dates = [];

var dateMap = new Map();


//setup before functions
var typingTimer; //timer identifier
var doneTypingInterval = 1000; //time in ms, 5 second for example



var APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';

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
        var secondAPICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock2 + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
        $.getJSON(secondAPICall, function(result2) {
            var data2 = result2["dataset"];
            stockData2 = data2["data"];
            for (i = 0; i < stockData2.length; i++) {
                if (stockData2[i][priceOption] > priceYMax) {
                    priceYMax = stockData2[i][priceOption];
                }
            }
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

        //Error handling for no data
        if (stockData == 0 && errorData == 0) {
            errorData = 1;
            $("#noDataMessage").toggle("fast");
            return;
        } else if(stockData != 0 && errorData == 1){
          errorData = 0;
          $("#noDataMessage").toggle("fast");
          d3.select("svg").remove();
          d3.select("#main").append("svg").attr("width", 1000).attr("height", 500).attr("id", "newChart");
        } else if( stockData != 0) {
          d3.select("svg").remove();
          d3.select("#main").append("svg").attr("width", 1000).attr("height", 500).attr("id", "newChart");
        } else {
          return;
        }


        demo = d3.select("#newChart");
        dateMap.clear();
        dates = [];
        for (i = 0; i < stockData.length; i++) {
            dateMap.set((new Date(stockData[i][0])).getTime(), stockData[i]);
            dates.push((new Date(stockData[i][0])).getTime());
        }
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
        priceYMax = stockData[0][priceOption];
        for (i = 0; i < stockData.length; i++) {
            if (stockData[i][priceOption] > priceYMax) {
                priceYMax = stockData[i][priceOption];
            }
        }


        // Sets up x and y axis
        var xScale = d3.time.scale().range([margins.left, width - margins.right]).domain([minDate, maxDate]);
        var yScale = d3.scale.linear().range([height - margins.top, margins.bottom]);

        function displaychart() {
            priceYMax += 20;
            yScale.domain([0, priceYMax]);

            var xAxis = d3.svg.axis().scale(xScale).ticks(8);
            var yAxis = d3.svg.axis().scale(yScale).orient("left");
            var formatTime = d3.time.format("%e %B");
            // Orients axes
            demo.append("svg:g").attr("class", "axis").attr("transform", "translate(0," + (height - margins.bottom) + ")").call(xAxis);
            demo.append("svg:g").attr("class", "axis").attr("transform", "translate(" + margins.left + ",0)").call(yAxis);

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
                .datum(stockData)
                .attr('d', lineGen)
                .attr("id", "lineChart")
                .attr('stroke', 'green')
                .attr('stroke-width', 2)
                .attr('fill', 'none');

            demo.on("mouseover", function() {
                    // Allows the tooltip to display
                    demo.select("#charTooltip").style("display", null);
                })
                .on("mouseout", function() {
                    // Causes tooltip text to dissapear upon removing mouse from line chart
                    demo.select("#charTooltip").style("display", "none");
                })
                .on("mousemove", function() {
                    // Updates position and text in tooltip with correct information based on where mouse is on chart
                    var date = xScale.invert(d3.event.pageX).toString().split(" ");
                    var date_formatted = new Date(xScale.invert(d3.event.pageX).toString());
                    var beforedates = dates.filter(function(d) {
                        return d - date_formatted < 0;
                    });
                    var dateData = dateMap.get(beforedates[0]);
                    demo.select("#charTooltip")

                    .attr("class", "thisText")
                    .attr("x", 320)
                    .attr("y", 15)
                    .attr("fill", "black").style("text-anchor", "middle")
                    // Sets text to tooltip with stock information from given date
                    .text(date[1] + " " + date[2] + " " + date[3] +
                        " Open: " + dateData[1] +
                        " High: " + dateData[2] +
                        " Low: " + dateData[3] +
                        " Close: " + dateData[4] +
                        " Volume: " + dateData[5])
                    .style("font-weight","bold");

                });

            // Adds x-axis label
            demo.append("text")
                .attr("x", width / 2)
                .attr("y", height + 30)
                .style("text-anchor", "middle")
                .style("font-size", 16)
                .style("font-family", "Lato")
                .text("Date");

            // Adds y-axis label
            demo.append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 10).attr("x", -height / 2)
                .style("text-anchor", "middle")
                .style("font-family", "Lato")
                .text("Open Stock Price (in USD)");

            // Adds chart title
            demo.append("text")
                .attr("x", (width / 2))
                .attr("y", 0 - (margins.top / 2))
                .attr("text-anchor", "middle")
                .attr("id", "charTooltip")
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


    chartCreation(APICall);
    svgChildren = document.getElementById("newChart").outerHTML;
    $('#add').click(function() {
        $('#secondOne').toggle("fast");
    });

    $('#finish').click(function() {

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
        publicChart = (document.getElementById("public").checked) ? 1 : 0;
        console.log("public? " + public);
        //        priceOption = (stockValue == "Low") ? 3 : (stockValue == "High") ? 2 : 1;

        // Forms API call from user inputs
        APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1 + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';
        console.log(APICall);

        // TD: What if company has no stock info for given date range?
        // Hint: use newest available date range from the API data before accessing stockData
        console.log(svgChildren);

        // Save minDate, maxDate, and priceYMax to reconstruct scales
        //
        console.log(Array.from(dateMap.entries()));
        var parameters = JSON.stringify({
            svg: svgChildren,
            company: stock1Name,
            start_date: startDate.substring(11),
            end_date: endDate.substring(10),
            chartName: chartName,
            minDate: minDate.toString(),
            maxDate: maxDate.toString(),
            priceYMax: priceYMax,
            publicChart: publicChart,
            dates: JSON.stringify(dates),
            dateMap: JSON.stringify(Array.from(dateMap.entries()))
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

                //  window.location.replace("test.php");

                window.location.replace("home.php");

            })
            .fail(function(data) {
                console.log('failure');
                console.log(data);
            });
    });
});
