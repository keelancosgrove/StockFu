$(function() {
    $('#edit').click(function() {
        $('#editPane').toggle("fast");
    });
    $('#add').click(function() {
        $('#secondOne').toggle("fast");
    });
    $('#delete').click(function() {
        if (confirm("Are you sure that you want to delete this chart?")) {
            var chartID = location.search.split('chartID=')[1];
            console.log(chartID);
            var params = JSON.stringify({
                chartID: chartID
            });
            $.ajax({
                    type: 'POST',
                    url: 'removeChart.php',
                    data: {
                        'param': params
                    },
                    datatype: 'json'
                })
                .done(function(data) {
                    console.log("Succeeded");
                    console.log(data);
                    window.location.replace("test.php");
                })
                .fail(function(data) {
                    console.log("Failed");
                    console.log(data);
                });
        }
    });
});
