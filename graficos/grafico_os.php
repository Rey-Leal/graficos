<?php
$conecta = mysqli_connect('localhost', 'u755286070_rdos', 'fS2!H]Ksm?Sb^Q8XD#', 'u755286070_rdos');
if (mysqli_connect_error()) :
	echo "Erro ao conectar!: " . mysqli_connect_error();
endif;

require("phplot/phplot.php");

$grafico = new PHPlot(400, 300);
$grafico->SetPrecisionY(0);
$grafico->SetFileFormat("png");
$grafico->SetTitle("Ordens de Servico");
$grafico->SetXTitle("Status");
$grafico->SetYTitle("Cadastros");
$grafico->SetLegend("OS");
$grafico->SetBackgroundColor('#F5F5F5');
$grafico->SetDataColors('#87CEFA');
$grafico->SetPlotType("bars"); //Tipo: lines, bars, boxes, bubbles, linepoints, ohlc, pie, points, squared, stackedarea, stackedbars, thinbarline

$dadosDoGrafico = array();

$sql =
	"SELECT os_status, 
	(CASE os_status WHEN 0 THEN 'Aguardando' WHEN 1 THEN 'Andamento' WHEN 2 THEN 'Parcial' WHEN 3 THEN 'Supervisao' WHEN 4 THEN 'Concluida' WHEN 99 THEN 'Cancelada' END) AS descricao, 
	COUNT(os_idOS) AS quantidade 
	FROM tb_os 
	GROUP BY os_status;";
$sql_result = mysqli_query($conecta, $sql);

while ($row = mysqli_fetch_assoc($sql_result)) {
	$aux = array();
	array_push($aux, $row["descricao"], $row["quantidade"]);
	array_push($dadosDoGrafico, $aux);
};

$grafico->SetDataValues($dadosDoGrafico);
$grafico->DrawGraph();