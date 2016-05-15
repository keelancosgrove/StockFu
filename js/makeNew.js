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

                window.location.replace("test.php");

            })
            .fail(function(data) {
                console.log('failure');
                console.log(data);
            });
    });
});
