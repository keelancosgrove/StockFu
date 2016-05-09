$(function() {


    chartCreation(APICall);
    svgChildren = document.getElementById("newChart").outerHTML;
    $('#add').click(function() {
        $('#secondOne').toggle("fast");
    });

    $('#finish').click(function() {

        var parameters = JSON.stringify({
            svg: svgChildren,
            company: stock1Name,
            start_date: startDate.substring(11),
            end_date: endDate.substring(10),
            chartName: chartName
        });

        console.log(parameters);

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
