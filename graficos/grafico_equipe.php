<?php
$conecta = mysqli_connect('localhost', 'u755286070_rdos', 'fS2!H]Ksm?Sb^Q8XD#', 'u755286070_rdos');
if (mysqli_connect_error()) :
	echo "Erro ao conectar!: " . mysqli_connect_error();
endif;

require("phplot/phplot.php");

$grafico = new PHPlot(400, 300);
$grafico->SetPrecisionY(0);
$grafico->SetFileFormat("png");
$grafico->SetTitle("Equipes");
$grafico->SetXTitle("Status");
$grafico->SetYTitle("Cadastros");
$grafico->SetLegend("Equipes");
$grafico->SetBackgroundColor('#F5F5F5');
$grafico->SetDataColors('#00FF7F');
$grafico->SetPlotType("bars"); //Tipo: lines, bars, boxes, bubbles, linepoints, ohlc, pie, points, squared, stackedarea, stackedbars, thinbarline

$dadosDoGrafico = array();

$sql =
	"SELECT COUNT(DISTINCT Eq.eq_idEquipe) AS quantidade, 'Em campo' AS descricao 
	FROM tb_equipe AS Eq, tb_os AS Os 
	WHERE Eq.eq_idEquipe = Os.os_idEquipe AND Os.os_status IN (1, 2, 3)
	UNION
	SELECT((SELECT COUNT(Eq1.eq_idEquipe) FROM tb_equipe AS Eq1) - 
	(SELECT COUNT(DISTINCT Eq2.eq_idEquipe) FROM tb_equipe AS Eq2, tb_os AS Os 
	WHERE Eq2.eq_idEquipe = Os.os_idEquipe AND Os.os_status IN (1, 2, 3))
	) AS quantidade, 'Disponivel' AS descricao;";
$sql_result = mysqli_query($conecta, $sql);

while ($row = mysqli_fetch_assoc($sql_result)) {
	$aux = array();
	array_push($aux, $row["descricao"], $row["quantidade"]);
	array_push($dadosDoGrafico, $aux);
};

$grafico->SetDataValues($dadosDoGrafico);
$grafico->DrawGraph();