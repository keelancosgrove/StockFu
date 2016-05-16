stock1Name = "GOOGL";
stock2Name = "YHOO"
curDate = new Date();
endDate = '&end_date=' + curDate.toISOString().substring(0, 10);
sDate = (new Date());
sDate.setUTCFullYear((curDate.getUTCFullYear() - 1));
startDate = 'start_date=' + sDate.toISOString().substring(0, 10);
chartName = "Chart Name";
priceOption = 1;
publicChart = 0;
makeNew = 1;

errorData = 0;

dateMap = new Map();


var APICall = 'https://www.quandl.com/api/v3/datasets/WIKI/' + stock1Name + '.json?' + startDate + endDate + '&api_key=KDzspapgf7Mv2zbUmTgd';

$(function() {

  // Sets global variables, as well as flag to indicate Ajax call has completed
  returnCompanyMap(function(map, reversed, names) {
      companyMap = map;
      reversedMap = reversed;
      companyNames = names;
  });
  
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
