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

        var parameters = JSON.stringify({
            svg: svgChildren,
            company: stock1Name,
            start_date: startDate,
            end_date: endDate,
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
                //window.location.replace("makeNew.php");
            })
            .fail(function(data) {
                console.log('failure');
                console.log(data);
            });
    });
});
