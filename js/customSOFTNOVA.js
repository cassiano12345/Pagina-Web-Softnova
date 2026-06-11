function getGraphFromJS(){
		
		var option = $("#chartType").find(":selected").text();
		
		if (option=="Circular"){
		
			
			document.getElementById("mainLayout").innerHTML = "";

			document.getElementById("mainLayout").innerHTML = '<div style="width:30%"><canvas id="canvas" height="450" width="450"></canvas></div>';
			new Chart(document.getElementById("canvas").getContext("2d")).Pie(pieData, {
			responsive: true
		});
		} 
		else if (option=="Barras")
		{
		
		document.getElementById("mainLayout").innerHTML = "";
	
	
		document.getElementById("mainLayout").innerHTML = '<div style="width:30%"><canvas id="canvas" height="450" width="450"></canvas></div>';
		new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData, {
			responsive: true
		});
		
		}
		else if (option=="Linhas")
		{
	
		document.getElementById("mainLayout").innerHTML = "";
	
	
		document.getElementById("mainLayout").innerHTML = '<div style="width:30%"><canvas id="canvas" height="450" width="450"></canvas></div>'
		
		new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData, {
			responsive: true
		});
			
		}
		
		else if (option=="Radar")
		{

		document.getElementById("mainLayout").innerHTML = "";

		
		document.getElementById("mainLayout").innerHTML = '<div style="width:30%" ><canvas id="canvas" height="450" width="450"></canvas></div>';
		new Chart(document.getElementById("canvas").getContext("2d")).Radar(radarChartData, {
			responsive: true
		});
		}
		else if (option=="Tabela")
		{

		document.getElementById("mainLayout").innerHTML = "";

		
		document.getElementById("mainLayout").innerHTML = '<div class="table-responsive"> <div style="width:30%" ><div id="canvas" height="450" width="450"><table class="table table-striped table-bordered" border="1" style="background-color:#FFFFCC;border-collapse:collapse;border:1px solid #FFCC00;color:#000000;width:100%" cellpadding="3" cellspacing="3"><thead><thead><tbody><td>Valor 1</th><th>Valor 2</th><th>Valor 3</th></tbody></thead><thead><thead><tbody><th>Valor 4</th><th>Valor 5</th><th>Valor 6</th></tbody></thead><thead><thead><tbody><th>Valor 7</th><th>Valor 8</th><th>Valor 9</th></tbody></thead><thead><thead><tbody><th>Valor 10</th><th>Valor 11</th><th>Valor 12</th></tbody></thead><thead><thead><tbody><th>Valor 13</th><th>Valor 14</th><th>Valor 15</th></tbody></thead></table></div></div></div>';		
		
		}
}

var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

	var barChartData = {
		labels : ["January","February","March","Abril","May","June","July"],
		datasets : [
			{
				fillColor : "rgba(220,220,220,0.5)",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
			},
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
			}
		]

	}
	var pieData = [
				{
					value: 300,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Red"
				},
				{
					value: 50,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Green"
				},
				{
					value: 100,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Yellow"
				},
				{
					value: 40,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "Grey"
				},
				{
					value: 120,
					color: "#4D5360",
					highlight: "#616774",
					label: "Dark Grey"
				}

			];
				var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			labels : ["January","February","March","April","May","June","July"],
			datasets : [
				{
					label: "My First dataset",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
				},
				{
					label: "My Second dataset",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
				}
			]

		}
			
var radarChartData = {
		labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
		datasets: [
			{
				label: "My First dataset",
				fillColor: "rgba(220,220,220,0.2)",
				strokeColor: "rgba(220,220,220,1)",
				pointColor: "rgba(220,220,220,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(220,220,220,1)",
				data: [65,59,90,81,56,55,40]
			},
			{
				label: "My Second dataset",
				fillColor: "rgba(151,187,205,0.2)",
				strokeColor: "rgba(151,187,205,1)",
				pointColor: "rgba(151,187,205,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(151,187,205,1)",
				data: [28,48,40,19,96,27,100]
			}
		]
	};

