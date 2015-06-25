$(document).ready(function(){
	$(".comment").click(function(event){
		event.preventDefault();
		$(".scroll-down").toggleClass("hidden");
	});
	$(".responder").click(function(event){
		event.preventDefault();
		$(".respuesta").toggleClass("hidden");
	});
	$(".active-slide").click(function(event){
		event.preventDefault();
		$(".list-slide").toggleClass("active");
	});
	$("#up").click(function(event){
		event.preventDefault();
		$("footer").toggleClass("active-up");
	});
});


$(function () { 
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Fruit Consumption'
        },
        xAxis: {
            categories: ['Apples', 'Bananas', 'Oranges']
        },
        yAxis: {
            title: {
                text: 'Fruit eaten'
            }
        },
        series: [{
            name: 'Jane',
            data: [1, 0, 4]
        }, {
            name: 'John',
            data: [5, 7, 3]
        }]
    });
});

